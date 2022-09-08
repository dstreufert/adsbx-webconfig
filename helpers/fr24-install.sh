#!/bin/bash

# remove fr24feed in package form
apt purge -y fr24feed &>/dev/null
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

#wget -O fr24.deb https://repo-feed.flightradar24.com/rpi_binaries/fr24feed_1.0.29-8_armhf.deb
wget -O fr24.deb https://repo-feed.flightradar24.com/rpi_binaries/fr24feed_1.0.30-3_armhf.deb

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

ExecStart=/bin/bash -c "stdbuf -oL -eL /usr/bin/fr24feed | stdbuf -oL -eL sed -u -e 's/[0-9,-]* [0-9,:]* | //' | stdbuf -oL -eL grep -v -e '::on_periodic_refresh:' -e 'Synchronizing time via NTP' -e 'synchronized correctly' -e 'Pinging' -e 'time references AC' -e 'mlat.... [A-F,0-9]*' -e '.feed..n.ping ' -e '.feed..i.sent' -e 'syncing stream' -e 'saving bandwidth' | stdbuf -oL -eL perl -ne 'print if (not /mlat..i.Stats/ or ($n++ % 58 == 3)) and (not /sent.*aircraft/ or ($m++ % 50 == 2)) and (not /stats.sent/ or ($k++ % 6 == 1)) ;$|=1'"

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
systemctl restart fr24feed
