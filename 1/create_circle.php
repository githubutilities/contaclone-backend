<?php
require 'utilities.php';

$userid = $_POST["user_id"];
$password = $_POST["password"];
$circlename = $_POST["circle_name"];
$circlelogo = $_POST["circle_logo"];
$circlepassw = $_POST["circle_passw"];

date_default_timezone_set('PRC');
 $current_time = date("Y-m-d H:i:s"); //当前时间
    
	   if (LOGIN_SUCCEED == login($userid, $password)){
	      $link = connect_db();
		  $sql_create_circle = "insert into circle (circle_name,circle_passw,user_id,circle_date,circle_logo) 
		  values('$circlename','$circlepassw','$userid','$current_time','$circlelogo') ";    //写入数据库语句
           
		  $result1 = mysql_query($sql_create_circle);
		   
		     if($result1){
		        $array["function"] = "createcircle";
			    $array["resultCode"] = 0;
				$params = array();
				$sql_query = "SELECT circle_id FROM `circle` where circle_name = '$circlename'" ;
				$result2 = mysql_query($sql_query);
				$result2_array = mysql_fetch_row($result2);
				$params["circle_id"] = $result2_array[0];				       
				$array["params"] = $params;
				$json = json_encode($array); //返回的json
				echo $json; 
                 $sql_update_relative = "insert into relative (circle_id,user_id) 
		  values('$result2_array[0]','$userid') ";
                 mysql_query($sql_update_relative);//插入关系表
		      }
		      else{ 
		            echo '{"function": "createcircle",“resultCode”:-7}';    //数据库写入失败
	               
		      }	   
		}
	   else 
	   echo '{"function": "joincircle",“resultCode": -8}';  //用户密码验证错误
 
?>