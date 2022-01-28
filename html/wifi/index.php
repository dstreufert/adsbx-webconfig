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
	#wifiSelect {
	 width: 100%;
	}

</style>


<?php
session_start();
if ($_SESSION['authenticated'] != 1) {
	$_SESSION['auth_URI'] = $_SERVER['REQUEST_URI'];
	header("Location: ../auth");
}
?>

</head>
<body onload="selectDefaults()">

<script type="text/javascript">

function otherssidCheck() {
    if (document.getElementById('otherCheck').checked) {
        document.getElementById('ifOther').style.visibility = 'visible';
	document.getElementById('wifiSelect').selectedIndex = -1;
    }
    else document.getElementById('ifOther').style.visibility = 'hidden';
}

function selectDefaults() {
	document.getElementById('wifiSelect').selectedIndex = 0;
}

</script>

	<center>

	<h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
	<h6>ADSBX ADS-B Anywhere <br />version 8.0</h6>
        <a class="btn btn-primary" href="../">(..back to main menu)</a><br /><br />
	<form method='POST' action="./index.php" onsubmit="return confirm('Save WiFi and reboot the unit?');">

<?php

	if(isset($_POST['wifiChoose'])) {
 		$newssid = $_POST["wifiChoose"];
	} else if (isset($_POST["customSSID"])) {
 		$newssid = $_POST["customSSID"];
	}
 	$newpassword = $_POST["wifipassword"];
	$newssid = str_replace(array("\n", "\t", "\r"), '', $newssid);

 	$newcountry = $_POST["wifiChooseCountry"];
	$newcountry = str_replace(array("\n", "\t", "\r"), '', $newcountry);

 if (!empty($newssid)) {

	// Read File
    	$content=file_get_contents("/boot/wpa_supplicant.conf.bak");
    	// replace SSID
	$content_chunks=explode("YourSSID", $content);
    	$content=implode($newssid, $content_chunks);
	// replace password
	$content_chunks=explode("WifiPassword", $content);
    	$content=implode($newpassword, $content_chunks);
	// replace country
	$content_chunks=explode("UK", $content);
    	$content=implode($newcountry, $content_chunks);

	//Write File
    	file_put_contents("/tmp/webconfig/wpa_supplicant.conf", $content);
?>
        <script type="text/javascript">
                var timeleft = 70;

                var downloadTimer = setInterval(function(){

                        if(timeleft <= 0){
                                clearInterval(downloadTimer);
                                window.location.replace("../index.php");
                        }

                document.getElementById("progressBar").style.width = (70 - timeleft) + "%";

                timeleft -= 1;

                }, 1000);
        </script>


        <div class="progress">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="70" style="width: 1%"></div>
        </div>

	<p>Rebooting... Next steps:
	<table><tr><td>
	<ol>
		<li>"Forget" the ADSBx-config" wifi network on your phone/PC</li>
		<li>Join the join the network you just configured and <a href="http://adsbexchange.local/config">setup location, etc.using this link.</a> or http://adsbexchange.local<br></li>
	</ol>
	</td></tr></table>
		<p>(If you will have multiple units on your network, they will be reachable at:<br>http://adsbexchange-2.local<br>http://adsbexchange-3.local<br>etc....</body></html>

	<?php

	system('sudo /adsbexchange/webconfig/install-wpasupp.sh > /dev/null 2>&1 &');
	exit;

}

 echo "<h3>Choose WiFi Network:</h3>";
 echo "(Note that 2.4ghz networks have longer range than 5.8ghz)<br /><br />";

		$lines = file('/tmp/webconfig/wifi_scan');

		?>
		<div class="container col-8">
		<table  class="table table-striped table-hover table-dark">
		<tr><td>
        	Choose Wifi Network:<br /><br />
		    <select name="wifiChoose" class="custom-select custom-select-lg btn btn-secondary" id="wifiSelect">
			<div class="form-group">
                        <option name="SSID" value="" selected>Choose Network ...</option>
		<?php

		foreach($lines as $line) {
			echo '<option onclick="javascript:otherssidCheck();" value="'.$line.'">'.$line.'</option>';
		}
		?>
			</div>
		</select>
		</div>
		<br /><br />
		<?php
		$country_json = file_get_contents('country_codes.json');
		$country_codes = json_decode($country_json, true);
		?>
		</div>

	<input class="form-check-input" type="checkbox" name="customSSID" id="otherCheck" onclick="javascript:otherssidCheck();" />
	<label class="form-check-label">Specify Network SSID</label> <br /><br />
	<div id="ifOther" style="visibility:hidden">
		<input class="form-control form-control-lg" type="text" id="customSSID" name="customSSID" />
	</div>
	</td></tr>
	<tr><td>
        Choose Wifi Country:<br /><br />
	<select name="wifiChooseCountry" class="custom-select custom-select-lg btn btn-secondary" id="wifiSelectCountry">
		<div class="form-group">
		<?php
		foreach($country_codes as [$code, $country]) {
			if($code == 'UK'){
				echo '<option value="'.$code.'" selected>'.$code.' - '.$country.'</option>';
			} else {
				echo '<option value="'.$code.'">'.$code.' - '.$country.'</option>';
			}
		}
		?>
		</div>
	</select>
	</td></tr>
	</table>
	</div>

<div class="container col-8">
<table class="table table-striped table-hover table-dark">
	<tr>
		<td>
			WiFi Password:<br /><br />
			<input class="form-control form-control-lg" type="text" name="wifipassword"  id="wifiSelect" pattern="^[\u0020-\u007e]{8,63}$" />
		</td>
	</tr>
</table>
</div>
<input class="btn btn-primary" type="submit" value="Submit">
</form>

<br /> <br />
 Current WiFi Status:

<table>
<tr>
<td>
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
