<?php
require 'utilities.php';
$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
 SQL_injection_error();
}else{

$json1 = $_POST['function'];
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$info_key = $_POST['info_key'];//文档里是info_1
$result = 0;
$result2 = 0;
if (LOGIN_SUCCEED == login($user_id, $password)){
	$link = connect_db(); 
	if($link){
		
	
	$query1="UPDATE `user` SET `$info_key`='' WHERE `user_id`='$user_id'";//将删除字段定义为空
    $query2="DELETE FROM `user_defined` WHERE `user_id`='$user_id AND`ex_key`='$info_key'";//自定义字段，删除后直接在mysql中删除
	$result = mysql_query($query1);
	if($result){
		$response["function"] = "delete_info";
        $response["resultCode"] =  0;
        echo json_encode($response);
     }
	else{
		$result2 = mysql_query($query2);
		if($result2){ 
		$response["function"] = "delete_info";
        $response["resultCode"] =  0;
        echo json_encode($response);

			break;
		 }
		$response["function"] = "delete_info";
        $response["resultCode"] =  -10;
        echo json_encode($response);

		}
        }
}//未加入身份验证
}
?>
