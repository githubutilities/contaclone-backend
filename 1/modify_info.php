<?php
require 'utilities.php';
$json1 = $_POST['function'];
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$info_key = $_POST['info_key'];
$info_value = $_POST['info_value'];
$result = 0;
if (LOGIN_SUCCEED == login($user_id, $password)){
	$link = connect_db();
	if($link){
	$query1="UPDATE `user` SET `$info_key`='$info_value' WHERE `user_id`='$user_id'";
	$query2="UPDATE `user_defined` SET `$info_key`='$info_value' WHERE `user_id`='$user_id'";//自定义字段
	$result = mysql_query($query1);
	if($result){
		$response["function"] = "modify_info";
        $response["resultCode"] = 0;
		echo json_encode($response);
	}
	else{
		$result2 = mysql_query($query2);
		if($result2){ 
			$response["function"] = "modify_info";
            $response["resultCode"] = 0;
		    echo json_encode($response);
			break;
		 }
		$response["function"] = "modify_info";
        $response["resultCode"] = -11;
        echo json_encode($response);
	}
    }
}//未加入身份验证
?>
