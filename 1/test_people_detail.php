<?php
$json["user_id"] = "3";
$json["password"] = "a2542aa885fc6d583c72";
$json["circle_id"] = "2";
$json["the_user_id"] = "1";

$filename = "people_detail.php";

if(defined('SAE_MYSQL_HOST_M'))
	$host = "2.contaclone.sinaapp.com";
else $host = "localhost/1";


$url = sprintf("http://%s/%s", $host, $filename);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
$result = curl_exec($ch);
echo $result;
curl_close($ch);
?>