<?php 
session_start();
if ($_SESSION['authenticated'] != 1) {
	exit(); 
}

system('cat /tmp/web_display_log');
//echo($output);
?>
