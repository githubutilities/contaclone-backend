<?php
$json["user_id"] = "3";
$json["password"] = "a2542aa885fc6d583c72";

$filename = "circle_all.php";

$url = sprintf("http://2.contaclone.sinaapp.com/%s", $filename);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
$result = curl_exec($ch);
echo $result;
curl_close($ch);
?>