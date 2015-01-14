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

<?php
$url_pre = "https://api.enphaseenergy.com/api/v2/systems/341484/summary_data=2014-01-13?key=" .$key. "&user_id=" .$userID ;
$ch_pre = curl_init($url_pre);
curl_setopt($ch_pre, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page_pre = curl_exec($ch_pre);
curl_close($ch_pre);
?>
<!--
<?php
echo $curl_scraped_page_pre;
$energy_pre = (json_decode($curl_scraped_page_pre)-> {"energy_today"})/1000.0;
//echo $energy_pre;
$lightBultkWh_pre = ((14/1000.0)*24); //the energy (in kWh) a 60 watt light bulb uses per day
echo $lightBultkWh_pre;
echo $energy_pre;
$numBulbs_pre = ($energy_pre/$lightBultkWh_pre);
//echo $numBulbs_pre;
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
<img src="hblogo.png">
</div>
<?php
echo '<div class="lightBulbs">';
for ($i= 0; $i< floor($numBulbs); $i++){
echo '<img height="40px" width="25px" src="lightbulb.png">';
}
$decimalBulbs = ($numBulbs - floor($numBulbs))*40;
echo '
<div style="height: ' . $decimalBulbs . 'px; overflow: hidden; display: inline-block">
	<img height="40px" width="25px" src="lightbulb.png">
</div>
';
echo '</div>';
echo '<div class="info">
	Today we\'ve produced ' .$lightBultkWh . ' kWh. Which is equivalent to ' .round($numBulbs,2). ' light bulbs! Yesterday we made '.$lightBultkWh_pre . ' kWh. Which is equivalent to '.$numBulbs_pre . ' light bulbs!
	</div>';
?>
<a href="http://enphase.com" title="Enphase API logo small" ><img src="enphaselogo.png"></a>
</div>
</body>
</html>
<?php
//echo $numBulbs;
?>

