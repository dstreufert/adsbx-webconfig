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

function otherssidCheck() {
    if (document.getElementById('otherCheck').checked) {
        document.getElementById('ifOther').style.visibility = 'visible';
    }
    else document.getElementById('ifOther').style.visibility = 'hidden';
}

</script> 


<center>

<h2>ADSBexchange.com<br>
Custom Image - WiFi Setup</h2>
<br /><br />
<form method='POST' action="./wifi.php" onsubmit="return confirm('Save WiFi settings and reboot the unit?');">

 
 <?php 
 
 
 echo "<h3>Choose WiFi Network:</h3>";
 echo "(Note that 2.4ghz networks have longer range than 5.8ghz)";
 //echo "<br> New SSID:";
 $newssid = $_POST["SSID"] . $_POST["customSSID"];
 $newpassword = $_POST["wifipassword"];
 //echo $newssid;
 //echo "<br> wifipassword:";
 //echo $_POST["wifipassword"];
 if (!empty($newssid)) {

	// Read File
    $content=file_get_contents("/boot/wpa_supplicant.conf.bak");
    // replace SSID
	$content_chunks=explode("YourSSID", $content);
    $content=implode($newssid, $content_chunks);
	// replace password
	$content_chunks=explode("WifiPassword", $content);
    $content=implode($newpassword, $content_chunks);
	//Write File
    file_put_contents("/tmp/webconfig/wpa_supplicant.conf", $content);
	
	echo '<p>Rebooting... <br>Join new network, and visit <a href="http://adsbexchange.local">http://adsbexchange.local</a> to verify..</body></html>';
	//system('sudo /home/pi/adsbexchange/webconfig/install-wpasupp.sh');
	exit;
	
	
}
 
$lines = file('/tmp/wifi_scan');

echo '<table>';
foreach($lines as $line) {

			echo '<tr><td>';
			echo '<br>';
		?>
		<input type="radio" name="SSID" onclick="javascript:otherssidCheck();" value= <?php echo $line; ?>/>

		<?php
			echo $line;
			echo '<br>';
			echo '</tr></td>';
		
	}		

?> 
<tr><td>
Other Network Not Listed:
<br>
<input type="radio" name="SSID" value="" id="otherCheck" onclick="javascript:otherssidCheck();" />

<div id="ifOther" style="visibility:hidden">
Network Name:
<input type="text" name="customSSID" />
</div>

<br>
</td></tr>

</table>
<br>
<table><tr><td>
WiFi Password:
<input type="text" name="wifipassword" />
</td></td></table>


<br>
<input type="submit" value="Submit">
</form>






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



</center>
</body>
</html>
