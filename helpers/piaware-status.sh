#!/bin/bash

if ! command -v piaware-status &>/dev/null; then
    echo "piaware is disabled."
    exit
fi

piaware-status
