<?php
require 'utilities.php';

$userid = $_POST["user_id"];
$password = $_POST["password"];
$circle_id = $_POST["circle_id"];

if (LOGIN_SUCCEED == login($userid, $password)) {
	$link = connect_db();
	if ($link) {
		if (qualify($userid, $circle_id)) {
			//connect two tables and then query
			$sql = sprintf("SELECT relative.user_id, username, name, note, mobile
				FROM relative, user
				WHERE circle_id='%s' AND relative.user_id=user.user_id",
				mysql_real_escape_string($circle_id));
			$result = mysql_query($sql);

			$ret["function"] = "find_people_in_circle";
			$data = array();
			$ret["resultCode"] = -8;
			if ($result) {
				while ($row = mysql_fetch_assoc($result)) {
					array_push($data, $row);
				}
				$ret["params"] = $data;
				$ret["resultCode"] = 0;
			}
			echo json_encode($ret);
		}
	}
} 

function qualify($userid, $circle_id) {
	$sql = sprintf("SELECT circle_id
		FROM relative
		WHERE user_id='%s' AND circle_id='%s'", 
		mysql_real_escape_string($userid), 
		mysql_real_escape_string($circle_id));
	$result = mysql_query($sql);
	if ($result) {
        $row = mysql_fetch_assoc($result);

        if ($row['circle_id'] == $circle_id) return true;
        else return false;
    }
}
?>