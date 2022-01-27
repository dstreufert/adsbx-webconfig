#!/bin/bash

if [ $(id -u) -ne 0 ]; then
  echo -e "This script must be run as root. \n"
  exit 1
fi

#These two lines allow www-data to auth using shadow file
sudo adduser www-data shadow
sudo apt-get install whois

sudo apt install php7.3 php7.3-fpm php7.3-cgi
sudo lighttpd-enable-mod fastcgi-php
sudo service lighttpd force-reload

echo -e "; Put session info here, to prevent SD card writes\nsession.save_path = \"/tmp\"" | sudo tee /etc/php/7.3/cgi/conf.d/30-session_path.ini


mkdir /home/pi/adsbexchange/webconfig
cp -t /home/pi/adsbexchange/webconfig adsb-config.txt.webtemplate install-adsbconfig.sh install-wpasupp.sh webconfig.sh reboot.sh run-update.sh run-defaults.sh bg-update.sh sanitize-uuid.sh
cp ./webconfig.service /etc/systemd/system/
cp ./010_www-data /etc/sudoers.d/
rm /var/www/html/index.htm
cp -r ./html/* /var/www/html
cp ./dnsmasq.conf /etc/
cp ./wpa_supplicant.conf.bak /boot/wpa_supplicant.conf.bak
cp ./wpa_supplicant.conf.bak /boot/wpa_supplicant.conf
cp ./wpa_supplicant.conf.bak /home/pi/adsbexchange/.adsbx/wpa_supplicant.conf
cp ./adsb-config.txt.initial /home/pi/adsbexchange/.adsbx/adsb-config.txt
cp ./adsb-config.txt.initial /boot/adsb-config.txt
cp ./adsbx-978env /home/pi/adsbexchange/.adsbx/adsbx-978env
cp ./resetdefaults.sh /home/pi/adsbexchange/
sudo /home/pi/adsbexchange/resetdefaults.sh


# We do not use hostapd. Setup network is open.
systemctl disable hostapd
systemctl daemon-reload
systemctl enable webconfig

