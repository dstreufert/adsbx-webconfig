#!/bin/bash

# This file is for use during development, it gathers all components to prepare for git commit.

sudo cp /adsbexchange/webconfig/* ./
sudo cp /etc/sudoers.d/010_www-data ./010_www-data
sudo cp /etc/systemd/system/webconfig.service ./
sudo cp -r /var/www/html .
sudo cp /etc/dnsmasq.conf ./

sudo git add .
sudo git commit
sudo git push
