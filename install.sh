#!/bin/bash

if [ $(id -u) -ne 0 ]; then
  echo -e "This script must be run as root. \n"
  exit 1
fi

trap 'echo "[ERROR] Error in line $LINENO when executing: $BASH_COMMAND"' ERR

function aptInstall() {
    if ! apt install -y --no-install-recommends --no-install-suggests "$@"; then
        apt update
        apt install -y --no-install-recommends --no-install-suggests "$@"
    fi
}

aptInstall whois php php-common php-fpm php-cgi dnsmasq

lighttpd-enable-mod fastcgi-php 2>&1 | grep -F -v -e 'force-reload'

systemctl restart lighttpd
systemctl disable dnsmasq &>/dev/null
systemctl stop dnsmasq || true

echo -e "; Put session info here, to prevent SD card writes\nsession.save_path = \"/tmp\"" > /etc/php/7.3/cgi/conf.d/30-session_path.ini

ipath=/adsbexchange/webconfig

mkdir -p $ipath
cp adsb-config.txt.webtemplate webconfig.sh leds.sh sanitize-uuid.sh $ipath
cp ./webconfig.service /etc/systemd/system/
cp ./leds.service /etc/systemd/system/
rm -f /var/www/html/index.htm*
cp -r ./html/* /var/www/html
cp ./dnsmasq.conf /etc/

rm -rf $ipath/helpers
cp -r -T ./helpers $ipath/helpers
chmod a+x $ipath/helpers/*.sh
cp ./010_www-data /etc/sudoers.d/
for file in helpers/*.sh; do
    echo "www-data ALL = NOPASSWD: /adsbexchange/webconfig/$file" >> /etc/sudoers.d/010_www-data
done

rm -rf /adsbexchange/update
mkdir -p /adsbexchange
git clone --depth 1 https://github.com/ADSBexchange/adsbx-update.git /adsbexchange/update

if [[ "$1" != "dont_reset_config" ]]; then
    pushd /adsbexchange/update/
    cp -v -T boot-configs/wpa_supplicant.conf /boot/wpa_supplicant.conf.bak
    cp -v -T boot-configs/wpa_supplicant.conf /etc/wpa_supplicant/wpa_supplicant.conf
    chmod 600 /etc/wpa_supplicant/wpa_supplicant.conf
    cp -v boot-configs/* /boot
    popd

    echo -e "\n UNLOCKING UNIT UNTIL FIRST CONFIG"
    touch /boot/unlock
fi

# We do not use hostapd. Setup network is open.
systemctl disable hostapd &>/dev/null || true
systemctl enable webconfig
systemctl enable leds
systemctl restart webconfig
systemctl restart leds || true
