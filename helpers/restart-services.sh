#!/bin/bash

restartIfEnabled() {
    # check if enabled
    if systemctl is-enabled "$1" &>/dev/null; then
            systemctl restart "$1"
    fi
}

systemctl restart webconfig

adsbx-first-run

services="readsb dump978-fa adsbexchange-978 adsbexchange-feed adsbexchange-mlat webconfig leds"
for service in $services; do
    restartIfEnabled $service
done

systemctl start adsbx-zt-enable
