<?php
$json["user_id"] = "3";
$json["password"] = "a2542aa885fc6d583c72";
$json["avatar"] = get_avatar();

$filename = "save_photo.php";
$url = sprintf("http://contaclone.sinaapp.com/%s", $filename);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
$result = curl_exec($ch);
echo $result;
curl_close($ch);

function get_avatar() {
	$storage = new SaeStorage();
	$domain = "img";
	$FileName = "42a98226cffc1e170668cc774a90f603728de9a5.jpg";

	$im = $storage->read($domain,$FileName);
	$im = imagecreatefromstring($im);
	//var_dump($im);
	//imagejpeg($im);
	//var_dump($im);
	//echo base64_encode($im);
	ob_start();
	imagejpeg($im);
	$contents =  ob_get_contents();
	ob_end_clean();

	//echo "<img src='data:image/png;base64,".base64_encode($contents)."' />";
	return base64_encode($contents);
}