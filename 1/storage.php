<?php
 echo "get";
 $storage = new SaeStorage();
 $domain = 'img';
 $content = $_POST["jpg"];
 echo $content;
 $Name = "test.jpg";
 $result = $storage->write($domain,$Name,$content);
 echo $result;
?>
