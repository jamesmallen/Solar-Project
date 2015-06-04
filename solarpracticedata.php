<?php

if (isset($_ENV["OPENSHIFT_DATA_DIR"])) {
	$data_dir = $_ENV["OPENSHIFT_DATA_DIR"];
} else {
	$data_dir = '.';
}

require_once("$data_dir/keys.php");
$energy_array= array();
$day_array = array();

$currentPower = 0; //kWh
$energy_pre = 0; //energy so far today in kWh
$lightBultkWh_pre = ((14/1000.0)*24); //the energy (in kWh) a 60 watt light bulb uses per day
$totalEnegery = 0; //total energy the solar panels have produced over their whole existence (kWh)
$numModules = 0; //number of solar panels up and running currently
$wakeUp = 0; //when did the solar panels first start working? Probably won't be displayed.
$energyUseToday = 0;
$energyUseWeek = 0;
$energy_pre_week = 0; //total energy we have produced over the week

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
	
	if ($i == 0){
		$currentPower = (json_decode($curl_scraped_page_pre)-> {"current_power"})/1000.0;
		$totalEnergy = (json_decode($curl_scraped_page_pre)-> {"energy_lifetime"})/1000.0;
		$numModules = json_decode($curl_scraped_page_pre)-> {"modules"};
		$wakeUp = date('m/d/Y', json_decode($curl_scraped_page_pre)-> {"operational_at"});
	}
	
	$energy_pre = (json_decode($curl_scraped_page_pre)-> {"energy_today"})/1000.0;
	$energy_pre_week += $energy_pre;
	
	//echo $energy_pre;
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

	$dateNow = date("Y m d", time());
	$dateNow = str_replace(" ", "-", $dateNow);
	$dateLastWeek = date("Y m d", time() - 60 * 60 * 24 * 7);
	$dateLastWeek = str_replace(" ", "-", $dateLastWeek);
	$url_pre_usage = "http://wattlog-hathawaybrown.rhcloud.com/usage?format=json&start_date=" . $dateNow;
	$ch_pre_usage = curl_init($url_pre_usage);
	curl_setopt($ch_pre_usage, CURLOPT_RETURNTRANSFER, true);
	$curl_scraped_page_pre_usage = curl_exec($ch_pre_usage);
	//echo $curl_scraped_page_pre_usage;
	curl_close($ch_pre_usage);
	
	//echo $curl_scraped_page_pre;
	$energyUseToday = (json_decode($curl_scraped_page_pre_usage)-> {"watt_hours"});
	
	$url_pre_usage = "http://wattlog-hathawaybrown.rhcloud.com/usage?format=json&start_date=" . $dateLastWeek;
	$ch_pre_usage = curl_init($url_pre_usage);
	curl_setopt($ch_pre_usage, CURLOPT_RETURNTRANSFER, true);
	$curl_scraped_page_pre_usage = curl_exec($ch_pre_usage);
	//echo $curl_scraped_page_pre_usage;
	curl_close($ch_pre_usage);
	
	$energyUseWeek = (json_decode($curl_scraped_page_pre_usage)-> {"watt_hours"});
	//echo $energy_pre_usage;
?>
<html>
<head> 
<link rel="stylesheet" href="solarpracticedata.css">
<title>Solar Energy Page</title>
<meta http-equiv="refresh" content="300" />
</head>
<body>
<div class="background">
<div class="logo">
<img src="hblogo.png">
</div>
<div class= "graph_container">
<div class='solarpic'>
<img src="HBsolar.jpg" width= "100%">
</div>
<div class = 'graph'>
<?php
echo '<span style="margin-bottom: 3px;">Today we have used '. $energyUseToday . ' watts.</span>';
for($i=0; $i< count($energy_array); $i++)
{
	$date_info = "Today";
	$day_produced = $day_array[$i];	
	$numBulbs = ($energy_array[$i] / $lightBultkWh_pre);
	echo '<div class="graph_info"> <span style= "float: left; width: 20%; line-height: 32px">'  . $day_produced . '</span>'; 
	
	echo '<div class="graph_lightBulbs">';
	echo $energy_array[$i] . "kWh - ";
	for ($j= 0; $j< floor($numBulbs); $j++){
		echo '<img height="32px" width="20px" src="lightbulb.png">';
	}
	$decimalBulbs = ($numBulbs - floor($numBulbs))*20;
	echo '
	<div style="width: ' . $decimalBulbs . 'px; overflow: hidden; margin-left: -5px; display: inline-block">
		<img height="32px" width="20px" src="lightbulb.png">
	</div>';	
	echo '</div>';
	echo '</div>';
}	
?>


</div>
</div>

<?php
/*
$lightBultkWh_pre = ((14/1000.0)*24);
for($i=0; $i< count($energy_array); $i++){
$date_info = "Today";
for($i; $i< count($day_array); $i++){
	$day_produced = $day_array[$i];	

echo '<div class="info">
	' . $day_produced .  ' we produced ' .$energy_array[$i] . ' kWh. Which is equivalent to ' . round(($energy_array[$i]/$lightBultkWh_pre),2) . ' light bulbs!
	</div>';
	
}	
}
$currentPower = 0; //kWh
$energy_pre = 0; //energy so far today in kWh
$lightBultkWh_pre = ((14/1000.0)*24); //the energy (in kWh) a 60 watt light bulb uses per day
$totalEnegery = 0; //total energy the solar panels have produced over their whole existence (kWh)
$numModules = 0; //number of solar panels up and running currently
$wakeUp = 0; //when did the solar panels first start working? Probably won't be displayed.
$energyUseToday = 0;
$energyUseWeek = 0;
*/
?>

<div class="info">
<?php

$plural = "they are";
if ($numModules == 1) 
{
	$plural = "it is";
}
//echo "Our solar panels at HB have been functioning since " . $wakeUp . "<br>";
//echo "Since they have been here, they have produced a whopping total of " . $totalEnergy . "kWh!<br>";
echo "Currently " . $numModules . " of 5 solar panels are up and running. At this moment " . $plural . " producing " . $currentPower . " WH.";
echo "<br>";
echo "Today HB has consumed " . $energyUseToday . " WH of electricity whereas the solar panels have produced " . $energy_pre*1000 . " WH.<br>";
$percent = "";
if ($energyUseToday < $energy_pre*1000)
{
	$percent = "more than enough";
}
else
{
	$percent = ($energyUseToday/$energy_pre*1000)*100 . "%";
}
echo "This means that the solar panels have provided " . $percent . " of our electricity.<br>";
if ($energyUseWeek < $energy_pre_week*1000)
{
	$percent = "more than enough";
}
else
{
	$percent = ($energyUseWeek/$energy_pre_week*1000)*100 . "%";
}
echo "This week HB has consumed " . $energyUseWeek . " WH of electricity and the solar panels have produced " . $energy_pre_week*1000 . " WH.<br>";
echo "So they have produced " . $percent . " of our electricity!"
?>
</div>


<a href="http://enphase.com" title="Enphase API logo small"><img src="enphaselogo.png" style="margin: 5px;"></a>
</div>
</body>
</html>
<?php
//echo $numBulbs;
//echo $day_of_data;
?>