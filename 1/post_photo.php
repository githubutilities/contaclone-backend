<?php
$storage = new SaeStorage();
$domain = 'img';
$name = '111.jpg';
$json['avatar'] = $storage->read($domain,$name);
echo $json['avatar'];
$json['user_id'] = '56';
$json["password"] = "e10adc3949ba59abbe56e057f20f883e";
$url = "http://2.contaclone.sinaapp.com/save_photo.php";
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$json);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
$result = curl_exec($ch);
echo $result;
curl_close($ch); 
?>
