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
<center>


<h2>ADSBexchange.com<br>Custom Image - Receiver Config</h2>
<br /><br />

<form method='POST'>

 <?php 
 
$lines = file('/boot/adsb-config.txt');
//$arr[key] = value;
echo '<table>';
foreach($lines as $line) {
	$pos = strpos($line, "=");
	if ($pos === false) {
		$result .= $line;
	} else {
		echo '<tr><td>';
		$key = 	substr($line, 0, $pos);
		$value = substr($line, $pos + 1);
		$value = trim(str_replace('"', "", $value));
		
		echo $key;
		echo '<br>';
?>
<input type="text" name="<?php echo $key; ?>" value= <?php echo $value; ?> />
<select name="cars">
  <option value="yes">yes</option>
  <option value="no">no</option>
</select>
<?php
		//echo '<input type="text" name="$key" value="$value">';
		echo $value;
		echo '<br>';
		$arr[$key] = $value;
		echo '</tr></td>';
	}

}		
	echo '</table>';

    //if(substr($line, 0, 4) == 'DUMP1090=') {
    //    $result .= 'DUMP1090='.$word."\n";
    //} else {
    //    $result .= $line;
    //}
?> 
<br>
<input type="submit" value="Submit">
 </form>
 
 <?php
 
echo '<br>';
var_dump($arr);

//file_put_contents('/tmp/adsb-config.txt', $result);
 
 
 
 ?> 
 
 </center>

</body>
</html>
