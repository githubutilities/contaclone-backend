<?php
require 'utilities.php';
$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
 SQL_injection_error();
}else{

$userid = $_POST["user_id"];
$password = $_POST["password"];
$circle_id = $_POST["circle_id"];
$theuserid = $_POST["the_user_id"];

if (LOGIN_SUCCEED == login($userid, $password)) {
	$link = connect_db();
	if ($link) {
		$vis = get_vis($theuserid, $circle_id);
        $val = get_val($theuserid, $circle_id);
        $ret = get_ret($vis, $val, $theuserid, $circle_id);
        echo json_encode($ret);
        //sae_debug(json_encode($ret));
	}
} 
}
// find non-costomized vis
function get_vis($theuserid, $circle_id) {
	$sql = sprintf("SELECT `birthday_visibility`,`mobile_visibility`,`qq_visibility`,
    					`email_visibility`,`weibo_visibility`,`wechat_visibility`,`renren_visibility`
					FROM relative
					WHERE user_id = '%s' AND circle_id = '%s'",
			mysql_real_escape_string($theuserid),
			mysql_real_escape_string($circle_id));
    
	$result = mysql_query($sql);
    
    if ($result)
        $ret = mysql_fetch_assoc($result);

    //formatting ret
    foreach(array_keys($ret) as $key){
        $value = $ret[$key];
        unset($ret[$key]);
        $key = substr($key, 0, strpos($key, "_"));
        $ret[$key] = $value;
    }
    $ret["name"] = 1;
    $ret["avatar"] = 1;

    return $ret;
}

// find non-costomized val
function get_val($theuserid, $circle_id) {
    $sql = sprintf("SELECT avatar, name, birthday, mobile, qq, email, weibo, wechat, renren
    		FROM user
            WHERE user_id = '%s'",
                   mysql_real_escape_string($theuserid));
    $result = mysql_query($sql);
    
    if($result)
        $ret = mysql_fetch_assoc($result);

    //encoding avatar into base64
    $ret["avatar"] = get_avatar_in_base64($ret["avatar"]);
    return $ret;
}

function get_avatar_in_base64($filename) {
    $storage = new SaeStorage();
    $domain = "img";
    $img = $storage->read($domain,$filename);

    // ob_start();
    // imagejpeg(imagecreatefromstring($img));
    // $img =  ob_get_contents();
    // ob_end_clean();
//echo $filename . "<br>";
//echo "<img src='data:image/png;base64,".base64_encode($img)."' /><br>";
    return base64_encode($img);
}

function get_ret($vis, $val, $theuserid, $circle_id) {

    $data = array();
    foreach(array_keys($vis) as $key) {
        if($vis[$key] != 0 && $val[$key] != NULL) {
            $data[$key] = $val[$key];
        }
    }

    put_costom_val($data, $theuserid, $circle_id);

    $data_arr = array();
    array_push($data_arr, $data);
    $ret['function'] = "find_person_detail";
    $ret['returnCode'] = 0;
    $ret['params'] = $data_arr;
    return $ret;
}


// put costomized value into data
function put_costom_val(&$data, $theuserid, $circle_id) {
	$sql = sprintf("SELECT ex_key, ex_value
    	FROM user_defined, user_defined_visi
    	WHERE user_defined_visi.user_id='%s'
    		AND user_defined_visi.circle_id='%s'
    		AND user_defined_visi.ex_message_visibility IS NOT NULL
    		AND user_defined_visi.ex_message_id=user_defined.ex_message_id",
    		mysql_real_escape_string($theuserid),
    		mysql_real_escape_string($circle_id));
	$result = mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
		$data[$row['ex_key']] = $row['ex_value'];
	}
}
?>