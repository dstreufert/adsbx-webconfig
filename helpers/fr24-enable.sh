#!/bin/bash

source /boot/adsb-config.txt
FR24_EMAIL="$1"

if ! command -v expect; then
    apt update
    apt install -y expect
fi

# remove fr24feed in package form
apt purge -y fr24feed
# remove the fr24feed updater (should be removed with the package but let's make real sure)
rm -f /etc/cron.d/fr24feed_updater

set -e

if ! id -u fr24 &>/dev/null; then
    adduser --system --no-create-home fr24 || true
    addgroup fr24 || true
    adduser fr24 fr24 || true
fi
cd
rm /tmp/fr24 -rf
mkdir -p /tmp/fr24
cd /tmp

wget -O fr24.deb https://repo-feed.flightradar24.com/rpi_binaries/fr24feed_1.0.29-8_armhf.deb

dpkg -x fr24.deb fr24
cp -f fr24/usr/bin/fr24feed* /usr/bin

cat >/etc/systemd/system/fr24feed.service <<"EOF"
[Unit]
Description=Flightradar24 Decoder & Feeder
After=network-online.target

[Service]
Type=simple
Restart=always
ExecStartPre=-/bin/rm -f /dev/shm/decoder.txt
ExecStopPost=-/bin/rm -f /dev/shm/decoder.txt

ExecStart=/bin/bash -c "stdbuf -oL -eL /usr/bin/fr24feed | stdbuf -oL -eL sed -u -e 's/[0-9,-]* [0-9,:]* | //' | stdbuf -oL -eL grep -v -e '::on_periodic_refresh:' -e 'Synchronizing time via NTP' -e 'synchronized correctly' -e 'Pinging' -e 'time references AC' -e 'mlat.... [A-F,0-9]*' -e '.feed..n.ping ' -e 'syncing stream' -e 'saving bandwidth' | stdbuf -oL -eL perl -ne 'print if (not /mlat..i.Stats/ or ($n++ % 58 == 3)) and (not /feed....sent/ or ($m++ % 250 == 10)) and (not /stats.sent/ or ($k++ % 6 == 1)) ;$|=1'"

User=fr24
PermissionsStartOnly=true
SyslogIdentifier=fr24feed
SendSIGHUP=yes
TimeoutStopSec=5
RestartSec=0
StartLimitInterval=5
StartLimitBurst=20

[Install]
WantedBy=multi-user.target
EOF


systemctl enable fr24feed


# Regular Expressions
# shellcheck disable=SC1112
REGEX_PATTERN_VALID_EMAIL_ADDRESS='^[a-z0-9!#$%&*+=?^_‘{|}~-]+(?:\.[a-z0-9!$%&*+=?^_{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$'
REGEX_PATTERN_FR24_SHARING_KEY='^\+ Your sharing key \((\w+)\) has been configured and emailed to you for backup purposes\.'
REGEX_PATTERN_FR24_RADAR_ID='^\+ Your radar id is ([A-Za-z0-9\-]+), please include it in all email communication with us\.'


# Temp files - created in one dir
TMPDIR_FR24SIGNUP=/tmp/fr24feed-signup
mkdir -p "$TMPDIR_FR24SIGNUP"
TMPFILE_FR24SIGNUP_EXPECT="$TMPDIR_FR24SIGNUP/TMPFILE_FR24SIGNUP_EXPECT"
TMPFILE_FR24SIGNUP_LOG="/tmp/fr24_signup_log"

rm -f $TMPFILE_FR24SIGNUP_LOG

SPAWN_CMD="spawn /usr/bin/fr24feed --signup"

if [[ ${ALTITUDE: -2} == "ft" ]]; then
    ALT_FT=${ALTITUDE:0:-2}
elif [[ ${ALTITUDE: -1} == "m" ]]; then
    ALT_M=${ALTITUDE:0:-1}
    ALT_FT=$(( ALT_M * 82 / 25 ))
else
    # no suffix implies meters
    ALT_FT=$(( ALTITUDE * 82 / 25 ))
fi

# use existing sharing key if one exists to avoid stupid issues
SHARING_KEY=$(grep -s -e '^fr24key' /etc/fr24feed.ini  | cut -d'"' -f2)

function write_fr24_expectscript() {
    {
        echo '#!/usr/bin/env expect --'
        echo 'set timeout 120'
        echo "${SPAWN_CMD}"
        echo "sleep 3"
        echo 'expect "Step 1.1 - Enter your email address (username@domain.tld)\r\n$:"'
        echo "send -- \"${FR24_EMAIL}\n\""
        echo 'expect "Step 1.2 - If you used to feed FR24 with ADS-B data before, enter your sharing key.\r\n"'
        echo 'expect "$:"'
        echo "send \"$SHARING_KEY\r\""
        echo 'expect "Step 1.3 - Would you like to participate in MLAT calculations? (yes/no)$:"'
        echo "send \"no\r\""
        echo 'set timeout 15'
        echo 'expect "Would you like to use autoconfig (*yes*/no)$:"'
        echo "send \"no\r\""
        echo 'set timeout 120'
        if [[ -z "$SHARING_KEY" ]]; then
            echo 'expect "Enter airport code or leave empty$:"'
            echo "send \"\r\""
            echo "send \"$SHARING_KEY\r\""
            echo "expect \"Step 3.A - Enter antenna's latitude (DD.DDDD)\r\n\$:\""
            echo "send -- \"${LATITUDE}\r\""

            echo "expect \"Step 3.B - Enter antenna's longitude (DDD.DDDD)\r\n\$:\""
            echo "send -- \"${LONGITUDE}\r\""
            echo "expect \"Step 3.C - Enter antenna's altitude above the sea level (in feet)\r\n\$:\""
            echo "send -- \"${ALT_FT}\r\""
            echo 'expect "Would you like to continue using these settings?"'
            echo 'expect "Enter your choice (yes/no)$:"'
            echo "send \"yes\r\""
        fi
        echo 'expect "Enter your receiver type (1-7)$:"'
        echo "send \"4\r\""
        echo 'expect "Enter your connection type (1-2)$:"'
        echo "send \"1\r\""
        echo 'expect "$:"'
        echo "send \"127.0.0.1\r\""
        echo 'expect "$:"'
        echo "send \"30005\r\""
        echo 'expect "Step 5.1 - Would you like to enable RAW data feed on port 30334 (yes/no)$:"'
        echo "send \"no\r\""
        echo 'expect "Step 5.2 - Would you like to enable Basestation data feed on port 30003 (yes/no)$:"'
        echo "send \"no\r\""
        echo 'expect "Step 6 - Please select desired logfile mode:"'
        echo 'expect "Select logfile mode (0-2)$:"'
        echo "send \"0\r\""
        echo 'expect "Saving settings to /etc/fr24feed.ini...OK"'
    } > "$TMPFILE_FR24SIGNUP_EXPECT"
}


# ========== MAIN SCRIPT ========== #

# Sanity checks
if ! echo "$FR24_EMAIL" | grep -P "$REGEX_PATTERN_VALID_EMAIL_ADDRESS" > /dev/null 2>&1; then
  echo "ERROR: Please set FR24_EMAIL to a valid email address"
  exit 1
fi

# write out expect script
write_fr24_expectscript

systemctl stop fr24feed

# run expect script & interpret output
if ! expect "$TMPFILE_FR24SIGNUP_EXPECT" > "$TMPFILE_FR24SIGNUP_LOG" 2>&1; then
  echo "ERROR: Problem running flightradar24 sign-up process :-("
  echo ""
  cat "$TMPFILE_FR24SIGNUP_LOG"
  systemctl restart fr24feed
  exit 1
fi

systemctl restart fr24feed

# clean up
rm -r "$TMPDIR_FR24SIGNUP"
