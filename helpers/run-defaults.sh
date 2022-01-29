#!/bin/bash

echo "Resetting to defaults..."  > /tmp/web_display_log
chmod a+r /tmp/web_display_log

/adsbexchange/update/resetdefaults.sh >> /tmp/web_display_log

echo "Rebooting...." >> /tmp/web_display_log
sleep 5
reboot now
exit
