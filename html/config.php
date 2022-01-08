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



</head>
<body>
<center>


<h2>ADSBexchange.com<br>Custom Image - Receiver Config</h2>
<br /><br />


<form method='POST' name="configform" action="./config.php" onsubmit="return confirm(checkcoords() + '\nSave configuration and reboot?');">


 <?php 
 

 if (!empty($_POST["DUMP1090"])) {
	$content=file_get_contents("/home/pi/adsbexchange/webconfig/adsb-config.txt");

	foreach ( $_POST as $key => $value ) {
		//echo $key . ': ' . $value;
		//echo '<br>';
		//echo "%%" . $key . "%%";
		//echo '<br>';
		$content_chunks=explode("%%" . $key . "%%", $content);
		$content=implode($value, $content_chunks);
		
	}
 
	file_put_contents("/tmp/webconfig/adsb-config.txt", $content);
	echo '<p>Rebooting... visit <a href="http://adsbexchange.local">http://adsbexchange.local</a> to verify changes in about 60 secs..</body></html>';
	system('sudo /home/pi/adsbexchange/webconfig/install-adsbconfig.sh');
	exit;
}

//echo $content;
	
	
$lines = file('/boot/adsb-config.txt');
echo '<table>';
foreach($lines as $line) {
	$pos = strpos($line, "=");
	if (substr($line, 0, 1) == '#') { $pos = false; } # If line is a comment, don't consider it a parameter even if an equal sign comes later.
	if ($pos === false) {
		$result .= $line;
		if (substr($line, 1, 1) == '#') { # if 2nd character is also #, this will be a continuation of comments to be displayed with field.
			$prevline = $prevline . substr($line, 2);
		} else {
			$prevline = substr($line, 2);
		}
	} else {
			echo '<tr><td>';
			$key = 	substr($line, 0, $pos);
			$value = substr($line, $pos + 1);
			$value = trim(str_replace('"', "", $value));

			echo $prevline;
			echo '<br>';
			if (strtolower($value) == 'yes') {
				?><select name="<?php echo $key; ?>">
					<option value="yes" selected>yes</option>
					<option value="no">no</option>
				</select><?php
			
			} elseif (strtolower($value) == 'no') {
				?><select name="<?php echo $key; ?>">
					<option value="yes">yes</option>
					<option value="no" selected>no</option>
				</select><?php
			
			} elseif ($key == "GAIN") {
				?><select name="<?php echo $key; ?>"><?php
				$gainoptions = array(-10, 0.0, 0.9, 1.4, 2.7, 3.7, 7.7, 8.7, 12.5, 14.4, 15.7, 16.6, 19.7, 20.7, 22.9, 25.4, 28.0, 29.7, 32.8, 33.8, 36.4, 37.2, 38.6, 40.2, 42.1, 43.4, 43.9, 44.5, 48.0, 49.6);

				foreach ($gainoptions as $gainval) {
					?><option value="<?php echo $gainval; ?>" <?php if ($gainval == $value) { echo 'selected'; } ?>><?php echo $gainval ; ?></option>
					<?php
					}
					echo '</select>';
			
			} elseif ($key == "LATITUDE") {
				?>
				<input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" pattern="[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)"/>
				<a href="javascript:alert(checkcoords())">Verify</a>
				<?php
					echo '</tr></td>';
			
			} elseif ($key == "LONGITUDE") {
				?>
				<input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" pattern="[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)"/>
				<a href="javascript:alert(checkcoords())">Verify</a>
				<?php
					echo '</tr></td>';
			} elseif ($key == "ALTITUDE") {
				?>
				<input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" pattern="-*[0-9]{1,}(m|ft)"/>
				<?php
					echo '</tr></td>';
			} elseif ($key == "USER") {
				?>
				<input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" pattern="[A-Za-z0-9._]+"/>
				<?php
					echo '</tr></td>';
			} else {
				?>
				<input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
				<?php
					echo '</tr></td>';
			}
		
	}

}		
	echo '</table>';


?> 
<br>

<input type="submit" value="Submit">
 </form>
 
 <?php
 
 
 
 
 ?> 
 
 </center>

</body>
</html>
