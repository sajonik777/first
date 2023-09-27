<?php
$file = dirname(__FILE__) . '/dbconfig.inc';
$content = fopen($file, 'r');
$arr = (array)json_decode(fgets($content));
//$arr['enableProfiling'] = true;
//$arr['enableParamLogging'] = true;
return ($arr);
?>
