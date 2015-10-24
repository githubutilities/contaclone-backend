<?php
require 'utilities.php';

$userid = $_POST["user_id"];
$password = $_POST["password"];
$circleid = $_POST["circle_id"];
$info_key = $_POST["info_key"];
$info_value = $_POST["info_value"];
    
	   if ( login($userid, $password) == LOGIN_SUCCEED){
	      $link = connect_db();
		  $sql_modify_circle = "UPDATE `circle` SET  `$info_key` = '$info_value'  WHERE  `circle_id` = '$circleid' ";   //更改信息
		  $result1 = mysql_query($sql_modify_circle);
		   
		     if($result1){
		        $json= '{"function": "modifycircle",“resultCode”:0}';
				echo $json; 			    				 				
		      }
		      else{ 
		        echo '{"function": "modifycircle",“resultCode”:-9}';    //数据库更改失败
	            die("error！")；
		      }	   
		}
	   else 
	   echo '{"function": "joincircle",“resultCode": -8}';  //用户密码验证错误
 
?>