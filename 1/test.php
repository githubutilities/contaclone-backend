<?php 

$json1 = $_POST['function'];  
$json2 = $_POST['username'];
$json3 = $_POST['password'];
$json4 = $_POST['name'];




   if(!strcmp($json1,"register")){
   $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);  
       if (!$link){
		    die('Could not connect: ' . mysql_error());
	   }
     mysql_select_db('app_contaclone');    
     mysql_query("SET NAMES 'utf8'");
     $sql="insert into user(user_id,username,password,name) values(null,'$json2','$json3','$json4')";
    $is_insert = mysql_query($sql);
       if($is_insert){
       $sql2="SELECT name FROM `user` where user_id = 1";
           $is_select = mysql_query($sql2);//发送sql语句
           $user_id = mysql_fetch_row($is_select);
           $user_id_str = $user_id[0];
         mysql_close();//关闭连接
           
           $return_string = '{
    "function": "register",
    "code": "103",   
     "userid":'.'"'.$user_id_str.'"'.'}'; 
        
           
           
           echo $return_string;
       }
           
   }



   else{
    echo 'unknown error!';
   }

?>