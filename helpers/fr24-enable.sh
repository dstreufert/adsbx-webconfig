#!/bin/bash

source /boot/adsb-config.txt
FR24_EMAIL="$1"

if ! command -v expect; then
    apt update
    apt install -y expect
fi

set -e

cd

# Regular Expressions
# shellcheck disable=SC1112
REGEX_PATTERN_VALID_EMAIL_ADDRESS='^[a-z0-9!#$%&*+=?^_‘{|}~-]+(?:\.[a-z0-9!$%&*+=?^_{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$'
REGEX_PATTERN_FR24_SHARING_KEY='^\+ Your sharing key \((\w+)\) has been configured and emailed to you for backup purposes\.'
REGEX_PATTERN_FR24_RADAR_ID='^\+ Your radar id is ([A-Za-z0-9\-]+), please include it in all email communication with us\.'


# Temp files
TMPFILE_FR24SIGNUP_EXPECT="/root/.fr24_signup_expect"
TMPFILE_FR24SIGNUP_LOG="/root/.fr24_signup_log"

rm -f $TMPFILE_FR24SIGNUP_LOG

if [[ ${ALTITUDE: -2} == "ft" ]]; then
    ALT_FT=${ALTITUDE:0:-2}
elif [[ ${ALTITUDE: -1} == "m" ]]; then
    ALT_M=${ALTITUDE:0:-1}
    ALT_FT=$(( ALT_M * 82 / 25 ))
else
    # no suffix implies meters
    ALT_FT=$(( ALTITUDE * 82 / 25 ))
fi

if [[ -n "$2" ]]; then
    SHARING_KEY="$2"
else
    # use existing sharing key if one exists to avoid stupid issues
    SHARING_KEY=$(grep -s -e '^fr24key' /etc/fr24feed.ini  | cut -d'"' -f2)
fi

cat > "$TMPFILE_FR24SIGNUP_EXPECT" <<EOF
#!/usr/bin/env expect --
set timeout 60
spawn /usr/bin/fr24feed --signup
sleep 5
expect "Step 1.1 - Enter your email address (username@domain.tld)\r\n$:"
send -- "${FR24_EMAIL}\n"
expect "Step 1.2 - If you used to feed FR24 with ADS-B data before, enter your sharing key.\r\n"
expect "$:"
send "${SHARING_KEY}\r"
expect "Step 1.3 - Would you like to participate in MLAT calculations? (yes/no)$:"
send "no\r"
EOF


if [[ -z "$SHARING_KEY" ]]; then

cat >> "$TMPFILE_FR24SIGNUP_EXPECT" <<EOF
expect "Enter airport code or leave empty$:"
send "\r"
send "\r"
expect "Step 3.A - Enter antenna's latitude (DD.DDDD)\r\n$:"
send -- "${LATITUDE}\r"
expect "Step 3.B - Enter antenna's longitude (DDD.DDDD)\r\n$:"
send -- "${LONGITUDE}\r"
expect "Step 3.C - Enter antenna's altitude above the sea level (in feet)\r\n$:"
send -- "${ALT_FT}\r"
expect {
    "Would you like to try again?" { exit 1 }
    "Would you like to continue using these settings?"
}
expect "Enter your choice (yes/no)$:"
send "yes\r"
EOF

fi

cat >> "$TMPFILE_FR24SIGNUP_EXPECT" <<EOF
set timeout 5
expect "Would you like to use autoconfig (*yes*/no)$:" { send "no\r" }
set timeout 60
expect "Enter your receiver type (1-7)$:"
send "4\r"
expect "Enter your connection type (1-2)$:"
send "1\r"
expect "$:"
send "127.0.0.1\r"
expect "$:"
send "30005\r"
expect "Step 5.1 - Would you like to enable RAW data feed on port 30334 (yes/no)$:"
send "no\r"
expect "Step 5.2 - Would you like to enable Basestation data feed on port 30003 (yes/no)$:"
send "no\r"
expect "Step 6 - Please select desired logfile mode:"
expect "Select logfile mode (0-2)$:"
send "0\r"
expect "Saving settings to /etc/fr24feed.ini...OK"
EOF


# ========== MAIN SCRIPT ========== #

# Sanity checks
if ! echo "$FR24_EMAIL" | grep -P "$REGEX_PATTERN_VALID_EMAIL_ADDRESS" > /dev/null 2>&1; then
  echo "ERROR: Please set FR24_EMAIL to a valid email address"
  exit 1
fi

systemctl stop fr24feed || true

# run expect script & interpret output
if ! expect "$TMPFILE_FR24SIGNUP_EXPECT" > "$TMPFILE_FR24SIGNUP_LOG" 2>&1; then
  echo "ERROR: Problem running flightradar24 sign-up process :-("
  echo ""
  cat "$TMPFILE_FR24SIGNUP_LOG"
  echo ""
  echo ""
  systemctl restart fr24feed || true
  exit 1
fi

systemctl restart fr24feed || true

sleep 10
