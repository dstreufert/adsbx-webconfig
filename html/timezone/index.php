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


<?php
session_start();
if ($_SESSION['authenticated'] != 1) {
    $_SESSION['auth_URI'] = $_SERVER['REQUEST_URI'];
    header("Location: ../auth/");
}
?>

</head>
<body>

    <center>

    <h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
    <h6>ADSBX ADS-B Anywhere <br />version <?php echo file_get_contents("/boot/adsbx-version"); ?></h6>
        <a class="btn btn-primary" href="../">(..back to main menu)</a><br /><br />
    <form method='POST' action="./index.php" onsubmit="return confirm('Change Timezone?');">

<?php

$newTimezone ='';

if(isset($_POST['timezoneSelect'])) {
    $newTimezone = $_POST['timezoneSelect'];
}

if (!empty($newTimezone)) {
    $output = shell_exec('sudo /adsbexchange/webconfig/helpers/set-timezone.sh ' . escapeshellarg($newTimezone) . ' 2>&1');
}
?>

    <h3>Change the timezone:</h3>

    <br>
    Current Time: 
    <?php
    $output = shell_exec('date');
    echo "<pre>$output</pre>";
    ?>
    Current Timezone: 
    <?php
    $output = shell_exec('timedatectl status | grep -i "Time zone:" | cut -d: -f2');
    echo "<pre>$output</pre>";
    ?>
        <select name="timezoneSelect" class="custom-select custom-select-lg btn btn-secondary" id="timezoneSelect">
            <div class="form-group">
            <option name="Timezone" value="" selected>Choose Timezone ...</option>
    <?php

    $timezones = shell_exec('timedatectl list-timezones');
    $lines = explode(PHP_EOL, $timezones);

    echo '<option value="UTC">UTC</option>';
    foreach($lines as $line) {
        echo '<option value="'.$line.'">'.$line.'</option>';
    }

    ?>
            </div>
        </select>
    <input class="btn btn-primary" type="submit" value="Submit">
    </form>
</center>
</body>
</html>
