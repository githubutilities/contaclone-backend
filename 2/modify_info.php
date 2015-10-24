<?php
require_once 'Notification.php';
require_once 'CLog.php';
require_once 'utilities.php';
$is_legal = IfStringExist(); // 检测post的合法性

if(Check_Failed == $is_legal){
 SQL_injection_error();
}else{
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
				pushNotification($user_id);
			}
			else{

				$result2 = mysql_query($query2);
				if($result2){ 
					$response["function"] = "modify_info";
		            $response["resultCode"] = 0;
				    echo json_encode($response);
				    pushNotification($user_id);
					break;
				}
				$response["function"] = "modify_info";
		       		$response["resultCode"] = -11;
		        echo json_encode($response);
			}
	    }
	}
	if($response["resultCode"] == 0){
		date_default_timezone_set('PRC');
		$current_time = date("Y-m-d H:i:s");
		$query = "UPDATE `circle` SET `last_change`='$current_time' WHERE `user_id`='$user_id'";
		$result = mysql_query($query);
		$query = "UPDATE `relative` SET `last_browse`='$current_time' WHERE `user_id`='$user_id'";
		mysql_query($query);
	}

}
function pushNotification($user_id) {
	$link = connect_db();
	//sae_debug("connecting");
	if($link){
		//sae_debug("connected");
		$sql = sprintf("SELECT DISTINCT circle_id
							FROM relative
							WHERE user_id='%s'",
							mysql_real_escape_string($user_id));
		$result = mysql_query($sql);
		//sae_debug($sql);
		//sae_debug(json_encode($result));
		//CLog::debug(json_encode($result)."***", $sql);
		if ($result) {
			$ids = array();
			//CLog::debug(json_encode($result), "NOT NULL");
			while ($row = mysql_fetch_assoc($result)) {
				//Notification::push($row['circle_id']);
				$sql = sprintf("SELECT DISTINCT registration_id
									FROM relative, user
									WHERE circle_id='%s'
										AND relative.user_id!='%s'
										AND relative.user_id=user.user_id",
									$row['circle_id'], $user_id);
				$res = mysql_query($sql);
				while ($registration_id = mysql_fetch_assoc($res)) {
					if (!empty($registration_id['registration_id']))
						array_push($ids, $registration_id['registration_id']);
				}
				//sae_debug($sql);
			}
			$ids = array_unique($ids);
//sae_debug(json_encode($ids));
			if(count($ids)) Notification::push($ids);
		}
	}

}

?>
