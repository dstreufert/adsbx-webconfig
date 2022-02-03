<?php
include('comments.php');
?>
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
		max-width: 450px;
	}
</style>



<script type="text/javascript">

function checkcoords() {
        var resp ;
        var xmlHttp ;

		var lat = document.forms['configform'].elements['LATITUDE'].value;
		var lon = document.forms['configform'].elements['LONGITUDE'].value;

		url =  'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + lat + '&longitude=' + lon + '&localityLanguage=en';

        resp  = '' ;
        xmlHttp = new XMLHttpRequest();

        if(xmlHttp != null)
        {
            xmlHttp.open( "GET", url, false );
            xmlHttp.send( null );
			resp = xmlHttp.responseText
        }

		var locdata = JSON.parse(resp);
        thealert = "Location is:\n" + locdata.locality + ", " + locdata.principalSubdivisionCode + ",\n" + locdata.countryName;
		//alert(thealert);
		return thealert;
    }

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


			<h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
			<h6>ADSBX ADS-B Anywhere <br />version 8.0</h6>
			<a class="btn btn-primary" href="../">(..back to main menu)</a><br /><br />


<form method='POST' name="configform" action="./index.php" onsubmit="return confirm(checkcoords() + '\nSave configuration and reboot?');">


<?php
function sanitize($string) {
	return preg_replace('/[^A-Za-z0-9_.\-]/', '', $string);
}
if (!empty($_POST["DUMP1090"])) {
	$new_config = '';
	foreach ( $_POST as $key => $value ) {
		$value = sanitize($value);
		if($key == "USER"){
			$new_config .= $key ."=\"".$value . "\"\n";
		} else {
			$new_config .= $key ."=".$value . "\n";
		}
	}
	file_put_contents("/tmp/webconfig/adsb-config.txt", $new_config);
	?>

	<script type="text/javascript">
		var timeleft = 70;

		var downloadTimer = setInterval(function(){

			if(timeleft <= 0){
				clearInterval(downloadTimer);
				window.location.replace("../index.php");
			}

		document.getElementById("progressBar").style.width = (70 - timeleft) + "%";

		timeleft -= 1;

		}, 1000);
	</script>

	<div class="progress">
  		<div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="70" style="width: 1%"></div>
	</div>
	<?php
	echo '<p>Rebooting... visit <a href="../index.php">this link</a> to verify changes in about 70 secs..</form></body></html>';
	system('sudo /adsbexchange/webconfig/helpers/install-adsbconfig.sh > /dev/null 2>&1 &');
	exit;
} // end if $_post

?>
<div class="container-sm adsbx-width">
<table class="table table-striped table-dark">
<?php
	echo '<tr><td>';
	$lines = file('/boot/adsb-config.txt',FILE_SKIP_EMPTY_LINES);
	//print_r($lines);
    foreach($lines as $line) {
        if(!(preg_match("/^#/",$line))) {
            $line = str_replace("\"","",$line);
            $key = explode("=",$line);
            if ($key[0] == "GAIN") {
                echo str_replace('#','<br />',$gain."<br /><br />");
                ?><select class="form-control" name="<?php echo $key[0]; ?>"><?php
                $gainoptions = array(-10, 0.0, 0.9, 1.4, 2.7, 3.7, 7.7, 8.7, 12.5, 14.4, 15.7, 16.6, 19.7, 20.7, 22.9, 25.4, 28.0, 29.7, 32.8, 33.8, 36.4, 37.2, 38.6, 40.2, 42.1, 43.4, 43.9, 44.5, 48.0, 49.6, 59);

                foreach ($gainoptions as $gainval) {
				?><option value="<?php echo $gainval; ?>" <?php if ($gainval == $key[1]) { echo 'selected'; } ?>><?php echo $gainval ; ?></option>
				<?php
				}
				echo '</select> </td></tr><tr><td>';
			}

            if ($key[0] == "LATITUDE") {
				echo str_replace('#','<br />',$latitude."<br /><br />");
				?>
				<input class="form-control" type="text" name="<?php echo $key[0]; ?>" value="<?php echo $key[1]; ?>" pattern="[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)"/>
				<?php
				echo '</tr></td><tr><td>';

            }
			if ($key[0] == "LONGITUDE") {
				echo str_replace('#','<br />',$longitude."<br /><br />");
				?>
				<input class="form-control" type="text" name="<?php echo $key[0]; ?>" value="<?php echo $key[1]; ?>" pattern="[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)"/>
                                <br />
                                <a class="btn btn-primary" href="javascript:alert(checkcoords())">Verify Coordinates</a>
				<?php
				echo '</tr></td><tr><td>';
			}

			if ($key[0] == "ALTITUDE") {
				echo str_replace('#','<br />',$altitude."<br /><br />");
				?>
				<input class="form-control" type="text" name="<?php echo $key[0]; ?>" value="<?php echo $key[1]; ?>" pattern="-*[0-9]{1,}(m|ft)"/>
				<?php
				echo '</tr></td><tr><td>';
			}

			if ($key[0] == "USER") {
				echo  str_replace('#','<br />',$mlat_name."<br /><br />");
				$user = str_replace('"','',$key[1]);
				?>
				<input class="form-control" type="text" name="<?php echo $key[0]; ?>" value="<?php echo $user; ?>" pattern="[A-Za-z0-9._]+"/>
				<?php
				echo '</tr></td><tr><td>';
			}

			if ($key[0] == "DUMP1090") {
                                echo  str_replace('#','<br />',$enable_1090."<br /><br />");
                                ?>
					<select class="form-control" name="<?php echo $key[0]; ?>">
					<?php
					if(str_replace(array("\n", "\t", "\r"), '', strtoupper($key[1])) == "YES"){
					?>
						<option value="yes" selected>yes</option>
						<option value="no">no</option>
					<?php
					} else {
					?>
						<option value="yes">yes</option>
						<option value="no" selected>no</option>
					<?php
					}
					?>
					</select>
                                <?php
                                echo '</tr></td><tr><td>';
                        }

			if ($key[0] == "DUMP978") {
                                echo  str_replace('#','<br />',$enable_978."<br /><br />");
                                ?>
					<select class="form-control" name="<?php echo $key[0]; ?>">
					<?php
					if(str_replace(array("\n", "\t", "\r"), '', strtoupper($key[1])) == "YES"){
					?>
						<option value="yes" selected>yes</option>
						<option value="no">no</option>
					<?php
					} else {
					?>
						<option value="yes">yes</option>
						<option value="no" selected>no</option>
					<?php
					}
					?>
					</select>
                                <?php
                                echo '</tr></td><tr><td>';
            }

            if ($key[0] == "AUTOGAIN") {
                echo  str_replace('#','<br />',$autogain."<br /><br />");

                ?>
                <select class="form-control" name="<?php echo $key[0]; ?>">
                <?php

                if(str_replace(array("\n", "\t", "\r"), '', strtoupper($key[1])) == "YES"){
                    ?>
                    <option value="yes" selected>yes</option>
                    <option value="no">no</option>
                    <?php
                } else {
                    ?>
                    <option value="yes">yes</option>
                    <option value="no" selected>no</option>
                    <?php
                }

                ?>
                </select>
                <?php

                echo '</tr></td><tr><td>';
            }

			if ($key[0] == "ZEROTIER") {
                                echo  str_replace('#','<br />',$zerotier."<br /><br />");
                                ?>
					<select class="form-control" name="<?php echo $key[0]; ?>">
					<?php
					if(str_replace(array("\n", "\t", "\r"), '', strtoupper($key[1])) == "YES"){
					?>
						<option value="yes" selected>yes</option>
						<option value="no">no</option>
					<?php
					} else {
					?>
						<option value="yes">yes</option>
						<option value="no" selected>no</option>
					<?php
					}
					?>
					</select>
                                <?php
                                echo '</tr></td><tr><td>';
            }

			if ($key[0] == "PROMG") {
                                echo  str_replace('#','<br />',$grafana."<br /><br />");
                                ?>
					<select class="form-control" name="<?php echo $key[0]; ?>">

					<?php
					if(str_replace(array("\n", "\t", "\r"), '', strtoupper($key[1])) == "YES"){
					?>
						<option value="yes" selected>yes</option>
						<option value="no">no</option>
					<?php
					} else {
					?>
						<option value="yes">yes</option>
						<option value="no" selected>no</option>
					<?php
					}
					?>
					</select>
                                <?php
                                echo '</tr></td><tr><td>';
            }

            if ($key[0] == "MLAT_MARKER") {
                echo  str_replace('#','<br />',$mlat_marker."<br /><br />");

                ?>
                <select class="form-control" name="<?php echo $key[0]; ?>">
                <?php

                if(str_replace(array("\n", "\t", "\r"), '', strtoupper($key[1])) == "YES"){
                    ?>
                    <option value="yes" selected>yes</option>
                    <option value="no">no</option>
                    <?php
                } else {
                    ?>
                    <option value="yes">yes</option>
                    <option value="no" selected>no</option>
                    <?php
                }

                ?>
                </select>
                <?php

                echo '</tr></td><tr><td>';
            }
        }
    }
?>

</table>
</div>
<br />

<input class="btn btn-danger" type="submit" value="Save & Reboot">
 </form>

 <?php

 ?>

 </center>

</body>
</html>
