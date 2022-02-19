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

<?php 
session_start();
if ($_SESSION['authenticated'] != 1) {
	$_SESSION['auth_URI'] = $_SERVER['REQUEST_URI'];
	header("Location: ../auth"); 
}
?>

</head>
<body>
<center>

			<h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
			<h6>ADSBX ADS-B Anywhere <br />version 8.0</h6>
			<a class="btn btn-primary" href="../">(..back to main menu)</a><br /><br />

Note: SDR sticks are often identified by their "serial numbers".<br />
These serial numbers can be changed using the "rtl_eeprom" command.<br />
This is mainly helpful when dealing with a setup containing more than one SDR.<br /><br />
This script edits the<br>/boot/adsbx-env and /boot/adsbx-978env files.<br /><br />


 <?php 
 
function sanitize($string) {
    return preg_replace('/[^A-Za-z0-9_.\-]/', '', $string);
}

 if (!empty($_POST["readsb_sdr"])) {

	echo 'Setting the following values:<br>';

	foreach ( $_POST as $key => $value ) {
		echo $key . ': ' . $value;
		echo '<br>';
	}
	 
 
	$readsb_sdr = sanitize($_POST["readsb_sdr"]);
 
	if ($readsb_sdr == 'unspecified') {
		system('sudo /adsbexchange/webconfig/helpers/set_receiver_options.sh /boot/adsbx-env \'--device-type rtlsdr --ppm 0\'');
	} else {
		system('sudo /adsbexchange/webconfig/helpers/set_receiver_options.sh /boot/adsbx-env \'--device ' . $readsb_sdr . ' --device-type rtlsdr --ppm 0\'');
	}
	
	$dump978_sdr = sanitize($_POST["dump978_sdr"]);
	$dump978_gain = sanitize($_POST["dump978_gain"]);

	if ($dump978_sdr == 'unspecified') {
		system('sudo /adsbexchange/webconfig/helpers/set_receiver_options.sh /boot/adsbx-978env \'--sdr-gain ' . $dump978_gain . ' --sdr driver=rtlsdr --format CS8\'');
	} else if ($dump978_sdr == 'stratuxv3') {
		system('sudo /adsbexchange/webconfig/helpers/set_receiver_options.sh /boot/adsbx-978env \'--stratuxv3 /dev/uatradio\'');
	} else {
		system('sudo /adsbexchange/webconfig/helpers/set_receiver_options.sh /boot/adsbx-978env \'--sdr-gain ' . $dump978_gain . ' --sdr driver=rtlsdr,serial=' . $dump978_sdr . ' --format CS8\'');
	}
	

	?>
	<script type="text/javascript">
	var timeleft = 15;
	var downloadTimer = setInterval(function(){
	if(timeleft <= 0){
		clearInterval(downloadTimer);
		window.location.replace("../index.php");
	}
	document.getElementById("progressBar").value = 15 - timeleft;
	timeleft -= 1;
	}, 1000);
	</script>
	<progress value="0" max="15" id="progressBar"></progress>
	
	<?php
	echo '<p>Restarting services... visit <a href="../index.php">this link</a> to verify changes in about 15 secs..</form></body></html>';

	system('sudo /adsbexchange/webconfig/helpers/restart-services.sh > /dev/null 2>&1 &');
	exit;
}


//grab readsb env file

$line = exec('cat /boot/adsbx-env | grep ^"RECEIVER_OPTIONS="');

$readsb_selection = strtok(trim(explode("--device ", $line)[1]), ' ');
$readsb_selection = strtok($readsb_selection, '\"');


$pos = strpos($line, "--device ");
if ($pos === false) $readsb_selection = 'unspecified';

//echo '<br>readsb_selection is: ' . $readsb_selection . "<br>";



//grab 978 env file	



$line = exec('cat /boot/adsbx-978env | grep ^"RECEIVER_OPTIONS="');

$dump978_selection = strtok(trim(explode(",serial=", $line)[1]), ' ');
$dump978_selection = strtok($dump978_selection, '\"');


$pos = strpos($line, ",serial=");
if (strpos($line, "--stratuxv3")) $dump978_selection = 'stratuxv3';
else if ($pos === false) $dump978_selection = 'unspecified';

if (! strpos($line, "--sdr-gain")) {
	$dump978_gain = "43.9";
} else {
	$dump978_gain = strtok(trim(explode("--sdr-gain ", $line)[1]), ' ');
	$dump978_gain = strtok($dump978_gain, '\"');
}

//echo '<br>dump978_selection is: ' . $dump978_selection . "<br>";
//echo '<br>dump978_gain is: ' . $dump978_gain . "<br>";



?> 
<form method='POST' name="sdrform" action="./index.php" onsubmit="return confirm(Save configuration and restart services?');">

<br>

<table><tr><td>
Choose an SDR serial number for<br>readsb service (1090Mhz): <p>

<?php
$lines = file('/tmp/webconfig/sdr_serials');
array_push($lines, 'unspecified');

echo '<select id="readsb_sdr" name="readsb_sdr">';
foreach($lines as $line) {
	$line = trim($line);
	?>
	<option value="<?php echo $line;?>" <?php if ($line == $readsb_selection) echo 'selected'; ?>><?php echo $line;?></option>
	<?php
}
echo '</select>';
?>



</tr></td><tr><td>
Choose an SDR serial number for<br>dump978 service (978Mhz): <p>

<?php
$lines = file('/tmp/webconfig/sdr_serials');
array_push($lines, 'unspecified');
array_push($lines, 'stratuxv3');

echo '<select id="dump978_sdr" name="dump978_sdr">';
foreach($lines as $line) {
	$line = trim($line);
	?>
	<option value="<?php echo $line;?>" <?php if ($line == $dump978_selection) echo 'selected'; ?>><?php echo $line;?></option>
	<?php
}
echo '</select>';
?>


</tr></td><tr><td>
Choose gain for<br>dump978 service (978Mhz)<br>Default is 36.4: <p>
<select id="dump978_gain" name="dump978_gain">
<?php
$gainoptions = array(-10, 0.0, 0.9, 1.4, 2.7, 3.7, 7.7, 8.7, 12.5, 14.4, 15.7, 16.6, 19.7, 20.7, 22.9, 25.4, 28.0, 29.7, 32.8, 33.8, 36.4, 37.2, 38.6, 40.2, 42.1, 43.4, 43.9, 44.5, 48.0, 49.6);

foreach ($gainoptions as $gainval) {
	?><option value="<?php echo $gainval; ?>" <?php if ($gainval == $dump978_gain) { echo 'selected'; } ?>><?php echo $gainval ; ?></option>
	<?php
	}
?>
</select>


</table>
<p>
<input class="btn btn-danger" type="submit" value="Save & restart services">
</form>
 
 </center>
</body>
</html>
