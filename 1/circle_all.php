<?php
require 'utilities.php';

$userid = $_POST["user_id"];
$password = $_POST["password"];

if (LOGIN_SUCCEED == login($userid, $password)) {
	$link = connect_db();
	if ($link) {
		$sql = sprintf("SELECT circle.circle_id, circle_name, circle_logo,circle_passw
			FROM relative, circle 
			WHERE relative.user_id='%s'
				AND relative.circle_id=circle.circle_id",
			mysql_real_escape_string($userid));
		$result = mysql_query($sql);
		
		$ret["function"] = "find_all_mycircle";
		$ret["resultCode"] = -8;

		if ($result) {
			$data = array();
			while ($row = mysql_fetch_assoc($result)) {
				array_push($data, $row);
			}
			$ret["params"] = $data;
			$ret["resultCode"] = 0;
		}
		echo json_encode($ret);
	}
} else {
    
}
?>