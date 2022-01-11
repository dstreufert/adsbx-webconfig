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
Custom Image - WiFi Setup</h2><a href="../index.php">(..back to main menu)</a><br />
<form method='POST' action="./wifi.php" onsubmit="return confirm('Save WiFi settings and reboot the unit?');">

 
 <?php 
 
 

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
	
	?>
	<script type="text/javascript">
	var timeleft = 70;
	var downloadTimer = setInterval(function(){
	if(timeleft <= 0){
		clearInterval(downloadTimer);
		window.location.replace("http://adsbexchange.local/config.php");
	}
	document.getElementById("progressBar").value = 70 - timeleft;
	timeleft -= 1;
	}, 1000);
	</script>
	<progress value="0" max="60" id="progressBar"></progress>
	
	
	
	<p>Rebooting... Next steps:
	<table><tr><td>
	<ol>
		<li>"Forget" the ADSBx-config" wifi network on your phone/PC</li>
		<li>Join the join the network you just configured and <a href="http://adsbexchange.local/config.php">setup location, etc.using this link.</a> or http://adsbexchange.local<br></li>
	</ol>
	</td></tr></table>
		<p>(If you will have multiple units on your network, they will be reachable at:<br>http://adsbexchange-2.local<br>http://adsbexchange-3.local<br>etc....</body></html>
	
	<?php
	// Attempt to push final echo to browser before reboot.
	//sleep(2);
	//ob_flush();
	//flush();
	
	system('sudo /home/pi/adsbexchange/webconfig/install-wpasupp.sh > /dev/null 2>&1 &');
	exit;
	
	
}
 
 echo "<h3>Choose WiFi Network:</h3>";
 echo "(Note that 2.4ghz networks have longer range than 5.8ghz)";
 
$lines = file('/tmp/webconfig/wifi_scan');

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
