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


<h2>ADSBexchange.com<br>Custom Image - SDR Assignment</h2><a href="../index.php">(..back to main menu)</a><br>
<br>
Note: SDR sticks are often identified by their "serial numbers".<br>
These serial numbers can be changed<br>using the "rtl_eeprom" command.<br>
This is mainly helpful when dealing
with<br>a setup containing more than one SDR.<p>
This script edits the<br>/boot/adsbx-env and /boot/adsbx-978env files.<p>


 <?php 
 

 if (!empty($_POST["readsb_sdr"])) {

	echo 'Setting the following values:<br>';

	foreach ( $_POST as $key => $value ) {
		echo $key . ': ' . $value;
		echo '<br>';
	}
	 
 
	$readsb_sdr = $_POST["readsb_sdr"];
 
	if ($readsb_sdr == 'unspecified') {
		system('sudo sed -i "s/^RECEIVER_OPTIONS=.*$/RECEIVER_OPTIONS=\"--device-type rtlsdr --ppm 0\"/g" /boot/adsbx-env');
	} else {
		system('sudo sed -i "s/^RECEIVER_OPTIONS=.*$/RECEIVER_OPTIONS=\"--device ' . $readsb_sdr . ' --device-type rtlsdr --ppm 0\"/g" /boot/adsbx-env');
	}
	
	$dump978_sdr = $_POST["dump978_sdr"];
	$dump978_gain = $_POST["dump978_gain"];
 
	system('sudo sed -i "s/^RECEIVER_OPTIONS=.*$/RECEIVER_OPTIONS=\"--sdr-gain ' . $dump978_gain . ' --sdr driver=rtlsdr,serial=' . $dump978_sdr . ' --format CS8\"/g" /boot/adsbx-978env');
	

	?>
	<script type="text/javascript">
	var timeleft = 70;
	var downloadTimer = setInterval(function(){
	if(timeleft <= 0){
		clearInterval(downloadTimer);
		window.location.replace("../index.php");
	}
	document.getElementById("progressBar").value = 70 - timeleft;
	timeleft -= 1;
	}, 1000);
	</script>
	<progress value="0" max="70" id="progressBar"></progress>
	
	<?php
	echo '<p>Rebooting... visit <a href="../index.php">this link</a> to verify changes in about 70 secs..</form></body></html>';

	system('sudo /home/pi/adsbexchange/webconfig/reboot.sh > /dev/null 2>&1 &');
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
if ($pos === false) $dump978_selection = 'unspecified';

$dump978_gain = strtok(trim(explode("--sdr-gain ", $line)[1]), ' ');
$dump978_gain = strtok($dump978_gain, '\"');

//echo '<br>dump978_selection is: ' . $dump978_selection . "<br>";
//echo '<br>dump978_gain is: ' . $dump978_gain . "<br>";



?> 
<form method='POST' name="sdrform" action="./index.php" onsubmit="return confirm(Save configuration and reboot?');">

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
<?php
echo '<select id="dump978_gain" name="dump978_gain">';

$gainoptions = array(-10, 0.0, 0.9, 1.4, 2.7, 3.7, 7.7, 8.7, 12.5, 14.4, 15.7, 16.6, 19.7, 20.7, 22.9, 25.4, 28.0, 29.7, 32.8, 33.8, 36.4, 37.2, 38.6, 40.2, 42.1, 43.4, 43.9, 44.5, 48.0, 49.6);

foreach ($gainoptions as $gainval) {
	?><option value="<?php echo $gainval; ?>" <?php if ($gainval == $dump978_gain) { echo 'selected'; } ?>><?php echo $gainval ; ?></option>
	<?php
	}
echo '</select>';
?>

</table>
<p>
<input type="submit" value="Submit">
</form>
 

 
 </center>

</body>
</html>
