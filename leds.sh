#!/bin/bash

# Use LEDs for custom status indications

trap "kill -SIGINT $$" SIGTERM

if [ "$(id -u)" != "0" ]; then
  echo -e "\033[33m"
  echo "This script must be ran using sudo or as root."
  echo -e "\033[37m"
  exit 1
fi

# load bash sleep builtin if available
[[ -f /usr/lib/bash/sleep ]] && enable -f /usr/lib/bash/sleep sleep || true

function on() {
  for led in "$@"; do
    [[ $led == "green" ]] && led="led0"
    [[ $led == "red" ]] && led="led1"
    echo 1 > "/sys/class/leds/$led/brightness"
  done
}

function off() {
  for led in "$@"; do
    [[ $led == "green" ]] && led="led0"
    [[ $led == "red" ]] && led="led1"
    echo 0 > "/sys/class/leds/$led/brightness"
  done
}

function greenflash {
  NEXTCHECK=$(( ${EPOCHREALTIME/.}/1000 + $1 * 1000 ))
  PERIOD="$2"

  while [ "$((${EPOCHREALTIME/.}/1000))" -le $NEXTCHECK ]
  do
    on green
    sleep $PERIOD
    off green
    sleep $PERIOD
  done
}

function redflash {
  off red

  sleep 1
  for i in 1 2 3 4 5
  do
    #echo Flash num $i
    if [ ${FAILURES[$i]} == "FAIL" ]; then
      for flash in $( seq 1 $i )
      do
        on red
        sleep 0.25
        off red
        sleep 0.25
      done
      sleep 2
    fi
  done
}

START_MONO=$(($(awk '/^now/ {print $3; exit}' /proc/timer_list)/1000000000))

function failurestats {
  # Failure 1 - no aircraft being received
  AIRCRAFTCOUNT=$(jq '.aircraft_with_pos' /run/adsbexchange-feed/status.json)
  if [[ ! $AIRCRAFTCOUNT -gt 0 ]]; then
    AIRCRAFTCOUNT=0
  fi

  if [[ $AIRCRAFTCOUNT -le 0 ]];
  then
    FAILURES[1]="FAIL"
  else
    FAILURES[1]="PASS"
  fi

  UPTIME=$(($(awk '/^now/ {print $3; exit}' /proc/timer_list)/1000000000))
  SINCE_START=$(( UPTIME - START_MONO ))
  # Failure 2 - dump978-fa service failed/failing _or_ location not set
  FAILURES[2]="PASS"
  if (( SINCE_START > 75)) && systemctl is-enabled dump978-fa &>/dev/null; then
    let DUMP978AGE=$SINCE_START-$(systemctl show dump978-fa.service --value --property=InactiveExitTimestampMonotonic)/1000000
    #echo dump978 age $DUMP978AGE
    if [[ $DUMP978AGE -le 60 ]];
    then
      FAILURES[2]="FAIL"
    fi
  fi

  cat /tmp/webconfig/location | grep "Location not set." > /dev/null
  if [[ $? -eq 0 ]];
  then
    FAILURES[2]="FAIL"
  fi

  # Failure 3 - readsb service failed/failing
  FAILURES[3]="PASS"

  if (( SINCE_START > 75)) && systemctl is-enabled readsb &>/dev/null; then
    let READSBAGE=$SINCE_START-$(systemctl show readsb.service --value --property=InactiveExitTimestampMonotonic)/1000000
    if [[ $READSBAGE -le 60 ]];
    then
      FAILURES[3]="FAIL"
    fi
  fi

  # Failure 4 - no connection to ADSBx
  netstat -apn | grep adsbxfeeder | grep -v 127.0.0.1 >> /dev/null
  if [[ $? -eq 0 ]];
  then
    FAILURES[4]="PASS"
  else
    FAILURES[4]="FAIL"
  fi

  # Failure 5 - Temp/Voltage fail but ignore soft limit signified by an 80000
  let TEMPSTAT=$(vcgencmd get_throttled | cut -d "x" -f 2)
  if [[ $TEMPSTAT -eq 0 ]] || [[ $TEMPSTAT -eq 80000 ]];
  then
    FAILURES[5]="PASS"
  else
    FAILURES[5]="FAIL"
  fi
}


for (( ; ; ))
do
  while [[ -e /tmp/webconfig_priv/unlock ]]; do
    # alternate leds:
    on red; off green
    sleep 0.5
    off red; on green
    sleep 0.5
  done

  if [[ -e /tmp/webconfig_priv/unlock ]]; then
    continue
  fi

  failurestats

  OVERALL="PASS"
  for i in "${FAILURES[@]}"; do
    if [[ $i != "PASS" ]]; then
      OVERALL="FAIL"
    fi
  done

  if [[ $OVERALL != PASS ]]; then
    #echo ${FAILURES[@]}

    # only have green led on while flashing red when aircraft are still being received
    if [[ ! $AIRCRAFTCOUNT -gt 0 ]]; then
        off green
    else
        on green
    fi

    redflash

  else
    off red green
    PERIOD=$(lua -e "print(11/(($AIRCRAFTCOUNT+1)*2))")
    DURATION=11
    # flash green for 11 seconds with calculated period
    greenflash "$DURATION" "$PERIOD"
  fi

done

