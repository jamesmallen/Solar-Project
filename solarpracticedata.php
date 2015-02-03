<?php
require_once("keys.php");
$energy_array= array();
for($i = 0; $i<8; $i++){
	$date_test = date("Y m d", time() - 60 * 60 * 24 * $i);
	$date_test=str_replace(" ", "-", $date_test);
	
	$url_pre = "https://api.enphaseenergy.com/api/v2/systems/341484/summary?summary_date=" . $date_test . "&key=" .$key. "&user_id=" .$userID ;
	$ch_pre = curl_init($url_pre);
	curl_setopt($ch_pre, CURLOPT_RETURNTRANSFER, true);
	$curl_scraped_page_pre = curl_exec($ch_pre);
	echo $curl_scraped_page_pre;
	curl_close($ch_pre);
	
	echo $curl_scraped_page_pre;
	$energy_pre = (json_decode($curl_scraped_page_pre)-> {"energy_today"})/1000.0;
	//echo $energy_pre;
	$lightBultkWh_pre = ((14/1000.0)*24); //the energy (in kWh) a 60 watt light bulb uses per day
	echo $lightBultkWh_pre;
	echo $energy_pre;
	
	$energy_array[] = $energy_pre;
	//$numBulbs_pre = ($energy_pre/$lightBultkWh_pre);
}
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
<div class='solarpic'>
<img width=40% src="HBsolar.jpg">
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
$lightBultkWh_pre = ((14/1000.0)*24);
for($i=0; $i< count($energy_array); $i++){
$date_info = "Today";
if ($i==1)
{
	$date_info = "1 day ago";
}
else if ($i>1)
{
	$date_info = $i . " days ago";
}
echo '<div class="info">
	' . $date_info . ' we produced ' .$energy_array[$i] . ' kWh. Which is equivalent to ' .round(($energy_array[$i]/$lightBultkWh_pre),2). ' light bulbs!
	</div>';
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