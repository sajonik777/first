<?php
$file = dirname(__FILE__) . '/request.inc';
$content = file_get_contents($file);
$arr = unserialize(base64_decode($content));
return CMap::mergeArray(
    $arr,
    array(
        'salt' => 'P@bl0',
        'someOption' => true,
    )
);
?>