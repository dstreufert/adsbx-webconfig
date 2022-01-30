#!/bin/bash

if [[ "$1" == "-u" ]]; then
    service="$(tr -d --complement '[:alnum:].' <<< "$2")"
    journalctl -u "$service"
else
    journalctl
fi
