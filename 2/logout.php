<?php
require_once 'utilities.php';

$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
	SQL_injection_error();
}else{
	$user_id = $_POST["user_id"];
	$password = $_POST["password"];

	echo json_encode(logout($user_id, $password));
}

function logout($user_id, $password) {
	$ret['resultCode'] = -8;

	$status = login($user_id, $password);
	if (LOGIN_SUCCEED == $status) {
		$sql = sprintf( "UPDATE user
							SET registration_id=''
							WHERE user_id='%s'", $user_id);
		mysql_query($sql);
		$ret['resultCode'] = 0;
	} 
	return $ret;
}