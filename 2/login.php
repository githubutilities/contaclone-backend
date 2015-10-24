<?php
require_once 'utilities.php';

$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
	SQL_injection_error();
}else{

	$username = $_POST["username"];
	$password = $_POST["password"];
	$registration_id = $_POST["registrationID"];

//sae_debug("USERNAME ".$username);
//sae_debug("PASSWORD ".$password);
//sae_debug("registration_id".$registration_id);
	$link = connect_db();
	$userid = "";
	if ($link) {
		$sql = sprintf( "SELECT user_id FROM user WHERE username='%s'",
			mysql_real_escape_string($username));
		$result = mysql_query($sql);

		if ($result) {
			$row = mysql_fetch_assoc($result);
			$userid = $row['user_id'];
		}
	}

	$ret_code = -2;
	if("" != $userid) {
		$status = login($userid, $password);
		if (LOGIN_SUCCEED == $status) {
			$sql = sprintf( "UPDATE user
								SET registration_id='%s'
								WHERE user_id='%s'", $registration_id, $userid);
			//sae_debug($sql);
			mysql_query($sql);
			$ret_code = 0;
		} else if(USER_NOT_EXIST == $status) {
			$ret_code = -2;
		} else if(PASSWORD_INCORRECT == $status) {
			$ret_code = -1;
		}
	}

	$ret["resultCode"] = $ret_code;
	$ret["function"] = "login";
	if(0 == $ret_code) $ret["user_id"] = $userid;

	echo json_encode($ret);
}
?>