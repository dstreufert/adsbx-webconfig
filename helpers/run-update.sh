#!/bin/bash
set -e

rm -f /run/bg-update.sh
cp /adsbexchange/webconfig/helpers/bg-update.sh /run/bg-update.sh

systemd-run --on-active=3 /run/bg-update.sh

sleep 1
exit
