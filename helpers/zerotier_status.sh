#!/bin/bash

if [[ $(systemctl is-enabled zerotier-one) == "disabled" ]]; then
    echo "zerotier is disabled."
else
    zerotier-cli status
fi
