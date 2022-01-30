#!/bin/bash

echo "Starting update..."  > /tmp/web_display_log
chmod a+rw /tmp/web_display_log

systemd-run --on-active=3 /adsbexchange/webconfig/helpers/bg-update.sh

sleep 1
exit
