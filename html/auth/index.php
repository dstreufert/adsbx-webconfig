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
	</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

            function checkPassword() {
		let newpassword1 = document.forms["changepwform"]["newpassword1"].value;
            	let newpassword2 = document.forms["changepwform"]["newpassword2"].value;

                // If password not entered
                if (newpassword1 == '')
                  //alert ("Please enter Password");
                  document.getElementById("password1-missing").removeAttribute("hidden");
                // If confirm password not entered
                else if (newpassword2 == '')
                   //alert ("Please enter confirm password");
                  document.getElementById("password2-missing").removeAttribute("hidden");
                // If Not same return False.
                else if (newpassword1 != newpassword2) {
                   //alert ("\nPasswords did not match: Please try again...")
                  document.getElementById("password-no-match").removeAttribute("hidden");
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

if (!empty($_POST["password"]) or file_exists('/boot/unlock') or file_exists('/tmp/webconfig_priv/unlock')) {

	function authenticate($user, $pass){
		/*
		system('sudo /adsbexchange/webconfig/helpers/init_auth.sh', $retval);
		if ($retval == 1) {
			error_log("auth check already in progress!");
			return false;
		}
		 */
		// this is a bit paranoid, don't rate check
		$retval = 1;
		system('echo ' . escapeshellarg($pass) . ' | /bin/su --command true - ' . escapeshellarg($user), $retval);
		//system('sudo /adsbexchange/webconfig/helpers/finish_auth.sh');
		if ($retval == 0) {
			error_log("auth check: Success.");
			return true;
		} else {
			error_log("auth check: Failure.");
			return false;
		}
	}



	//If authentication passed, refer back to URL that called for auth (if present)

	if(file_exists('/boot/unlock') or file_exists('/tmp/webconfig_priv/unlock') or authenticate('pi',$_POST["password"])){
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

<h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
<h6>ADSBX ADS-B Anywhere<br />Feeder Authorization</h6>
<a class="btn btn-primary" href="../">(..back to main menu)</a><br />
<br />
This password is synced with local user account "pi",<br />
whose default password is <a href="https://www.adsbexchange.com/sd-card-docs/">listed in the documentation.</a>
<br /><br />

<?php
//handle PW change
if (!empty($_POST["newpassword1"])) {
	if ($_SESSION['authenticated'] == 1) {
		//error_log('attempting password change: ' . 'sudo /adsbexchange/webconfig/helpers/change_passwd.sh pi "' . $_POST["oldpassword"] . '" "' . $_POST["newpassword1"] . '"');
		echo('<br>Attempting password change ....<br>');
		$output = system('sudo /adsbexchange/webconfig/helpers/change_passwd.sh pi ' . escapeshellarg($_POST["oldpassword"]) . ' ' . escapeshellarg($_POST["newpassword1"]) . ' 2>&1', $retval);
		if ($retval != 0) {
			echo('<br>Password change failed.<br>');
			return;
		}
		echo('<br>Your password has been changed. <br>');
		echo('<p><a href=".">Click here to login... </a></center></body></html>');
		system('sudo /adsbexchange/webconfig/helpers/enable_auth.sh');
		session_unset();
		exit;
	}
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
		<br /><br />Rebooting... </center></body></html>
		<?php
		system('sudo /adsbexchange/webconfig/helpers/reboot.sh > /dev/null 2>&1 &');
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
		exec('sudo /adsbexchange/webconfig/helpers/run-update.sh > /dev/null 2>&1 &');
		?>

		<br />System will reboot when complete... </center></body></html>
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
		exec('sudo /adsbexchange/webconfig/helpers/run-defaults.sh > /dev/null 2>&1 &');
		?>
		<br /><br />System will reboot when complete... </center></body></html>
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


<br /><br />
<form method='POST' name="authform" action=".">
  <div class="form-group mb-4 col-6">
	<input type="password" id="password" name="password" class="form-control form-control-lg">
	<small id="passwordHelp" class="form-text text-muted">Use the current pi password.</small>
  </div>
<input type="submit"  class="btn btn-primary" value="Authenticate">
</form>
<br /><strong>Forget your password?</strong><br />
Remove SD card, insert into computer,<br />
create a txt file or directory called "reset_password" on boot partition.<br />
Reboot and the password will reset to the defaullt password.<br />
Return to this page to set a new password.

<?php

}



//Below - things to display if user is authenticated
if ($_SESSION['authenticated'] == 1) {
	?>
	<br />
	<h3>System is unlocked</h3>
	<?php
	if(!file_exists('/boot/unlock') and !file_exists('/tmp/webconfig_priv/unlock')) {

	echo('<form method="POST" name="logout" action=".">');
	echo('<input type="hidden" id="logout" name="logout" value="logout">');
	echo('<input type="submit" class="btn btn-primary" value="Logout"></form>');
	}
	?>

	<br>If you wish to change the password, enter new password below:
	<p>
	<form method='POST' name="changepwform" action="." onSubmit = "return checkPassword()">

	 <div class="form-group mb-4 col-6">
		<div id="password1-missing" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
  			Password missing.
		</div>
		<div id="password2-missing" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
				Confirm Password missing.
		</div>
		<div id="password-no-match" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
				Passwords do not match. Try again.
		</div>
		<label for="oldpassword">Old Password</label>
		<input class="form-control form-control-lg" type="password" id="oldpassword" name="oldpassword" required>
		<label for="password1">New Password</label>
		<input class="form-control form-control-lg" type="password" id="password1" name="newpassword1" required>
		 <label for="password2">Re-enter Password</label>
		<input class="form-control form-control-lg" type="password" id="password2" name="newpassword2" required>
	</div>

	<input type="submit" class="btn btn-primary" value="Change PW">
	</form>
	<hr width="50%">
	<form method='POST' name="reboot" action="." onSubmit = "return confirm('Reboot the feeder?')">
	<input type="hidden" id="reboot" name="reboot" value="reboot">
	<input type="submit" class="btn btn-primary" value="Reboot Feeder">
	</form>
	<p>
	<hr width="50%">
	<form method='POST' name="setdefaults" action="." onSubmit = "return confirm('Reset EVERYTHING (except the password) to defaults? (wifi, location, etc.)')">
	<input type="hidden" id="setdefaults" name="setdefaults" value="setdefaults">
	<input type="submit" class="btn btn-primary" value="Reset EVERYTHING to defaults">
	</form>
	(Configuration, network settings, password, etc.)
	<p>
	<hr width="50%">
	<form method='POST' name="update" action="." onSubmit = "return confirm('Update the feeder?')">
	<input type="hidden" id="update" name="update" value="update">
	<input type="submit" class="btn btn-primary" value="Update Feeder">
	</form>
	<a href="https://raw.githubusercontent.com/ADSBexchange/adsbx-update/main/update-adsbx.sh">(executes this script)</a>
	<p>



	<?php
}
?>

 </center>

</body>
</html>
