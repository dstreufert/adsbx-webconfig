#!/bin/bash

sudo cp /home/pi/adsbexchange/webconfig/* ./
sudo cp /etc/sudoers.d/010_www-data ./010_www-data
sudo cp /etc/systemd/system/webconfig.service ./
sudo cp -r /var/www/html .

sudo git add .
sudo git commit
sudo git push
