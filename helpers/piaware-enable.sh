#!/bin/bash

FEEDER_ID="$1"

URL=""
grep -qs -e buster /etc/os-release && URL="https://flightaware.com/adsb/piaware/files/packages/pool/piaware/p/piaware-support/piaware-repository_6.1_all.deb"
grep -qs -e bullseye /etc/os-release && URL="https://flightaware.com/adsb/piaware/files/packages/pool/piaware/p/piaware-support/piaware-repository_7.1_all.deb"

wget -O /tmp/piaware-repo.deb "$URL"

apt purge -y piaware-repository &>/dev/null
rm -f /etc/apt/sources.list.d/piaware-*.list

dpkg -i /tmp/piaware-repo.deb

apt update
apt install -y piaware

if [[ -n "$FEEDER_ID" ]]; then
    piaware-config feeder-id "$FEEDER_ID"
    systemctl restart piaware
fi

sleep 10
