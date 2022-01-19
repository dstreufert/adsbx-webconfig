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
.btn-margin-bottom{
    margin-bottom: 5px !important;
}

</style>

</head>
<body>
<div class="container-fluid">
<div class="d-grid gap-1 col-2 mx-auto">
        <h5>ADSBexchange.com<br />Custom Receiver Software<br /><br />Main Menu</h5>
        <?php
        $receivername=file_get_contents("/tmp/webconfig/name");
        $location = file_get_contents("/tmp/webconfig/location");
        ?>
        <div class="col">Receiver Name:<br /><div class="alert alert-primary" role="alert"><?php echo $receivername ?></div></div>
        <div class="col">Approximate Configured Location:<br /><div class="alert alert-primary" role="alert"><?php echo $location ?></div></div>

        <a class="btn btn-primary" href="/wifi"/>Configure WiFi<a/>
        <a class="btn btn-primary" href="/config"/>Configure Receiver/Location<a/>
        <a class="btn btn-primary" href="/sdr-assign"/>Assign SDRs to services <br />(for multiple SDR Installs)</a>
        <a class="btn btn-primary" href="/auth"/>Authentication and System Tools<a/>
        <a class="btn btn-primary" href="/info"/>System Info<a/>
        <a class="btn btn-primary" href="/tar1090"/>1090 map (default)<a/>
        <a class="btn btn-primary" href="/978"/>978 map (if enabled, US only)<a/>
        <a class="btn btn-primary" href="/tar1090/?pTracks"/>8 hours of tracks<a/>
        <a class="btn btn-primary" href="/graphs1090"/>graphs1090<a/>
        <a class="btn btn-primary" href="/" onclick="javascript:event.target.port=3000"/>Grafana Dashboard<a/>
        <a class="btn btn-primary" href="https://www.adsbexchange.com/myip/"/>ADSBexchange Status (/myip)<a/>

        </div>
</div>
</body>
</html>
