<?php
$json["user_id"] = "1";
$json["password"] = "e10adc3949ba59abbe56e057f20f883e";

$filename = "my_detail.php";

if(defined('SAE_MYSQL_HOST_M'))
	$host = "1.contaclone.sinaapp.com";
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