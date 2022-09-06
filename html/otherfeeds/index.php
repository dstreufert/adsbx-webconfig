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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['faSelect'])) {
        if ($_POST['faSelect'] == "Enable") {
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/piaware-enable.sh ' . escapeshellarg($_POST['fa-id']));
        }
        if ($_POST['faSelect'] == "Disable") {
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/piaware-disable.sh');
        }
    }

    if(isset($_POST['fr24Select'])) {
        if ($_POST['fr24Select'] == "Enable") {
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-install.sh');
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-enable.sh ' . escapeshellarg($_POST['fr24mail']) . ' ' . escapeshellarg($_POST['fr24key']));
        }
        if ($_POST['fr24Select'] == "Disable") {
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-disable.sh');
        }
    }

    if(isset($_POST['fr24Select2'])) {
        if ($_POST['fr24Select2'] == "Reset") {
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-reset.sh');
        }
        if ($_POST['fr24Select2'] == "Reinstall") {
            $output = shell_exec('sudo /adsbexchange/webconfig/helpers/fr24-install.sh');
        }
    }

    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
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

Although this software is published by ADSBexchange, we recognize that sometimes users may want to feed data to other sites as well. Our goal is to put the feeders first by making it easy to get the most from their hardware investment.
<p><p>To facilitate this, we include the following web GUI setups.
<p>
When considering which flight tracking services to support with your data, keep the following in mind:
<ul>
<li>ADSBexchange was built by aviation enthusiasts, for aviation enthusiasts.  We're not a fortune 500 company.
We don't have a company jet, a fancy office (or any office at all) but thanks to people like you
we ARE building a network that gives the big guys a run for their money.
<p>
<li>Other flight tracking sites block all sorts of aircraft. This includes aircraft owned by authoritarian regiemes,
dicators, war criminals, oligarchs, and anyone else who doeesn't want to be seen.
ADSBexchange doesn't beleive in playing these games, and shares all data collected.
</ul>

</td></tr>

	
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
    <br><br>
    Optional, leave empty if uncertain:<br>
    Input your old feeder-id (Unique Identifier) from your FA stats page if you want to keep using that id.
    Don't use the same feeder-id with more than one receiver.
    <br><br>
    <input class="form-control" type="text" name="fa-id"  id="fa-id" pattern="^[\u0020-\u007e]{8,63}$" />
    <br><br>
    <select name="faSelect" class="custom-select custom-select-lg btn btn-secondary" id="faSelect">
        <div class="form-group">
        <option value="" selected>Enable or Disable Flightaware</option>
        <option value="Enable">Enable Flightaware</option>;
        <option value="Disable">Disable Flightaware</option>;
        </div>
    </select>

    <br><br>
    Once enabled for 15 minutes, you should be able to link to your flightaware account.
    <br><br>
    If you want to do so, use this URL:<br>
<?php
$fa_id = shell_exec('cat /var/cache/piaware/feeder_id');
if (strlen($fa_id) >= 36) {
    $claim_url = "https://flightaware.com/adsb/piaware/claim/".$fa_id;
} else {
    $claim_url = "https://flightaware.com/adsb/piaware/claim";
}
echo '<a href="'.$claim_url.'">'.$claim_url.'</a>';
?>
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
    It can take an hour or more to show and the status above to show as "connected":
    <br><br>
    <a href="https://www.flightradar24.com/account/data-sharing">https://www.flightradar24.com/account/data-sharing</a>
    <br><br>
    Enter email address to be submitted to Flightradar24:
    <br><br>
    <input class="form-control" type="text" name="fr24mail"  id="fr24mail" pattern="^[\u0020-\u007e]{8,63}$" />
    <br><br>
    Optional, leave empty if uncertain:<br>
    If you want to reuse a fr24 sharing key you have received via mail or can see on the fr24 webpage, enter it below.<br>
    This is mandatory if you already have 3 sharing keys associated with an email address as fr24 only allows 3 share keys per email address.
    <br><br>
    <input class="form-control" type="text" name="fr24key"  id="fr24key" pattern="^[\u0020-\u007e]{8,63}$" />
    <br><br>

    <select name="fr24Select" class="custom-select custom-select-lg btn btn-secondary" id="fr24Select">
        <div class="form-group">
        <option value="" selected>Enable or Disable FR24</option>
        <option value="Enable">Enable FR24</option>;
        <option value="Disable">Disable FR24</option>;
        </div>
    </select>

    <br><br>
    In case you want to get a new FR24 sharing key for whatever reason, populate the email address field above and choose reset below.
    <br><br>
    The below drop down also allows reinstalling the fr24 binary.<br>It will also update the fr24feed version if the authors of this webinterface have set that new version to be used, this happens maybe twice a year so don't expect the most recent fr24feed version, it's not necessary.
    <br><br>
    <select name="fr24Select2" class="custom-select custom-select-lg btn btn-secondary" id="fr24Select2">
        <div class="form-group">
        <option value="" selected>Specialty options for FR24</option>
        <option value="Reset">Reset FR24 config / sharing key</option>;
        <option value="Reinstall">Reinstall / Update FR24</option>;
        </div>
    </select>

    </td></tr>
</tbody></table>
</div>

<input class="btn btn-primary" type="submit" value="Execute changes">
</form>

</center>
</body>
</html>
