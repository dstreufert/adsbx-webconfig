<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="js/bootstrap.bundle.min.js"></script>
<style>  
table {  
  font-family: arial, sans-serif;  
  border-collapse: collapse;  
  <!-- width: 50%; -->
}  
  
td, th {  
  border: 2px solid #111111;  
  text-align: center;  
  padding: 5px;  
}  
tr:nth-child(even) {  
  background-color: #D5D8DC;  
}  
</style>


 
</head>
<body>
<div class="container-sm">
<center>
<h2>ADSBexchange.com<br>Custom Receiver Software<br>Main Menu</h2>
<br />
<table>
<tr><td>
<?php
$receivername=file_get_contents("/tmp/webconfig/name");
$location = file_get_contents("/tmp/webconfig/location");
//echo "Receiver Name: " . $receivername;
echo "Receiver Name:<center><pre>$receivername</pre></center>";
echo "Approximate Configured Location:<center><pre>$location</pre></center>";
?>
</td></tr>
<tr><td>
  <div class="d-grid gap-2 d-md-block">
<a class="btn btn-outline-primary" href="/wifi"/>Configure WiFi<a/>
<a class="btn btn-outline-primary" href="/config"/>Configure Receiver/Location<a/>
<a class="btn btn-outline-primary" href="/sdr-assign"/>
Assign SDRs to services</a><br>(for multiple SDR Installs)
<a class="btn btn-outline-primary" href="/auth"/>Authentication and System Tools<a/>
<a class="btn btn-outline-primary" href="/info"/>System Info<a/>
<a class="btn btn-outline-primary" href="/tar1090"/>1090 map (default)<a/>
<a class="btn btn-outline-primary" href="/978"/>978 map (if enabled, US only)<a/>
<a class="btn btn-outline-primary" href="/tar1090/?pTracks"/>8 hours of tracks<a/>
<a class="btn btn-outline-primary" href="/graphs1090"/>graphs1090<a/>
<a class="btn btn-outline-primary" href="/" onclick="javascript:event.target.port=3000"/>Grafana Dashboard<a/>
<a class="btn btn-outline-primary" href="https://www.adsbexchange.com/myip/"/>ADSBexchange Status (/myip)<a/>
</td></tr>
</table>
</center>
</div>
</body>
</html>
