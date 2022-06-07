#!/bin/bash

timedatectl set-timezone "$1"

systemctl restart graphs1090
