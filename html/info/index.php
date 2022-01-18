<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>  
table {  
  font-family: arial, sans-serif;  
  border-collapse: collapse;  
  <!-- width: 50%; -->
}  
  
td, th {  
  border: 2px solid #111111;  
  text-align: left;  
  padding: 8px;  
}  
tr:nth-child(even) {  
  background-color: #D5D8DC;  
}  
</style>

</head>
<body>

<script type="text/javascript">
</script> 

<center>

<h2>ADSBexchange.com<br>
Custom Image - System Info</h2><a href="../index.php">(..back to main menu)</a><br><br>

<table><tr><td><center>Feeder Name</center></td><td><center>Public IP</center></td><tr>

<tr><td>
<?php
$output = shell_exec('cat /tmp/webconfig/name');
echo "<pre>$output</pre>";
?>
</td>
<td>
<?php
$output = shell_exec('timeout 2 curl http://ipecho.net/plain');
echo "<pre>$output</pre>";
?>
</td>

</tr>
</table>


 <br>
 Local IP:

<table><tr><td>
<?php
$output = $_SERVER['SERVER_ADDR'];
echo "<pre>$output</pre>";
?>
</td></tr>
</table>




 <br>
 Configured Location:

<table><tr><td>
<?php
$output = shell_exec('cat /tmp/webconfig/location');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 Zerotier Status:

<table><tr><td>
<?php
$output = shell_exec('sudo zerotier-cli status | head -n 1');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

<br>
 Uptime:

<table><tr><td>
<?php
$currenttime = shell_exec('date');
$uptime = shell_exec('uptime -p');
$upsince = shell_exec('uptime -s');
echo "<center><pre>Current Time: $currenttime";
echo "Uptime: $uptime";
echo "Since $upsince</pre></center>";
?>
</td></tr>
</table>

 <br>
 Voltage Drops:

<table><tr><td>
<?php
$output = shell_exec('/home/pi/adsbexchange/throttle.sh');
echo "<pre>$output</pre>";
echo "</td></tr><tr><td>";
$temp = shell_exec('sudo vcgencmd measure_temp');
echo "<center><pre>CPU $temp";
echo "Redline=80'C</pre></center>";
?>
</td></tr>
</table>



 

 <br>
 Messages since boot:

<table><tr><td>
<?php
$messages1090 = number_format(shell_exec('cat /run/adsbexchange-feed/aircraft.json | jq .messages'));
$messages978 = number_format(shell_exec('cat /run/adsbexchange-978/aircraft.json | jq .messages'));
$totaltracks = number_format(shell_exec('cat /run/adsbexchange-feed/stats.json | jq .total.tracks.all'));
echo "<pre>1090mhz: $messages1090<br>978mhz: $messages978<br>Tracks: $totaltracks</pre>";
?>
</td></tr>
</table>


 <br>
 Installed SDR serials<br>(vendor code 0bda:*)

<table><tr><td>
<?php
$sdrserials = shell_exec('cat /tmp/webconfig/sdr_serials');
echo "<pre>$sdrserials</pre>";
?>
</td></tr>
</table>


 <br>
 Position Count By Type: <a href="https://www.adsbexchange.com/version-2-api-wip/" target="_blank">(info)</a>

<table><tr><td><center>Last 1 Minute</center></td><td><center>Last 5 Minutes</center></td><td><center>Last 15 Minutes</center></td>
<td><center>Since Boot</center></td></tr>

<?php
$pos1min = shell_exec('cat /run/adsbexchange-feed/stats.json | jq .last1min.position_count_by_type');
$pos5min = shell_exec('cat /run/adsbexchange-feed/stats.json | jq .last5min.position_count_by_type');
$pos15min = shell_exec('cat /run/adsbexchange-feed/stats.json | jq .last15min.position_count_by_type');
$postotal = shell_exec('cat /run/adsbexchange-feed/stats.json | jq .total.position_count_by_type');
?>

<tr>

<td>
<?php
echo "<pre>$pos1min</pre>";
?>
</td>

<td>
<?php
echo "<pre>$pos5min</pre>";
?>
</td>

<td>
<?php
echo "<pre>$pos15min</pre>";
?>
</td>

<td>
<?php
echo "<pre>$postotal</pre>";
?>
</td>

</tr>

</table>







 <br>
 Current WiFi Status:

<table><tr><td>
<?php
$output = shell_exec('iwconfig wlan0');
echo "<pre>$output</pre>";
?>
</td></tr></table>

 <br>
 Current IP Status:

<table><tr><td>
<?php
$output = shell_exec('ifconfig');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 IP Route:

<table><tr><td>
<?php
$output = shell_exec('ip route');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>


 <br>
 readsb.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u readsb.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 dump978-fa.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u dump978-fa.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>


 <br>
 autogain1090.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u autogain1090.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 tar1090.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u tar1090.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 tar1090-978.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u tar1090-978.service | /home/pi/adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 adsbexchange-feed.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u adsbexchange-feed.service | /home/pi/adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 adsbexchange-mlat.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u adsbexchange-mlat.service | /home/pi/adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 adsbexchange-stats.service logs:

<table><tr><td>
<?php
$output = shell_exec('sudo journalctl -u adsbexchange-stats.service | /home/pi/adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 /boot/adsbx-env

<table><tr><td>
<?php
$output = shell_exec('cat /boot/adsbx-env');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 /boot/adsbx-978env

<table><tr><td>
<?php
$output = shell_exec('cat /boot/adsbx-978env');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>



</center>
</body>
</html>
