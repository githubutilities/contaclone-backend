<?php
require_once 'utilities.php';
$userid = $_POST["user_id"];
$password = $_POST["password"];
$avatar = $_POST["avatar"];

if(LOGIN_SUCCEED == login($userid, $password)){

	$link = connect_db();
	if($link) {
		$response["resultCode"]= -999;//set default response to false

		$domain = 'img';
		$storage = new SaeStorage();
//start
		$contain = base64_decode($avatar);//decode $avatar to binary
		//sae_debug($avatar . "<br>");
		$converted = img_convert_to_jpg($contain);
//echo "<img src='data:image/png;base64,".base64_encode($contain)."' /><br>";
		//echo $avatar;

		//echo "<img src='data:image/png;base64,".$avatar."' /><br>";
//end
		//$contain = base_convert($_POST["avatar"], 16, 2);
		$FileName = md5($userid).".JPG";
		//echo $FileName;
		if($converted)
			$result = $storage->write($domain,$FileName,$contain);
		if($result && $converted){
			if($link){
				$query = "UPDATE `user` SET `avatar`='$FileName' WHERE `user_id`='$userid'";
				$result = mysql_query($query);
				if($result){
					$response["function"] = "save_photo";
					$response["resultCode"]= 0;
				}else{
					$response["function"] = "save_photo";
				}
				
			}
		}

		echo json_encode($response);
	}
}

function img_convert_to_jpg(&$contain) {
	
	sae_write("tmp", $contain);
	$img_file = SAE_TMP_PATH."/"."tmp";
	//sae_debug($img_file);
	if (($img_info = getimagesize($img_file)) === FALSE)
		return false;

	switch ($img_info[2]) {
		case IMAGETYPE_GIF  : $src = imagecreatefromgif($img_file);  break;
		case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img_file); break;
		case IMAGETYPE_PNG  : $src = imagecreatefrompng($img_file);  break;
		default : return false; //die("Unknown filetype");
	}

	ob_start();
	imagejpeg($src);
	$contain =  ob_get_contents();
	ob_end_clean();
	return true;
}

function sae_write($file, $content) {
	file_put_contents(SAE_TMP_PATH."/".$file, $content);
}

function sae_read($file) {
	return file_get_contents(SAE_TMP_PATH."/".$file);
}
?>
