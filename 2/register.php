<?php 


$json1 = $_POST['function'];  
$json2 = $_POST['username'];
$json3 = $_POST['password'];
$json4 = $_POST['name'];

   if($json1 == "register"){
   $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);  
       
	   if (!$link){
	        $return_string = '{"function": "register","resultCode":-8}';
	        echo $return_string;   //返回错误码-8，无法连接数据库
		    die('Could not connect: ' . mysql_error());
	   }
        
		mysql_select_db('app_contaclone');    
        mysql_query("SET NAMES 'utf8'");
        $sql="insert into user(user_id,username,password,name) values(null,'$json2','$json3','$json4')";
        $is_insert = mysql_query($sql);           
	   if($is_insert){          
              $return_string = '{
             "function": "register",
             "resultCode":0 }';         
             echo $return_string; //返回成功的json
		      mysql_close();//关闭连接
        }
		else{
		      $return_string = '{
             "function": "register",
             "resultCode":-3 }';         
             echo $return_string; //插入失败，返回错误码-3
			 mysql_close();//关闭连接
		}
           
   }

   else{
    echo '{"function": "register","resultCode":-8}';  //未知错误
   }

?>