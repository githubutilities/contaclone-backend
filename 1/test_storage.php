<?php

//header('content-type: image/jpeg');

$url = 'http://www.baidu.com/img/bd_logo1.png';
$img = file_get_contents($url);
//readfile($url);
//echo "HI";


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
$ret['avatar'] = base64_encode($contents);
echo json_encode($ret);
/*
echo "HI". "<br>";
$files = $storage->getList($domain);

$imageUrls = array();
foreach ($files as $imagefile) {
	//$tempName=$directoryname ."/". $imagefile["Name"];

	$imageUrl = $storage->getUrl($domain, $imagefile);
	array_push($imageUrls, $imageUrl);
	echo $imageUrl . "<br>";
}*/
