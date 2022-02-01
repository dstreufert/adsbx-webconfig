#!/bin/bash

# this is a privileged folder ... only root can access
mkdir -p /tmp/webconfig_priv
chmod -R go-rwx /tmp/webconfig_priv


mkdir -p /tmp/webconfig
echo $USER > /tmp/webconfig/name

if ! echo "$LATITUDE $LONGITUDE" | grep -E -qs -e '[1-9]+'; then
    echo "Location not set." > /tmp/webconfig/location
    location_not_set="1"
fi

chmod -R a+rwX /tmp/webconfig

# reset password when reset_password file is set
if [[ -e /boot/reset_password ]] || [[ -e /boot/reset_password.txt ]]; then
    echo "pi:adsb123" | chpasswd
fi

# Runs a script that may be manually placed on /boot for batch setup.  By default, nothing there.
if [[ -f /boot/firstboot.sh ]]; then
    bash /boot/firstboot.sh
fi

lsusb -d 0bda: -v 2> /dev/null | grep iSerial |  tr -s ' ' | cut -d " " -f 4 > /tmp/webconfig/sdr_serials
sleep 15 # Give stuff a chance to come up
netnum=$(wpa_cli list_networks | grep ADSBx-config | cut -f 1)
sleep 5
iw wlan0 scan | grep SSID: | sort | uniq | cut -c 8- | grep '\S' | grep -v '\x00' > /tmp/webconfig/wifi_scan
if [ $? -ne 0 ]
then
    sleep 3
    iw wlan0 scan | grep SSID: | sort | uniq | cut -c 8- | grep '\S' | grep -v '\x00' > /tmp/webconfig/wifi_scan
fi

if [[ -z $location_not_set ]] ; then
    timeout 3 wget https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=$LATITUDE\&longitude=$LONGITUDE\&localityLanguage=en -q -T 3 -O /tmp/webconfig/geocode
    cat /tmp/webconfig/geocode | jq -r .'locality' > /tmp/webconfig/location
    cat /tmp/webconfig/geocode | jq -r .'principalSubdivisionCode' >> /tmp/webconfig/location
    cat /tmp/webconfig/geocode | jq -r .'countryName' >> /tmp/webconfig/location
    chmod -R a+rwX /tmp/webconfig
fi

ping 1.1.1.1 -w 10 > /dev/null
if [ $? -eq 0 ];
then
    echo "1.1.1.1 pingable, exiting"
    exit 0
else
    echo "1.1.1.1 unreachable"
fi

ping 8.8.8.8 -w 10 > /dev/null
if [ $? -eq 0 ];
then
    echo "8.8.8.8 pingable, exiting"
    exit 0
else
    echo "8.8.8.8 unreachable"
fi


echo "ip connectivity failed, enabling ADSBx-config network"

wpa_cli enable_network $netnum
dnsmasq
totalwait=0
touch /tmp/webconfig_priv/unlock

fatal="no"

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

    if (( $totalwait > 15 )) && [[ "$ssid" != "ADSBx-config" ]]; then
        fatal="yes"
        break;
    fi

    ((totalwait++))
    sleep 1
done

if [[ "$ssid" == "ADSBx-config" ]] && [[ "$fatal" != "yes" ]]; then
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
rm -rf /tmp/webconfig_priv/unlock
wpa_cli disable $netnum

exit 0;


