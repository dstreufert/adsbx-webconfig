#!/bin/bash
set -e

echo "Starting update..."  > /tmp/web_display_log
chmod a+rw /tmp/web_display_log

rm -f /run/bg-update.sh
cp /adsbexchange/webconfig/helpers/bg-update.sh /run/bg-update.sh

systemd-run --on-active=3 /run/bg-update.sh

sleep 1
exit
