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
Custom Image - Update</h2><a href="../index.php">(..back to main menu)</a><br><br>

Updates key software components
<p>

<?php
$updateit = $_POST["updateit"];

if (!empty($updateit)) {

	
	?>
	
	
	
	<p>Updating...
	<p>
	
	<?php


$cmd = 'sudo /home/pi/adsbexchange/update-adsbx.sh';
while (@ ob_end_flush()); // end all output buffers if any

$proc = popen($cmd, 'r');
echo '<pre>';
while (!feof($proc))
{
    echo fread($proc, 4096);
    @ flush();
}
echo '</pre>';

?>
<p>
<script type="text/javascript">
	var timeleft = 70;
	var downloadTimer = setInterval(function(){
	if(timeleft <= 0){
		clearInterval(downloadTimer);
		window.location.replace("http://adsbexchange.local");
	}
	document.getElementById("progressBar").value = 70 - timeleft;
	timeleft -= 1;
	}, 1000);
	</script>
	<progress value="0" max="70" id="progressBar"></progress>
	<p>Rebooting...
	
<?php

	echo '</body></html>';

	system('sudo /home/pi/adsbexchange/webconfig/reboot.sh > /dev/null 2>&1 &');
	exit;

}

?>

<form method='POST' action="./update.php" onsubmit="return confirm('Update unit and reboot?');">


<input type="hidden" name="updateit" value="Y"/>

<input type="submit" value="Run Update">
</form>


</center>
</body>
</html>
