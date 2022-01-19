<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="js/bootstrap.min.js"></script>
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
</style>

</head>
<body class="container-margin">
<div class="container-sm">
<div class="d-grid gap-1 col-0 mx-auto">
        <h4 class="adsbx-green logo-margin"><img src="img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
        <h6>Custom Receiver Software<br /><br />Main Menu</h6>
        	<?php
        		$receivername = file_get_contents("/tmp/webconfig/name");
        		$location = file_get_contents("/tmp/webconfig/location");
        	?>
        Receiver Name:<br /><div class="alert alert-success min-adsb-width" role="alert"><?php echo $receivername ?></div>
        Approximate Configured Location:<br /><div class="alert alert-success min-adsb-width" role="alert"><?php echo $location ?></div>

	<a class="btn btn-primary" href="/wifi">
		<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-wifi" viewBox="0 0 16 16">
  			<path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z"/>
  			<path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
		</svg> Configure WiFi</a>

        <a class="btn btn-primary" href="/config">

<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bullseye" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10zm0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
  <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
  <path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
</svg>

Configure Receiver/Location</a>

 <a class="btn btn-primary" href="/sdr-assign">
<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
</svg>
Assign SDRs to services</a>

        <a class="btn btn-primary" href="/auth">
<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
</svg>
Authentication and System Tools</a>

        <a class="btn btn-primary" href="/info"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg> System Info</a>

        <a class="btn btn-primary" href="/tar1090"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
</svg> 1090 map (default)</a>

        <a class="btn btn-primary" href="/978"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
</svg> 978 map (if enabled, US only)</a>

        <a class="btn btn-primary" href="/tar1090/?pTracks"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
  <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
  <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
  <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
</svg> 8 hours of tracks</a>

        <a class="btn btn-primary" href="/graphs1090"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
  <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
</svg> graphs1090</a>
        <a class="btn btn-primary" href="/" onclick="javascript:event.target.port=3000"> <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
  <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
  <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
</svg> Grafana Dashboard</a>

        <a class="btn btn-primary" href="https://www.adsbexchange.com/myip/"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
  <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z"/>
</svg> ADSBexchange Status</a>

</div>
</div>
</body>
</html>




























pi@adsbexchange:~ $ sudo nano /var/www/html/index.php 
pi@adsbexchange:~ $ cat /var/www/html/index.php 
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
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
	</style>
</head>

<body class="container-margin">
	<div class="container-sm">
		<div class="d-grid gap-1 col-0 mx-auto">
			<h4 class="adsbx-green logo-margin"><img src="img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
			<h6>ADSBX ADS-B Anywhere <br />version 8.0</h6>
			<?php
        		$receivername = file_get_contents("/tmp/webconfig/name");
        		$location = file_get_contents("/tmp/webconfig/location");
        	?> Receiver Name:
				<br />
				<div class="alert alert-success min-adsb-width" role="alert">
					<?php echo $receivername ?>
				</div> Approximate Configured Location:
				<br />
				<div class="alert alert-success min-adsb-width" role="alert">
					<?php echo $location ?>
				</div>
				<a class="btn btn-primary" href="/wifi">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-wifi" viewBox="0 0 16 16">
						<path d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z" />
						<path d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z" /> </svg> Configure WiFi</a>
				<a class="btn btn-primary" href="/config">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bullseye" viewBox="0 0 16 16">
						<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
						<path d="M8 13A5 5 0 1 1 8 3a5 5 0 0 1 0 10zm0 1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z" />
						<path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
						<path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" /> </svg> Configure Receiver/Location</a>
				<a class="btn btn-primary" href="/sdr-assign">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
						<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" /> </svg> Assign SDRs to services</a>
				<a class="btn btn-primary" href="/auth">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
						<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z" />
						<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z" /> </svg> Authentication and System Tools</a>
				<a class="btn btn-primary" href="/info">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
						<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
						<path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" /> </svg> System Info</a>
				<a class="btn btn-primary" href="/tar1090">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z" /> </svg> 1090 map (default)</a>
				<a class="btn btn-primary" href="/978">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-map" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z" /> </svg> 978 map (if enabled, US only)</a>
				<a class="btn btn-primary" href="/tar1090/?pTracks">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
						<path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
						<path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
						<path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" /> </svg> 8 hours of tracks</a>
				<a class="btn btn-primary" href="/graphs1090">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
						<path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z" /> </svg> graphs1090</a>
				<a class="btn btn-primary" href="/" onclick="javascript:event.target.port=3000">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
						<path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z" />
						<path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z" /> </svg> Grafana Dashboard</a>
				<a class="btn btn-primary" target="_blank" href="https://www.adsbexchange.com/myip/">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
						<path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" /> </svg> ADSBexchange Status</a>
		</div>
	</div>
</body>

</html>
pi@adsbexchange:~ $ sudo nano /var/www/html/auth/index.php 
pi@adsbexchange:~ $ cat /var/www/html/auth/index.php 
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
	</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

            function checkPassword() {
		let newpassword1 = document.forms["changepwform"]["newpassword1"].value;
            	let newpassword2 = document.forms["changepwform"]["newpassword2"].value;

                // If password not entered
                if (newpassword1 == '')
                  //alert ("Please enter Password");
                  document.getElementById("password1-missing").removeAttribute("hidden");
                // If confirm password not entered
                else if (newpassword2 == '')
                   //alert ("Please enter confirm password");
                  document.getElementById("password2-missing").removeAttribute("hidden");
                // If Not same return False.
                else if (newpassword1 != newpassword2) {
                   //alert ("\nPasswords did not match: Please try again...")
                  document.getElementById("password-no-match").removeAttribute("hidden");
		   return false;
                }
                // If same return True.
                else{
                    //alert("Password Match!")
                    return true;
                }
            }

</script>


<?php

session_start();

//If logout button pushed, clear session!
 if (!empty($_POST["logout"])) {
	 session_unset();
 }

//Process a password submission, or "unlock file" presence

 if (!empty($_POST["password"]) or file_exists('/boot/unlock') or file_exists('/tmp/webconfig/unlock')) {


	function authenticate($user, $pass){
	  // run shell command to output shadow file, and extract line for $user
	  // then spit the shadow line by $ or : to get component parts
	  // store in $shad as array
	  $shad =  preg_split("/[$:]/",`cat /etc/shadow | grep "^$user\:"`);
	  // use mkpasswd command to generate shadow line passing $pass and $shad[3] (salt)
	  // split the result into component parts
	  $mkps = preg_split("/[$:]/",trim(`mkpasswd -m sha-512 $pass $shad[3]`));
	  // compare the shadow file hashed password with generated hashed password and return
	   //echo $shad[4] . '<br>';
	   //echo $mkps[3] . '<br>';
	  return ($shad[4] == $mkps[3]);
	}


//If authentication passed, refer back to URL that called for auth (if present)

if(authenticate('pi',$_POST["password"]) or file_exists('/boot/unlock') or file_exists('/tmp/webconfig/unlock')){ 
  $_SESSION['authenticated'] = 1;
   if (!empty($_SESSION['auth_URI'])) {
	header("Location: .." . $_SESSION['auth_URI']); 
	unset($_SESSION['auth_URI']);
   }
  }
}


?>

</head>
<body>
<center>

<h4 class="adsbx-green logo-margin"><img src="../img/adsbx-svg.svg" width="35"/>  ADSBexchange.com</h4>
<h6>ADSBX ADS-B Anywhere<br />Feeder Authorization</h6>
<a class="btn btn-primary" href="../">(..back to main menu)</a><br />
<br />
This password is synced with local user account "pi",<br />
whose default password is <a href="https://www.adsbexchange.com/sd-card-docs/">listed in the documentation.</a>
<br /><br />

<?php
//handle PW change
 if (!empty($_POST["newpassword1"])) {
	 if ($_SESSION['authenticated'] == 1) {
		$output = system('echo "pi:' .$_POST["newpassword1"] . '" | sudo chpasswd');
		echo('<br>Your password has been changed: <br>' . $output);
		echo('<p><a href=".">Click here to login... </a></center></body></html>');
		system('sudo rm -r /boot/unlock');
		system('sudo rm -r /tmp/webconfig/unlock');
		session_unset();
		exit;
	 }
 }

//Handle reboot request
 if (!empty($_POST["reboot"])) {
	 if ($_SESSION['authenticated'] == 1) {
		?>
		<script type="text/javascript">
		var timeleft = 70;
		var downloadTimer = setInterval(function(){
		if(timeleft <= 0){
			clearInterval(downloadTimer);
			window.location.replace("../");
		}
		document.getElementById("progressBar").value = 70 - timeleft;
		timeleft -= 1;
		}, 1000);
		</script>
		<progress value="0" max="70" id="progressBar"></progress>
		<br /><br />Rebooting... </center></body></html>
		<?php
		system('sudo /home/pi/adsbexchange/webconfig/reboot.sh > /dev/null 2>&1 &');
		exit;
	 }
 }


//Handle update request
 if (!empty($_POST["update"])) {
	 if ($_SESSION['authenticated'] == 1) {

		?>
		<!-- Output Window -->
		<table><tr><td>
		<pre>
		<div id="output_container"></div>
		</pre>
		</td></tr></table>
		<script type="text/javascript">
		function poll(){
			$("showfile.php", function(data){
				$("#output_container").load('./showfile.php');
			}); 
		}
		setInterval(function(){ poll(); }, 2000);
		</script>
		<?php
		ob_end_flush();
		flush();
		exec('sudo /home/pi/adsbexchange/webconfig/run-update.sh > /dev/null 2>&1 &');
		?>

		<br />System will reboot when complete... </center></body></html>
		<?php
		exit;
	 }
 }

//Handle setdefaults request
 if (!empty($_POST["setdefaults"])) {
	 if ($_SESSION['authenticated'] == 1) {

		?>
		<!-- Output Window -->
		<table><tr><td>
		<pre>
		<div id="output_container"></div>
		</pre>
		</td></tr></table>
		<script type="text/javascript">
		function poll(){
			$("showfile.php", function(data){
				$("#output_container").load('./showfile.php');
			}); 
		}
		setInterval(function(){ poll(); }, 2000);
		</script>
		<?php
		ob_end_flush();
		flush();
		exec('sudo /home/pi/adsbexchange/webconfig/run-defaults.sh > /dev/null 2>&1 &');
		?>
		<br /><br />System will reboot when complete... </center></body></html>
		<?php
		exit;
	 }
 }




//echo('<br>Referrer: ' . $_SESSION['auth_URI'] . '<br>');

if (empty($_SESSION['authenticated'])) {
   $_SESSION['authenticated'] = 0;
}

if ($_SESSION['authenticated'] == 0) {
   echo('You are not authenticated, please enter password to login: ');


?>


<br /><br />
<form method='POST' name="authform" action=".">
  <div class="form-group mb-4 col-6">
	<input type="password" id="password" name="password" class="form-control form-control-lg">
	<small id="passwordHelp" class="form-text text-muted">Use the current pi password.</small>
  </div>
<input type="submit"  class="btn btn-primary" value="Authenticate">
</form>
<br /><strong>Forget your password?</strong><br />
Remove SD card, insert into computer,<br />
create file or directory called "unlock"<br />
on boot partition. Reboot, and return to this page<br />
to set new password.

<?php

}



//Below - things to display if user is authenticated
if ($_SESSION['authenticated'] == 1) {
	?>
	<br />
	<h3>System is unlocked</h3>
	<?php
	if(!file_exists('/boot/unlock') and !file_exists('/tmp/webconfig/unlock')) {

	echo('<form method="POST" name="logout" action=".">');
	echo('<input type="hidden" id="logout" name="logout" value="logout">');
	echo('<input type="submit" class="btn btn-primary" value="Logout"></form>');
	}
	?>

	<br>If you wish to change the password, enter new password below:
	<p>
	<form method='POST' name="changepwform" action="." onSubmit = "return checkPassword()">

	 <div class="form-group mb-4 col-6">
		<div id="password1-missing" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
  			Password missing.
		</div>
                <div id="password2-missing" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
                        Confirm Password missing.
                </div>
                <div id="password-no-match" class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
                        Passwords do not match. Try again.
                </div>
		<label for="password1">New Password</label>
		<input class="form-control form-control-lg" type="password" id="password1" name="newpassword1" required>
		 <label for="password2">Re-enter Password</label>
		<input class="form-control form-control-lg" type="password" id="password2" name="newpassword2" required>
	</div>

	<input type="submit" class="btn btn-primary" value="Change PW">
	</form>
	<hr width="50%">
	<form method='POST' name="reboot" action="." onSubmit = "return confirm('Reboot the feeder?')">
	<input type="hidden" id="reboot" name="reboot" value="reboot">
	<input type="submit" class="btn btn-primary" value="Reboot Feeder">
	</form>
	<p>
	<hr width="50%">
	<form method='POST' name="setdefaults" action="." onSubmit = "return confirm('Reset EVERYTHING to defaults? (network, password, etc.)')">
	<input type="hidden" id="setdefaults" name="setdefaults" value="setdefaults">
	<input type="submit" class="btn btn-primary" value="Reset EVERYTHING to defaults">
	</form>
	(Configuration, network settings, password, etc.)
	<p>
	<hr width="50%">
	<form method='POST' name="update" action="." onSubmit = "return confirm('Update the feeder?')">
	<input type="hidden" id="update" name="update" value="update">
	<input type="submit" class="btn btn-primary" value="Update Feeder">
	</form>
	<a href="https://raw.githubusercontent.com/ADSBexchange/adsbx-update/main/update-adsbx.sh">(executes this script)</a>
	<p>



	<?php
}
?>

 </center>

</body>
</html>
