<?php
require 'utilities.php';
$is_legal =  $is_legal = IfStringExist(); // 检测post的合法性
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
		$query1="insert into user_defined(user_id,ex_key,ex_value) values('$user_id','$info_key','$info_value')";
		$query="UPDATE `user` SET `$info_key`='$info_value' WHERE `user_id`='$user_id'";
        $result = mysql_query($query);
		if($result){
			$return["function"] = "add_info";
            $return["resultCode"] = 0;
            echo json_encode($return);
		}
		else{
			$result = mysql_query($query1);
            if($result){
                $return["function"] = "add_info";
                $return["resultCode"] = 0;
                echo json_encode($return);
                break;
            }
            $return["function"] = "add_info";
            $return["resultCode"] = -12;
			echo json_encode($return);
		}
        }
}
}
?>
