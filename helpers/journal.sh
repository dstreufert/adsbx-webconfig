#!/bin/bash

if [[ "$1" == "-u" ]]; then
    journalctl -u "$2"
else
    journalctl
fi
