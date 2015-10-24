<?php
require 'utilities.php';
$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
 SQL_injection_error();
}else{


$userid = $_POST["user_id"];
$password = $_POST["password"];

if (LOGIN_SUCCEED == login($userid, $password)) {
	$link = connect_db();
	if ($link) {
        $val = get_val($userid);
        //sae_debug(json_encode($val));
        $ret = get_ret($val, $userid);
        echo json_encode($ret);
        //sae_debug(json_encode($ret));
	}
} 
}
// find non-costomized val
function get_val($userid) {
    $sql = sprintf("SELECT name, birthday, mobile, qq, 
                                    email, weibo, wechat, renren, avatar
    		FROM user
            WHERE user_id = '%s'",
                   mysql_real_escape_string($userid));

    $result = mysql_query($sql);
    
    if($result)
        $ret = mysql_fetch_assoc($result);
    //sae_debug($ret['avatar']);
    $ret['avatar'] = get_avatar_in_base64($ret['avatar']);

    return $ret;
}

function get_ret($val, $userid) {
    foreach (array_keys($val) as $key) {
        if($val[$key] != NULL) $data[$key] = $val[$key];
    }

    put_costom_val($data, $userid);

    $data_arr = array();
    array_push($data_arr, $data);
    $ret['function'] = "get_my_detail";
    $ret['returnCode'] = 0;
    $ret['params'] = $data_arr;
    return $ret;
}

// put costomized value into data
function put_costom_val(&$data, $userid) {
	$sql = sprintf("SELECT ex_key, ex_value
    	FROM user_defined, user_defined_visi
    	WHERE user_defined_visi.user_id='%s'
    		AND user_defined_visi.ex_message_id=user_defined.ex_message_id",
    		mysql_real_escape_string($userid));
	$result = mysql_query($sql);

	while ($row = mysql_fetch_assoc($result)) {
		$data[$row['ex_key']] = $row['ex_value'];
	}
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
?>