#!/bin/bash


bash -c "$(wget -nv -O - https://raw.githubusercontent.com/ADSBexchange/adsbx-update/main/update-adsbx.sh)"  >> /tmp/web_display_log

echo "rebooting..." >> /tmp/web_display_log
sleep 5
sudo reboot now
