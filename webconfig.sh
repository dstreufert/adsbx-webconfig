#!/bin/bash

# this is a privileged folder ... only root can access
mkdir -p /tmp/webconfig_priv
chmod -R go-rwx /tmp/webconfig_priv


mkdir -p /tmp/webconfig
echo $USER > /tmp/webconfig/name

if ! echo "$LATITUDE $LONGITUDE" | grep -E -qs -e '[1-9]+'; then
    echo "Location not set." > /tmp/webconfig/location
    location_set=0
else
    printf "%.1f, %.1f\n" $LATITUDE $LONGITUDE > /tmp/webconfig/location
    location_set=1
fi

chmod -R a+rwX /tmp/webconfig

function services-handle {
    for SERVICE in $2; do
        echo "$1 $SERVICE"
        if [[ $1 == disable ]]; then
            if systemctl is-enabled $SERVICE &>/dev/null; then
                systemctl disable --now $SERVICE
            fi
            if systemctl is-active $SERVICE &>/dev/null; then
                systemctl stop $SERVICE
            fi
        fi
        if [[ $1 == enable ]]; then
            if ! systemctl is-enabled $SERVICE &>/dev/null; then
                systemctl enable --now $SERVICE
            fi
            if ! systemctl is-active $SERVICE &>/dev/null; then
                systemctl start $SERVICE
            fi
        fi
    done
}

if [[ $CUSTOMLEDS == "yes" ]];
then
    services-handle enable leds
    systemctl restart leds
else
    services-handle disable leds
fi

# reset password when reset_password file is set
if ls /boot | grep -qs '^reset_password'; then
    echo "Resetting user pi to default password!"
    echo "pi:adsb123" | chpasswd
    rm -rf /boot/reset_password*
fi

lsusb -d 0bda: -v 2> /dev/null | grep iSerial |  tr -s ' ' | cut -d " " -f 4 > /tmp/webconfig/sdr_serials

internet=0
connected=0

# wait until we have internet connectivity OR a maximum of 30 seconds
for i in {1..15}; do
    sleep 2 &
    if ping -c 1 -w 1 8.8.8.8 &>/dev/null || ping -c 1 -w 1 1.1.1.1 &>/dev/null; then
        echo we have internet!
        internet=1
        break;
    fi
    wait
done

if wpa_cli status 2>&1 | grep -qs 'wpa_state=COMPLETED'; then
    echo we have wifi!
    connected=1
fi

if [[ $internet == 1 ]] || [[ $connected == 1 ]]; then
    echo > /dev/tty1
    echo ------------- > /dev/tty1
    echo "Use the webinterface at http://adsbexchange.local OR http://$(ip route get 1.2.3.4 | grep -m1 -o -P 'src \K[0-9,.]*')" > /dev/tty1
    echo ------------- > /dev/tty1
fi

if [[ $location_set == 1 ]] && [[ $internet == 1 ]]; then
        timeout 3 wget https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=$LATITUDE\&longitude=$LONGITUDE\&localityLanguage=en -q -T 3 -O /tmp/webconfig/geocode
        cat /tmp/webconfig/geocode | jq -r .'locality' > /tmp/webconfig/location
        cat /tmp/webconfig/geocode | jq -r .'principalSubdivisionCode' >> /tmp/webconfig/location
        cat /tmp/webconfig/geocode | jq -r .'countryName' >> /tmp/webconfig/location
fi
chmod -R a+rwX /tmp/webconfig

function wifi_scan() {
    for i in {1..30}; do
        # let's retry at changing intervals
        sleep "0.$(( i * 3 ))" &
        if iw wlan0 scan > /tmp/webconfig/raw_scan; then
            break;
        fi
        wait
    done

    cat /tmp/webconfig/raw_scan | grep SSID: | sort | uniq | cut -c 8- | grep '\S' | grep -v '\x00' > /tmp/webconfig/wifi_scan
    cat /tmp/webconfig/raw_scan | grep -e SSID: -e 'BSS .*(on' -e freq: | sed -z -e 's/\n\t/\t/g' | sed -e 's/\((on.*\)\(freq:\)/\t\2/' | tr '\t' '^' | column -t -s '^' > /tmp/webconfig/wifi_bssids
}

if [[ $internet == 1 ]]; then
    wifi_scan
    echo "1.1.1.1 or 8.8.8.8 pingable, exiting"

    wait
    exit 0
fi
if [[ $connected == 1 ]]; then
    wifi_scan
    echo "connected to WiFi, exiting"

    wait
    exit 0
fi


echo "ip connectivity failed, enabling ADSBx-config network"

echo > /dev/tty1
echo ------------- > /dev/tty1
echo "Internet can't be reached with current WiFi settings, enabling ADSBx-config WiFi Network!" > /dev/tty1
echo "Use your smartphone / laptop to connect to the WiFi network called: ADSBx-config" > /dev/tty1
echo "On that device visit the URL http://adsbexchange.local in your browser" > /dev/tty1
echo "Select a WiFi network / country / password for the Raspberry Pi to join" > /dev/tty1
echo ------------- > /dev/tty1

netnum=$(wpa_cli list_networks | grep ADSBx-config | cut -f 1)
wpa_cli select_network $netnum
wpa_cli enable_network $netnum

# do the wifi can after selecting / enabling the config network as it can be unreliable otherwise
wifi_scan

dnsmasq
totalwait=0

until [ $totalwait -gt 900 ]
do
    ssid=$(wpa_cli status | grep ssid | grep -v bssid | cut -d "=" -f 2)
    if [ "$ssid" = "ADSBx-config" ]; then
        ipset=$(ip address show dev wlan0 | grep "172.23.45.1")

        if [ -z "$ipset" ]; then
            ip address replace 172.23.45.1/24 dev wlan0; echo "setting wlan0 ip to 172.23.45.1/24"
        fi
        clientip=$(cat /tmp/webconfig/dnsmasq.leases | head -n 1 |  cut -d " " -f3)

        if [[ ! -z "$clientip" ]]; then
            echo "Client lease detected at $clientip"
        fi
    fi

    if (( $totalwait > 30 )) && [[ "$ssid" != "ADSBx-config" ]]; then
        # if for some reason we can't enable the config network, bail.
        break;
    fi

    ((totalwait++))
    sleep 1
done

if [[ "$ssid" == "ADSBx-config" ]]; then
    ping $clientip -I wlan0 -f -w 1; hostup=$?
    if [ $hostup -eq 0 ]; then
        echo "timeout tripped but client connected, disabling ADSBx-config in 900 sec"
        sleep 900
        wpa_cli disable $netnum
    fi
fi

kill $(cat /var/run/dnsmasq.pid)
sleep 1
killall dnsmasq #Make sure dnsmasq is off
sleep 2
pkill -9 dnsmasq # Make extra sure dnsmasq is off
ip address del 172.23.45.1/32 dev wlan0
wpa_cli disable $netnum


wait
exit 0
