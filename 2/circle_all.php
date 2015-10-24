<?php

require_once 'utilities.php';
require_once 'CLog.php';
$is_legal = IfStringExist(); // 检测post的合法性
if( Check_Failed == $is_legal){
	SQL_injection_error();
}else{
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
					$row['red_dot'] = getRedDot($userid, $row['circle_id']);
					array_push($data, $row);
				}
				$storage = new SaeStorage();
				$domain = "img";
//				echo var_dump($data);
//				echo "</br>";
				foreach($data as $key=>$value){
//					echo $value["circle_logo"];
//					echo "</br>";
					$FileName = $value["circle_logo"];
//					echo $FileName . "</br>";
					$img = $storage->read($domain,$FileName);

					$imageUrl = $storage->getUrl($domain, $FileName);

					ob_start();
					imagejpeg(imagecreatefromstring($img));
					$contents =  ob_get_contents();
					ob_end_clean();

//echo "CONTENT" . base64_encode($contents) . "<br>";
//echo "IMG" .base64_encode($contents) . "<br>";

//echo "<img src='data:image/png;base64,".base64_encode($contents)."' /><br>";

					$data[$key]["circle_logo"] = base64_encode($contents);
					//imagedestroy($contents);
					//file_get_contents($imageUrl);
						//base64_encode(base64_decode($img));
				}
				$ret["params"] = $data;
				$ret["resultCode"] = 0;

				//$data['red_dot'] = getRedDot($userid);
				//$ret["params"] = $data;
				//$ret["resultCode"] = 0;
				//var_dump($data[0]["circle_logo"]); echo "</br>";
			}
			//CLog::debug("REDDOT", json_encode($ret));
			//echo json_encode($ret);
			//CLog::debug("RedDOT", "NO");
			
			echo json_encode($ret);
		}
	} else {

	}
}
function getRedDot($userid, $circle_id) {
	$sql = sprintf("SELECT relative.last_browse, circle.last_change
				FROM relative, circle 
				WHERE relative.user_id='%s'
					AND relative.circle_id='%s'
					AND relative.circle_id=circle.circle_id",
				$userid, $circle_id);
	$result = mysql_query($sql);
	//CLog::debug("RedDOT", $sql);
	//CLog::debug("REDDOT", json_encode($result));
	if ($result) {
		//CLog::debug("push pending", $json);
		$row = mysql_fetch_assoc($result);
		$circle_time = strtotime($row['last_change']);
		$visit_time = strtotime($row['last_browse']);

//UPDATE `user` SET `$info_key`='$info_value' WHERE `user_id`='$user_id'
		date_default_timezone_set('PRC');
		$current_time = date("Y-m-d H:i:s");
		$sql = sprintf("UPDATE relative, circle
							SET relative.last_browse='%s'
							WHERE relative.user_id='%s'", $current_time, $userid);
		mysql_query($sql);
		//CLog::debug("RedDOT", $sql);
		if ($visit_time < $circle_time) {
			return 1;
		} else return 0;
	}
}
?>
