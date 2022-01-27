<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<script src="../js/bootstrap.min.js"></script>
<style>
	.btn-margin-bottom {
		margin-bottom: 5px !important;
	}
	
	body {
		background-color: #343434;
		color: #FFF;
	}
	
	.adsbx-green {
		color: #FFF;
	}
	
	.container-margin {
		padding: 5px 10px !important;
	}
	
	.logo-margin {
		padding: 10px 0px !important;
	}
	
	.btn-primary {
		/*width: 325px;*/
		padding: 10px;
		text-align: left;
		color: #fff;
		border-color: #545454;
		background-color: #828282;
	}
	
	.alert-success {
		color: #686868;
		font-weight: 900;
		background-color: #29d682;
		border-color: #828282;
	}
	
	.min-adsb-width {
		/*width: 325px;*/
	}

	.container-padding {
		padding: 5px;
	}
</style>

<script type="text/javascript">
</script>

</head>

<body>

<div class="container-sm container-padding">

<center>

<h2>ADSBexchange.com<br />
Custom Image - System Info</h2><a href="../">(..back to main menu)</a><br /><br />

<table class="table table-dark">

<tr>
	<td><center>Feeder Name</center></td>
	<td><center>Public IP</center></td>
</tr>

<tr>

<td>
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

<table class="table table-dark"><tr><td>
<?php
$output = $_SERVER['SERVER_ADDR'];
echo "<pre>$output</pre>";
?>
</td></tr>
</table>




 <br>
 Configured Location:

<table class="table table-dark"><tr><td>
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

<table class="table table-dark"><tr><td>
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

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('/adsbexchange/update/throttle.sh');
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

<table class="table table-dark"><tr><td>
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

<table class="table table-dark"><tr><td>
<?php
$sdrserials = shell_exec('cat /tmp/webconfig/sdr_serials');
echo "<pre>$sdrserials</pre>";
?>
</td></tr>
</table>


 <br>
 Position Count By Type: <a href="https://www.adsbexchange.com/version-2-api-wip/" target="_blank">(info)</a>

<table class="table table-dark">

<tr>
<td><center>Last 1 Minute</center></td>
<td><center>Last 5 Minutes</center></td>
<td><center>Last 15 Minutes</center></td>
<td><center>Since Boot</center></td>
</tr>

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

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('iwconfig wlan0');
echo "<pre>$output</pre>";
?>
</td></tr></table>

 <br>
 Current IP Status:

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('ifconfig');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 IP Route:

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('ip route');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 readsb.service logs:
<div class="table-responsive-lg">
<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u readsb.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>
</div>

 <br>

 dump978-fa.service logs:
<div class="table-responsive-lg">
<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u dump978-fa.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>
</div>

 <br>

 autogain1090.service logs:
<div class="table-responsive-lg">
<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u autogain1090.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>
</div>

 <br>

 tar1090.service logs:
<div class="table-responsive-lg">
<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u tar1090.service');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>
</div>
 <br>
 tar1090-978.service logs:

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u tar1090-978.service | /adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 adsbexchange-feed.service logs:

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u adsbexchange-feed.service | /adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 adsbexchange-mlat.service logs:

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u adsbexchange-mlat.service | /adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 adsbexchange-stats.service logs:

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('sudo journalctl -u adsbexchange-stats.service | /adsbexchange/webconfig/sanitize-uuid.sh');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 /boot/adsbx-env

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('cat /boot/adsbx-env');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>

 <br>
 /boot/adsbx-978env

<table class="table table-dark"><tr><td>
<?php
$output = shell_exec('cat /boot/adsbx-978env');
echo "<pre>$output</pre>";
?>
</td></tr>
</table>


</center>
</div>
</body>

</html>
