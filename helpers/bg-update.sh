#!/bin/bash

log=/adsbexchange/adsbx-update.log
rm -f $log
exec &> >(tee -a "$log")

export DEBIAN_FRONTEND=noninteractive
apt update
#apt upgrade -y

bash -c "$(wget -nv -O - https://raw.githubusercontent.com/ADSBexchange/adsbx-update/main/update-adsbx.sh)"
bash -c "$(wget -nv -O - https://raw.githubusercontent.com/dstreufert/adsbx-webconfig/master/update-webconfig.sh)"

echo "rebooting..."
sleep 5
reboot now
