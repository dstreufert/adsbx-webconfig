#!/bin/bash

if [[ "$1" == "-u" ]]; then
    journalctl -u "$2" -n 100
else
    journalctl -n 200
fi
