#!/bin/bash

systemctl disable fr24feed
systemctl --no-block stop fr24feed
pkill -9 fr24feed

rm -f /usr/bin/fr24feed*
