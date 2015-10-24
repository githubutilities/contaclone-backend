<?php

/*
 * @author	ccx
 * @date	2014/10/31
 * 登录成功时，返回LOGIN_SUCCEED的常量
 */
define("LOGIN_SUCCEED", 0);
define("USER_NOT_EXIST", 1);
define("PASSWORD_INCORRECT", 2);
define("LOGIN_FAILED", 3);
define("Check_Through",4);
define("Check_Failed",5);
   
function login($userid, $password) {

   if($userid == "" )
     return LOGIN_FAILED;    //防止空字符串登陆的影响--by 李鹏飞 2014.11.08
   else{
    $link = connect_db();
    if ($link) {
        $sql = sprintf( "SELECT password FROM user WHERE user_id='%s'",
                       mysql_real_escape_string($userid));
        $result = mysql_query($sql);

        if ($result) {
            $row = mysql_fetch_assoc($result);

            if ($row['password'] == $password) {
                return LOGIN_SUCCEED;
            } else return PASSWORD_INCORRECT;
        } else {
            //user not existed
            return USER_NOT_EXIST;
        }
    } else {
        //can not connect to db
        return LOGIN_FAILED;
    }
  }
}
/*
 * @author	ccx
 * @date	2014/10/31
 * 用于链接数据库，如果返回值是NULL时，链接数据库出现问题
 */
function connect_db() {
    //connect to db
    $link = NULL;
    if (defined('SAE_MYSQL_HOST_M')) {
        $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    } else {
        $link = mysql_connect("localhost","root","");
    }

    if ($link) {
        $is_selected = mysql_select_db('app_contaclone', $link);

        if ($is_selected) {
            mysql_query("SET NAMES 'utf8'");
        } else {
            if (defined('SAE_MYSQL_HOST_M')) 
                sae_debug("can not select database app_contaclone");
        }
    } else {
        if (defined('SAE_MYSQL_HOST_M')) 
            sae_debug("can not connect to database server");
    }
    return $link;
}
/*
 * @author	xavier张某人
 * @date	2014/11/06
 * 对$_POST数据进行检查，如果为出现敏感词汇则返回Check_Through,若检查失败则返回Check_Failed
 */
function IfStringExist(){
        foreach($_POST as $post_key => $post_string){
                if(preg_match('/select|insert|update|delete|union|into|load_file|outfile|\'|\*|\/\*|..\/|.\/|;/i',$post_string)){
                        return Check_Failed;
                }
        return Check_Through;
        }
}

?>