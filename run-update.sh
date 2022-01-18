#!/bin/bash

echo "Starting update..."  > /tmp/web_display_log
sudo chmod 777 /tmp/web_display_log

systemd-run --on-active=3 /home/pi/adsbexchange/webconfig/bg-update.sh

sleep 1
exit
