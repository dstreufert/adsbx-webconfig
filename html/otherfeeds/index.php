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
	.adsbx-width {
		max-width: 700;
	}

</style>


<?php
session_start();
if ($_SESSION['authenticated'] != 1) {
    $_SESSION['auth_URI'] = $_SERVER['REQUEST_URI'];
    header("Location: ../auth/");
}

$newTimezone ='';

if(isset($_POST['faSelect'])) {
    if ($_POST['faSelect'] == "Enable") {
        $output = shell_exec('sudo /adsbexchange/webconfig/helpers/piaware-enable.sh');
    }
    if ($_POST['faSelect'] == "Disable") {
        $output = shell_exec('sudo /adsbexchange/webconfig/helpers/piaware-disable.sh');
    }
}

if(isset($_POST['fr24Select'])) {
    if ($_POST['fr24Select'] == "Enable") {
        $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-enable.sh ' . escapeshellarg($_POST['fr24mail']));
    }
    if ($_POST['fr24Select'] == "Disable") {
        $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-disable.sh');
    }
    if ($_POST['fr24Select'] == "Reset") {
        $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-reset.sh');
    }
}

?>

</head>
<body>
<center>

<h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
<a class="btn btn-primary" href="../">(..back to main menu)</a><br /><br />

<h3>Configure other feed clients:</h3>

<form method='POST' action="./index.php" onsubmit="return confirm('Execute the selected actions? This can take up to 15 minutes');">

<div class="container-sm adsbx-width">
<table class="table table-striped table-dark"><tbody>

    <tr><td>
    <br><br>
    <h4>Flightaware:</h4>
    <br><br>
    Current status:
    <br><br>
<?php
$output = shell_exec('sudo /adsbexchange/webconfig/helpers/piaware-status.sh 2>&1');
echo "<pre>$output</pre>";
?>
    <select name="faSelect" class="custom-select custom-select-lg btn btn-secondary" id="faSelect">
        <div class="form-group">
        <option value="" selected>Enable or Disable Flightaware</option>
        <option value="Enable">Enable Flightaware</option>;
        <option value="Disable">Disable Flightaware</option>;
        </div>
    </select>

    <br><br>
    Once enabled for 15 minutes, you should be able to link to your flightaware account if desired.
    <br><br>
    If so, use this URL: https://flightaware.com/adsb/piaware/claim
    <br><br>
    In case you have any issues you can add a / and the id from the status at the end of the URL.
    <br><br>

    </td></tr>
    <tr><td>

    <br><br>
    <h4>Flightradar24:</h4>
    <br><br>
    Current status:
    <br><br>
<?php
$output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-status.sh 2>&1');
echo "<pre>$output</pre>";
?>
    <br><br>
    Make sure you use the same email address for the fr24 account and in the below form.
    <br><br>
    Once installed, you can check "my data sharing" on the fr24 website if it's working.
    <br><br>
    It can take an hour or more to show.
    <br><br>
    Enter email address to be submitted to Flightradar24:
    <br><br>
    <input class="form-control" type="text" name="fr24mail"  id="fr24mail" pattern="^[\u0020-\u007e]{8,63}$" />
    <br><br>

    <select name="fr24Select" class="custom-select custom-select-lg btn btn-secondary" id="fr24Select">
        <div class="form-group">
        <option value="" selected>Enable or Disable FR24</option>
        <option value="Enable">Enable FR24</option>;
        <option value="Disable">Disable FR24</option>;
        <option value="Reset">Reset FR24 Sharing Key</option>;
        </div>
    </select>
    <br><br>

    </td></tr>
</tbody></table>
</div>

<input class="btn btn-primary" type="submit" value="Execute changes">
</form>

</center>
</body>
</html>
