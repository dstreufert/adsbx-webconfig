<?php
$head="# !!!!! HEADS-UP WINDOWS USERS !!!!!
#
# Do not use Wordpad for editing this file, it will mangle it and your
# configuration won't work. Use a proper text editor instead.
# Recommended: Notepad, Notepad++, VSCode, Atom, SublimeText.
#
# !!!!! HEADS-UP MACOSX USERS !!!!!
#
# If you use Textedit to edit this file make sure to use \"plain text format\"
# and \"disable smart quotes\" in \"Textedit > Preferences\", otherwise Textedit 
# will use none-compatible characters and your network configuration won't
# work!

# location
# note the (-) sign
# for your hemisphere
# If latitude is positive, the position is on the northern hemisphere.
# If latitude is negative, it is on the southern hemisphere.
# If west of prime meridian, then longitude is negative.
# If east of prime meridian, then longitude is positive.
";

$latitude= "# Precise Latitude
#
# Northern Hemisphere, positive.
# Southern Hemisphere, negative.
# <a href=\"https://www.freemaptools.com/elevation-finder.htm\" target=\"_blank\">
# This site</a> can be used to determine lat/lon and ground altitude.";

$longitude = "# Precise Longitude
#
# East of Greenwich, UK - positive.
# West of Greenwich (including USA) - negative.";

$altitude = "# Altitude of your antenna above MSL (mean sea level)
#
# Meters Example: \"1050m\"
# Feet Example: \"305ft\"";

$mlat_name = "# Feeder Name for MLAT Map <br>
# Spaces or special characters will be removed.<br>
# Numbers, letters, underscore ( _ ) and period ( . ) only.";

$enable_1090 = "# Enable 1090 using readsb?";

$gain = "# Gain setting for 1090 readsb";

$autogain = "# Adjust gain every 24 hours automatically?
# <a href=\"https://github.com/wiedehopf/adsb-scripts/wiki/Automatic-gain-optimization-for-readsb-and-dump1090-fa\">More info</a>";

$enable_978 = "# Enable 978 UAT? (requires second SDR)";

$zerotier = "# Allow ADSBexchange staff to access this unit remotely for troubleshooting via zerotier?";

$grafana = "# Run Graphana?";

$customleds = "# Use Custom LED Indications?";

$mlat_marker = "# MLAT marker: Marker with random 5 mile offset on
<a href=\"https://map.adsbexchange.com/mlat-map/\" target=\"_blank\">https://map.adsbexchange.com/mlat-map</a>";

$zerotier_standalone = "# Enable zerotier service without remote support";

$graphs1090 = "# Enable graphs1090?";

?>

