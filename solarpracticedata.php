<?php
require_once("keys.php");
$url = "https://api.enphaseenergy.com/api/v2/systems/341484/summary?key=" .$key. "&user_id=" .$userID ;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);
?>
<!--
<?php
echo $curl_scraped_page;
$energy = (json_decode($curl_scraped_page)-> {"energy_today"})/1000.0;
//echo $energy_today;
$lightBultkWh = ((14/1000.0)*24); //the energy (in kWh) a 60 watt light bulb uses per day
echo $lightBultkWh;
echo $energy;
$numBulbs = ($energy/$lightBultkWh);
//echo $numBulbs;
?>
-->
<html>
<head> 
<link rel="stylesheet" href="solarpracticedata.css"> 
<title>Solar Energy Page</title>
</head>
<body>
<div class="background">
<div class="logo">
<img src="http://www.hb.edu/uploaded/images2/logolg.png">
</div>
<?php
echo '<div class="lightBulbs">';
for ($i= 0; $i< floor($numBulbs); $i++){
echo '<img height="40px" width="25px" src="http://upload.wikimedia.org/wikipedia/commons/f/f6/01_Spiral_CFL_Bulb_2010-03-08_(transparent_back).png">';
}
$decimalBulbs = ($numBulbs - floor($numBulbs))*40;
echo '
<div style="height: ' . $decimalBulbs . 'px; overflow: hidden; display: inline-block">
	<img height="40px" width="25px" src="http://upload.wikimedia.org/wikipedia/commons/f/f6/01_Spiral_CFL_Bulb_2010-03-08_(transparent_back).png">
</div>
';
echo '</div>';
echo '<div class="info">
	Today we\'ve produced ' .$lightBultkWh . ' kWh. Which is equivalent to ' .round($numBulbs,2). ' light bulbs!
	</div>';
?>
<a href="http://enphase.com" title="Enphase API logo small" ><img src="https://s3.amazonaws.com/enterprise-multitenant.3scale.net.3scale.net/enphase-energy/2014/05/06/ENPH_logo_scr_RGB_API_sm-4155f33125cda43a.png?AWSAccessKeyId=AKIAIRYLTWBQ37ZNGBZA&amp;Expires=1417629196&amp;Signature=YGMuGG%2FgsWR27SRBOk4g6u27bqw%3D"></a>
</div>
</body>
</html>
<?php
//echo $numBulbs;
?>

