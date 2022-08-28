#!/bin/bash

if ! command -v fr24feed-status &>/dev/null; then
    echo "fr24feed is disabled."
    exit
fi


fr24feed-status 2>&1 | grep -v MLAT
