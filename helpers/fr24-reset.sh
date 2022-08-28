#!/bin/bash

systemctl disable fr24feed
systemctl --no-block stop fr24feed
rm -f /etc/fr24feed.ini
