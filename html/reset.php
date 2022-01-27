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
Custom Image - Reset to Defaults</h2><a href="../index.php">(..back to main menu)</a><br><br>

Resets unit to out of box defaults.<br><b>Including network configuration!</b>
<p>

<?php
$resetit = $_POST["resetit"];

if (!empty($resetit)) {

	
	?>
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
	
	
	
	<p>Rebooting... Next steps:
	<p>
	<table><tr><td>
	If the unit is running on wifi, you'll need to connect to the "ADSBx-config" network within 15 minutes of reboot and visit <a href="http://adsbexchange.local">http://adsbexchange.local</a> to reconfigure wifi.
	
	</td></tr></table>
		<p>(If you will have multiple units on your network, they will be reachable at:<br>http://adsbexchange-2.local<br>http://adsbexchange-3.local<br>etc....</body></html>
	
	<?php

	
	system('sudo /adsbexchange/update/resetdefaults.sh');
	system('sudo /adsbexchange/webconfig/reboot.sh > /dev/null 2>&1 &');
	exit;
	
	
}

?>

<form method='POST' action="./reset.php" onsubmit="return confirm('Reset to defaults and reboot the unit?');">


<input type="hidden" name="resetit" value="Y"/>

<input type="submit" value="Reset to Defaults">
</form>


</center>
</body>
</html>
