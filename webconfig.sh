#!/bin/bash
mkdir /tmp/webconfig
sleep 15 # Give stuff a chance to come up
netnum=$(wpa_cli list_networks | grep ADSBx-config | cut -f 1)
sleep 5
sudo iw wlan0 scan | grep SSID: | sort | uniq | cut -d ' ' -f2 | grep . > /tmp/webconfig/wifi_scan
timeout 3 wget https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=$LATITUDE\&longitude=$LONGITUDE\&localityLanguage=en -q -T 3 -O /tmp/webconfig/geocode
cat /tmp/webconfig/geocode | jq -r .'locality' > /tmp/webconfig/location
cat /tmp/webconfig/geocode | jq -r .'principalSubdivisionCode' >> /tmp/webconfig/location
cat /tmp/webconfig/geocode | jq -r .'countryName' >> /tmp/webconfig/location
echo $USER > /tmp/webconfig/name
chmod 777 /tmp/webconfig/*
chmod 777 /tmp/webconfig

ping 1.1.1.1 -I wlan0 -w 10 > /dev/null
if [ $? -eq 0 ];
then
  echo "1.1.1.1 pingable, exiting"
  exit 0
else
  echo "1.1.1.1 unreachable"
fi

ping 8.8.8.8 -I wlan0 -w 10 > /dev/null
if [ $? -eq 0 ];
then
  echo "8.8.8.8 pingable, exiting"
  exit 0
else
  echo "8.8.8.8 unreachable"
fi


echo "ip connectivity failed, enabling ADSBx-config network"

wpa_cli enable_network $netnum
sudo dnsmasq
totalwait=0

until [ $totalwait -gt 900 ]
do
	ssid=$(wpa_cli status | grep ssid | grep -v bssid | cut -d "=" -f 2)
        if [ "$ssid" = "ADSBx-config" ]; then
		sudo ip address replace 172.23.45.1/24 dev wlan0; echo "setting wlan0 ip to 172.23.45.1/24"
		clientip=$(cat /var/lib/misc/dnsmasq.leases | head -n 1 |  cut -d " " -f3)
		#clientip=$(arp -n | grep wlan0 | cut -d " " -f1 )
                if [[ ! -z "$clientip" ]]; then
					echo "Client lease detected at $clientip"
                    #ip address show wlan0 | grep 169.254.10.90 > /dev/null
					#ipset=$?
					#if [ $ipset -ne 0 ]; then sudo ip address replace 169.254.10.90/16 dev wlan0; echo "setting wlan0 ip to 169.254.10.90/16"; fi
					
				fi
        fi

	((totalwait++))
        sleep 1
done

if [ "$ssid" = "ADSBx-config" ]; then
        sudo ping $clientip -I wlan0 -f -w 1; hostup=$?
                if [ $hostup -eq 0 ]; then
			echo "timeout tripped but client connected, disabling ADSBx-config in 900 sec"
                        sleep 900
			wpa_cli disable $netnum
                fi
fi

sudo kill $(cat /var/run/dnsmasq.pid)
sleep 1
killall dnsmasq #Make sure dnsmasq is off
wpa_cli disable $netnum

exit 0;


