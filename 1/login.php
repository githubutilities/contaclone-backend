<?php
require 'utilities.php';

$username = $_POST["username"];
$password = $_POST["password"];

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

?>