#!/bin/bash

if [ $(id -u) -ne 0 ]; then
  echo -e "This script must be run as root. \n"
  exit 1
fi

#These two lines allow www-data to auth using shadow file
adduser www-data shadow

apt install -y whois php7.3 php7.3-fpm php7.3-cgi
lighttpd-enable-mod fastcgi-php
systemctl restart lighttpd

echo -e "; Put session info here, to prevent SD card writes\nsession.save_path = \"/tmp\"" | tee /etc/php/7.3/cgi/conf.d/30-session_path.ini

ipath=/adsbexchange/webconfig

mkdir -p $ipath
cp -t $ipath adsb-config.txt.webtemplate install-adsbconfig.sh install-wpasupp.sh webconfig.sh reboot.sh run-update.sh run-defaults.sh bg-update.sh sanitize-uuid.sh
cp ./webconfig.service /etc/systemd/system/
cp ./010_www-data /etc/sudoers.d/
rm -f /var/www/html/index.htm*
cp -r ./html/* /var/www/html
cp ./dnsmasq.conf /etc/

rm -rf /adsbexchange/update
mkdir -p /adsbexchange
git clone --depth 1 https://github.com/ADSBexchange/adsbx-update.git /adsbexchange/update

pushd /adsbexchange/update/

cp -v -T boot-configs/wpa_supplicant.conf /boot/wpa_supplicant.conf.bak
cp -v -T boot-configs/wpa_supplicant.conf /etc/wpa_supplicant/wpa_supplicant.conf
chmod 600 /etc/wpa_supplicant/wpa_supplicant.conf
cp -v boot-configs/* /boot

popd

#echo -e "\n UNLOCKING UNIT UNTIL FIRST CONFIG"
touch /boot/unlock

# We do not use hostapd. Setup network is open.
systemctl disable hostapd
systemctl daemon-reload
systemctl enable webconfig

