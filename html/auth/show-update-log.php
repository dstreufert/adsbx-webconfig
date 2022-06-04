<?php
session_start();
if ($_SESSION['authenticated'] != 1) {
	exit();
}

system('cat /adsbexchange/adsbx-update.log');
//echo($output);
?>

