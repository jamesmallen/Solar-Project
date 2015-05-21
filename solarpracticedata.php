<?php
require_once("keys.php");
$energy_array= array();
$day_array = array();
for($i = 0; $i<8; $i++){
	$date_test = date("Y m d", time() - 60 * 60 * 24 * $i);
	$date_test=str_replace(" ", "-", $date_test);
	$day = date("l", time() - 60 * 60 * 24 * $i);
	$day_array[] = $day; 
	
	$url_pre = "https://api.enphaseenergy.com/api/v2/systems/341484/summary?summary_date=" . $date_test . "&key=" .$key. "&user_id=" .$userID ;
	$ch_pre = curl_init($url_pre);
	curl_setopt($ch_pre, CURLOPT_RETURNTRANSFER, true);
	$curl_scraped_page_pre = curl_exec($ch_pre);
	//echo $curl_scraped_page_pre;
	curl_close($ch_pre);
	
	//echo $curl_scraped_page_pre;
	$energy_pre = (json_decode($curl_scraped_page_pre)-> {"energy_today"})/1000.0;
	//echo $energy_pre;
	$lightBultkWh_pre = ((14/1000.0)*24); //the energy (in kWh) a 60 watt light bulb uses per day
	//echo $lightBultkWh_pre;
	//echo $energy_pre;
	
	$energy_array[] = $energy_pre;
	$numBulbs_pre = ($energy_pre/$lightBultkWh_pre);
	//echo $energy_array;
	
	/*for($j = 0; $j< count($day_array); $j++){
		echo $day_array[$j];
	}**/
	
}
?>
<?php

$url_pre_usage = "http://wattlog-hathawaybrown.rhcloud.com/usage" ;
	$ch_pre_usage = curl_init($url_pre_usage);
	curl_setopt($ch_pre_usage, CURLOPT_RETURNTRANSFER, true);
	$curl_scraped_page_pre_usage = curl_exec($ch_pre_usage);
	//echo $curl_scraped_page_pre;
	curl_close($ch_pre_usage);
	
	//echo $curl_scraped_page_pre;
	$energy_pre_usage = (json_decode($curl_scraped_page_pre_usage)-> {"watt_hours"});
	//echo $energy_pre_usage;


?>
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
<div class= "graph_container">
<div class='solarpic'>
<img src="HBsolar.jpg" height= "100%" width= "100%">
</div>
<div class = 'graph'>
<?php
echo 'Today we have used '.$energy_pre_usage . ' watts.'; 
for($i=0; $i< count($energy_array); $i++)
{
	$date_info = "Today";
	$day_produced = $day_array[$i];	
	$numBulbs = ($energy_array[$i] / $lightBultkWh_pre);
	echo '<div class="graph_info"> <span style= "float: left; width: 20%; line-height: 32px">'  . $day_produced . '</span>'; 
	
	echo '<div class="graph_lightBulbs">';
	for ($j= 0; $j< floor($numBulbs); $j++){
		echo '<img height="32px" width="20px" src="lightbulb.png">';
	}
	$decimalBulbs = ($numBulbs - floor($numBulbs))*40;
	echo '
	<div style="width: ' . $decimalBulbs . 'px; overflow: hidden; display: inline-block">
		<img height="32px" width="20px" src="lightbulb.png">
	</div>';	
	echo '</div>';
	echo '</div>';
}	
?>


</div>
</div>
<?php
/*echo '<div class="lightBulbs">';
for ($i= 0; $i< floor($numBulbs); $i++){
echo '<img height="40px" width="25px" src="lightbulb.png">';
}
$decimalBulbs = ($numBulbs - floor($numBulbs))*40;
echo '
<div style="height: ' . $decimalBulbs . 'px; overflow: hidden; display: inline-block">
	<img height="40px" width="25px" src="lightbulb.png">
</div> 
';
echo '</div>'; */
$lightBultkWh_pre = ((14/1000.0)*24);
for($i=0; $i< count($energy_array); $i++){
$date_info = "Today";
for($i; $i< count($day_array); $i++){
	$day_produced = $day_array[$i];	

echo '<div class="info">
	' . $day_produced .  ' we produced ' .$energy_array[$i] . ' kWh. Which is equivalent to ' .round(($energy_array[$i]/$lightBultkWh_pre),2). ' light bulbs!
	</div>';
	
}	
}	
	
	
	
?>
<a href="http://enphase.com" title="Enphase API logo small" ><img src="enphaselogo.png"></a>
</div>
</body>
</html>
<?php
//echo $numBulbs;
echo $day_of_data;
?>