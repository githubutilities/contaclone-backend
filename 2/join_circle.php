<?php
require 'utilities.php';
$is_legal = IfStringExist(); // 检测post的合法性
if(Check_Failed == $is_legal){
 SQL_injection_error();
}else{

$userid = $_POST["user_id"];
$password = $_POST["password"];
$circleid = $_POST["circle_id"];
$circlepassword = $_POST["circle_passw"];
    
	   if ( LOGIN_SUCCEED == login($userid, $password)){
	      $link = connect_db();
		  $sql_get_circle_pass = "SELECT circle_passw FROM `circle` where circle_id = '$circleid' ";    //查询圈子密码
		  $result1 = mysql_query($sql_get_circle_pass);
		   
		   if($result1){
		    $result1_array = mysql_fetch_row($result1);
		     $passw_in_db = $result1_array[0];   //数据库中的圈子密码
			     if($passw_in_db == $circlepassword){  
                  $sql_insert_relative = "insert into relative(circle_id,user_id) values('$circleid','$userid')"; //插入 关系表语句
					$sql_update_people_amount = "UPDATE  `circle` SET  `people_amount` = people_amount+1 WHERE  `circle_id` ='$circleid'"; //人数加一
	                $result2 = mysql_query($sql_insert_relative);
			        $update = mysql_query($sql_update_people_amount);
                     if($result2){
                         
                          if(!($privateinfo == "")){    //用户设置了某些字段的可见性
				              $string = substr($privateinfo, 0, -1);  //去除传过来字符串的最后一个"/"
				              $array = explode("/",$string);  
				              foreach($array as $values){
                               $content = $values."_visibility";  //转换成数据库对应的可见性字段名
                                $sql_update_relative = "UPDATE  `relative` SET  `$content` =  '0'
								WHERE  `relative`.`circle_id` =$result2_array[0] AND  `relative`.`user_id` =$userid ";
								mysql_query($sql_update_relative);//更新关系表
                              }
				           }
                         
                      $array["function"] = "joincircle";
				       $array["resultCode"] = 0;
				       $params = array();
				       $sql_query = "SELECT circle_id,circle_name,circle_logo FROM `circle` where circle_id = '$circleid'" ;
				       $result3 = mysql_query($sql_query);
				       $result3_array = mysql_fetch_row($result3);
				       $params["circle_id"] = $result3_array[0];
				       $params["circle_name"] = $result3_array[1];
				       $params["circle_logo"] = $result3_array[2];
				       $array["params"] = $params;
				       $json = json_encode($array); //返回的json
				       echo $json; 
                     }
                     else
                      echo '{"function": "joincircle","resultCode": -8}';   //写入数据库失败
                 }
                 else 
                   echo '{"function": "joincircle","resultCode": -5}';    //密码错误
                     
           }
            else
             echo '{"function": "joincircle","resultCode":-4}';
		  
	   }
       else 
	   echo '{"function": "joincircle","resultCode": -8}';
} 
?>