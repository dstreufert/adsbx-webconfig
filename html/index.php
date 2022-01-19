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
<div class="container">
  <center>
   <div class="row row-cols-1">
     <div class="col"><h2>ADSBexchange.com<br>Custom Receiver Software<br />Main Menu</h2>
    <br />
      <?php
        $receivername=file_get_contents("/tmp/webconfig/name");
        $location = file_get_contents("/tmp/webconfig/location");
        echo "Receiver Name:<center><pre>$receivername</pre></center>";
        echo "Approximate Configured Location:<center><pre>$location</pre></center>";
      ?>
    </div>
    <div class="d-grid gap-2 col-6 mx-auto">
       <div class="col"><a class="btn btn-outline-primary" href="/wifi"/>Configure WiFi<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/config"/>Configure Receiver/Location<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/sdr-assign"/>Assign SDRs to services<div class="alert alert-info" role="alert">(for multiple SDR Installs)</div></a></div>
       <div class="col"><a class="btn btn-outline-primary" href="/auth"/>Authentication and System Tools<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/info"/>System Info<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/tar1090"/>1090 map (default)<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/978"/>978 map (if enabled, US only)<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/tar1090/?pTracks"/>8 hours of tracks<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/graphs1090"/>graphs1090<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="/" onclick="javascript:event.target.port=3000"/>Grafana Dashboard<a/></div>
       <div class="col"><a class="btn btn-outline-primary" href="https://www.adsbexchange.com/myip/"/>ADSBexchange Status (/myip)<a/></div>
    </div>
  </div>
  </center>
</div>
</body>
</html>
