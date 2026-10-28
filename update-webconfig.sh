#!/bin/bash

set -e
trap 'echo "[ERROR] Error in line $LINENO when executing: $BASH_COMMAND"' ERR

# in case /var/log is full ... delete some logs
echo test > /var/log/.test 2>/dev/null || rm -f /var/log/*.log

function aptInstall() {
    if ! apt install -y --no-install-recommends --no-install-suggests "$@"; then
        apt update
        apt install -y --no-install-recommends --no-install-suggests "$@"
    fi
}

aptInstall git

cd /tmp
updir=/tmp/update-webconfig

rm -rf $updir
git clone --depth 1 https://github.com/ADSBexchange/adsbx-webconfig.git $updir

cd $updir
bash install.sh dont_reset_config


cd /tmp
rm -rf $updir

echo "8.2.$(date '+%y%m%d')" > /boot/adsbx-version-webconfig

echo '--------------------------------------------'
echo '        update-webconfig complete.          '
echo '--------------------------------------------'
