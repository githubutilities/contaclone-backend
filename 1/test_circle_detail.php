<?php
$json["user_id"] = "3";
$json["password"] = "a2542aa885fc6d583c72";
$json["circle_id"] = "2";

$filename = "circle_detail.php";
$host = "1.contaclone.sinaapp.com";

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