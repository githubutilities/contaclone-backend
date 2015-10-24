<?php
//防注入，过滤字符，包括sql和php关键字

function IfStringExist($Str){
	if(preg_match('/select|insert|update|delete|union|into|load_file|outfile|\'|\*|\/\*|..\/|.\/|;/i',$Str)){
		return ture;
	}
	return false;
}

?>
