<?php
require 'utilities.php';
$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
 SQL_injection_error();
}else{

$userid = $_POST["user_id"];
$password = $_POST["password"];
$circleid = $_POST["circle_id"];

    
	   if ( LOGIN_SUCCEED == login($userid, $password)){
	      $link = connect_db();
		  $sql_quit_circle = "DELETE FROM `relative` WHERE `circle_id`= '$circleid' AND `user_id`= '$userid ' ";   //删除数据库语句
		  $result1 = mysql_query($sql_quit_circle);
		   
		     if($result1){
		        $json= '{"function": "quitcircle",“resultCode”:0}';
				echo $json; 			    				 				
		      }
		      else 
		        echo '{"function": "quitcircle",“resultCode”:-6}';    //数据库删除失败
	           
		      	   
		}
	   else 
	   echo '{"function": "joincircle",“resultCode": -8}';  //用户密码验证错误
}
?>