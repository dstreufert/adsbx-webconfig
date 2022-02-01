#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
apt update
#apt upgrade -y

bash -c "$(wget -nv -O - https://raw.githubusercontent.com/ADSBexchange/adsbx-update/main/update-adsbx.sh)"  >> /tmp/web_display_log
bash -c "$(wget -nv -O - https://raw.githubusercontent.com/dstreufert/adsbx-webconfig/master/update-webconfig.sh)"  >> /tmp/web_display_log

echo "rebooting..." >> /tmp/web_display_log
sleep 5
reboot now
