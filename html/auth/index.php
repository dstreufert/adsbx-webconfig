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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

            function checkPassword() {
			let newpassword1 = document.forms["changepwform"]["newpassword1"].value;
            let newpassword2 = document.forms["changepwform"]["newpassword2"].value;
  
                // If password not entered
                if (newpassword1 == '')
                    alert ("Please enter Password");
                      
                // If confirm password not entered
                else if (newpassword2 == '')
                    alert ("Please enter confirm password");
                      
                // If Not same return False.    
                else if (newpassword1 != newpassword2) {
                    alert ("\nPasswords did not match: Please try again...")
                    return false;
                }
  
                // If same return True.
                else{
                    //alert("Password Match!")
                    return true;
                }
            }

</script>


<?php

session_start();

//If logout button pushed, clear session!
 if (!empty($_POST["logout"])) {
	 session_unset();
 }

//Process a password submission, or "unlock file" presence

 if (!empty($_POST["password"]) or file_exists('/boot/unlock') or file_exists('/tmp/webconfig/unlock')) {


	function authenticate($user, $pass){
	  // run shell command to output shadow file, and extract line for $user
	  // then spit the shadow line by $ or : to get component parts
	  // store in $shad as array
	  $shad =  preg_split("/[$:]/",`cat /etc/shadow | grep "^$user\:"`);
	  // use mkpasswd command to generate shadow line passing $pass and $shad[3] (salt)
	  // split the result into component parts
	  $mkps = preg_split("/[$:]/",trim(`mkpasswd -m sha-512 $pass $shad[3]`));
	  // compare the shadow file hashed password with generated hashed password and return
	   //echo $shad[4] . '<br>';
	   //echo $mkps[3] . '<br>';
	  return ($shad[4] == $mkps[3]);
	}


//If authentication passed, refer back to URL that called for auth (if present)

if(authenticate('pi',$_POST["password"]) or file_exists('/boot/unlock') or file_exists('/tmp/webconfig/unlock')){ 
  $_SESSION['authenticated'] = 1;
  if (!empty($_SESSION['auth_URI'])) {
	header("Location: .." . $_SESSION['auth_URI']); 
	unset($_SESSION['auth_URI']);
	
	}
  } 
}


?>

</head>
<body>
<center>


<h2>ADSBexchange.com<br>Feeder Unit<br>Authentication and System Tools</h2><a href="../">(..back to main menu)</a><br>
<br>
This password is synced with local user account "pi",<br>
whose default password is <a href="https://www.adsbexchange.com/sd-card-docs/">listed in the documentation.</a>
<p>

<?php
//handle PW change
 if (!empty($_POST["newpassword1"])) {
	 if ($_SESSION['authenticated'] == 1) {
		$output = system('echo "pi:' .$_POST["newpassword1"] . '" | sudo chpasswd');
		echo('<br>Your password has been changed: <br>' . $output);
		echo('<p><a href=".">Click here to login... </a></center></body></html>');
		system('sudo rm -r /boot/unlock');
		system('sudo rm -r /tmp/webconfig/unlock');
		session_unset();
		exit;
	 }
	 //echo('<br>' . $_POST["newpassword1"] . '<br>');
 }

//Handle reboot request
 if (!empty($_POST["reboot"])) {
	 if ($_SESSION['authenticated'] == 1) {
		
		?>
		<script type="text/javascript">
		var timeleft = 70;
		var downloadTimer = setInterval(function(){
		if(timeleft <= 0){
			clearInterval(downloadTimer);
			window.location.replace("../");
		}
		document.getElementById("progressBar").value = 70 - timeleft;
		timeleft -= 1;
		}, 1000);
		</script>
		<progress value="0" max="70" id="progressBar"></progress>
		
		
		
		<p>Rebooting... </center></body></html>
		<?php
		system('sudo /home/pi/adsbexchange/webconfig/reboot.sh > /dev/null 2>&1 &');
		exit;
	 }
 }


//Handle update request
 if (!empty($_POST["update"])) {
	 if ($_SESSION['authenticated'] == 1) {
		
		?>
		<!-- Output Window -->
		<table><tr><td>
		<pre>
		<div id="output_container"></div>
		</pre>
		</td></tr></table>
		<script type="text/javascript">
		function poll(){
			$("showfile.php", function(data){
				$("#output_container").load('./showfile.php');
			}); 
		}
		setInterval(function(){ poll(); }, 2000);
		</script>
		<?php
		ob_end_flush();
		flush();
		exec('sudo /home/pi/adsbexchange/webconfig/run-update.sh > /dev/null 2>&1 &');
		?>
		
		
		<p>System will reboot when complete... </center></body></html>
		<?php
		exit;
	 }
 }

//Handle setdefaults request
 if (!empty($_POST["setdefaults"])) {
	 if ($_SESSION['authenticated'] == 1) {
		
		?>
		<!-- Output Window -->
		<table><tr><td>
		<pre>
		<div id="output_container"></div>
		</pre>
		</td></tr></table>
		<script type="text/javascript">
		function poll(){
			$("showfile.php", function(data){
				$("#output_container").load('./showfile.php');
			}); 
		}
		setInterval(function(){ poll(); }, 2000);
		</script>
		<?php
		ob_end_flush();
		flush();
		exec('sudo /home/pi/adsbexchange/webconfig/run-defaults.sh > /dev/null 2>&1 &');
		?>
		
		
		<p>System will reboot when complete... </center></body></html>
		<?php
		exit;
	 }
 }




//echo('<br>Referrer: ' . $_SESSION['auth_URI'] . '<br>');

if (empty($_SESSION['authenticated'])) {
   $_SESSION['authenticated'] = 0;
}

if ($_SESSION['authenticated'] == 0) {
   echo('You are not authenticated, please enter password to login: ');


?>



<form method='POST' name="authform" action=".">
<input type="password" id="password" name="password" required>
<p>
<input type="submit" value="Submit">
</form>
<p>Forget your password?<br>
Remove SD card, insert into computer,<br>
create file or directory called "unlock"<br>
on boot partition. Reboot, and return to this page<br>
to set new password.

 
<?php

}



//Below - things to display if user is authenticated
if ($_SESSION['authenticated'] == 1) {
		
	?>
	<p>
	<h3>System is unlocked</h3>
	<?php
	if(!file_exists('/boot/unlock') and !file_exists('/tmp/webconfig/unlock')) {

	echo('<form method="POST" name="logout" action=".">');
	echo('<input type="hidden" id="logout" name="logout" value="logout">');
	echo('<input type="submit" value="Logout"></form>');
	}
	?>

	<br>If you wish to change the password, enter new password below:
	<p>
	<form method='POST' name="changepwform" action="." onSubmit = "return checkPassword()">

	<table>
	<tr><td>New Password:</td><td><input type="password" id="password1" name="newpassword1" required></td></tr>
	<tr><td>Re-enter Password:</td><td><input type="password" id="password2" name="newpassword2" required></td></tr>
	</table>
	<p>
	<input type="submit" value="Change PW">
	</form>
	<p>
	<hr width="25%">
	<form method='POST' name="reboot" action="." onSubmit = "return confirm('Reboot the feeder?')">
	<input type="hidden" id="reboot" name="reboot" value="reboot">
	<input type="submit" value="Reboot Feeder">
	</form>
	<p>
	<hr width="25%">
	<form method='POST' name="setdefaults" action="." onSubmit = "return confirm('Reset EVERYTHING to defaults? (network, password, etc.)')">
	<input type="hidden" id="setdefaults" name="setdefaults" value="setdefaults">
	<input type="submit" value="Reset EVERYTHING to defaults">
	</form>
	(Configuration, network settings, password, etc.)
	<p>
	<hr width="25%">
	<form method='POST' name="update" action="." onSubmit = "return confirm('Update the feeder?')">
	<input type="hidden" id="update" name="update" value="update">
	<input type="submit" value="Update Feeder">
	</form>
	<a href="https://raw.githubusercontent.com/ADSBexchange/adsbx-update/main/update-adsbx.sh">(executes this script)</a>
	<p>



	<?php	
}

?>

 </center>

</body>
</html>
