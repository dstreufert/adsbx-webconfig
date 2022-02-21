#!/bin/bash

# Use LEDs for custom status indications

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

function alternate_leds {
  NOW=$(date +%s%N)
  while [ $NOW -le $NEXTCHECK ]
  do
    on red; off green
    sleep 0.5
    off red; on green
    sleep 0.5
    NOW=$(date +%s%N)
  done
}

function greenflash {
  NOW=$(date +%s%N)
  while [ $NOW -le $NEXTCHECK ]
  do
    on green
    sleep $PERIOD
    off green
    sleep $PERIOD
    NOW=$(date +%s%N)
  done
}

function redflash {

  off red

  for (( ; ; ))
  do
    for i in 1 2 3 4 5
    do
      #echo Flash num $i
      if [ ${FAILURES[$i]} == "FAIL" ]; then
        for flash in $( seq 1 $i )
        do
          on red
          sleep 0.2
          off red
          sleep 0.2
        done
        sleep 1
      fi

      NOW=$(date +%s%N)
      let TIMELEFT=$NEXTCHECK-$NOW
      #Exit loop if < 2 seconds left
      if [ $TIMELEFT -lt 2000000000 ]; then break; fi
    done

    if [ $TIMELEFT -lt 2000000000 ]; then break; fi
  done

}

function failurestats {

# Failure 1 - no aircraft being received
AIRCRAFTCOUNT=$(jq '.aircraft_with_pos' /run/adsbexchange-feed/status.json)
if [[ $AIRCRAFTCOUNT -le 0 ]];
then
  FAILURES[1]="FAIL"
else
  FAILURES[1]="PASS"
fi

# Failure 2 - dump978-fa service failed/failing _or_ location not set
let DUMP978AGE=$(awk '/^now/ {print $3; exit}' /proc/timer_list)/1000000000-$(systemctl show dump978-fa.service --value --property=InactiveExitTimestampMonotonic)/1000000
#echo dump978 age $DUMP978AGE
if [[ $DUMP978AGE -le 60 ]];
then
  FAILURES[2]="FAIL"
else
  FAILURES[2]="PASS"
fi

cat /tmp/webconfig/location | grep "Location not set." > /dev/null
if [[ $? -eq 0 ]];
then
  FAILURES[2]="FAIL"
fi


# Failure 3 - readsb service failed/failing
let READSBAGE=$(awk '/^now/ {print $3; exit}' /proc/timer_list)/1000000000-$(systemctl show readsb.service --value --property=InactiveExitTimestampMonotonic)/1000000
#echo readsb age is $READSBAGE
if [[ $READSBAGE -le 60 ]];
then
  FAILURES[3]="FAIL"
else
  FAILURES[3]="PASS"
fi

# Failure 4 - no connection to ADSBx
netstat -apn | grep adsbxfeeder | grep -v 127.0.0.1 >> /dev/null
if [[ $? -eq 0 ]];
then
  FAILURES[4]="PASS"
else
  FAILURES[4]="FAIL"
fi

# Failure 5 - Temp/Voltage fail
let TEMPSTAT=$(vcgencmd get_throttled | cut -d "x" -f 2)
if [[ $TEMPSTAT -eq 0 ]];
then
  FAILURES[5]="PASS"
else
  FAILURES[5]="FAIL"
fi
}


failurestats

for (( ; ; ))
do

  NOW=$(date +%s%N)
  let NEXTCHECK=$NOW+11000000000

  if [[ -e /tmp/webconfig_priv/unlock ]];
  then
    alternate_leds
  else
    AIRCRAFT=$(jq '.aircraft_with_pos' /run/adsbexchange-feed/status.json)
    if [[ ! $AIRCRAFT -gt 0 ]]; then
      AIRCRAFT=0
    fi
    PERIOD=$(lua -e "print(11/(($AIRCRAFT+1)*2))")
    greenflash &
    redflash &
    wait
    failurestats
    echo ${FAILURES[@]}
  fi

done

