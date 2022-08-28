#!/bin/bash

timedatectl set-timezone "$1"

# fix timedatectl doing stupid stuff (adding .. in front of /usr ???)
dpkg-reconfigure --frontend noninteractive tzdata

systemctl restart graphs1090
