<?php
$file = dirname(__FILE__) . '/sms.inc';
$content = file_get_contents($file);
$arr = unserialize(base64_decode($content));
return CMap::mergeArray(
    array(
        'class' => 'application.extensions.sms.src.smsc',
    ),
    array(
        'login'     => $arr['smsuser'],  // login
        'password'   =>  $arr['smspassword'], // plain password or lowercase password MD5-hash
        'post' => true, // use http POST method
        'https' => true,    // use secure HTTPS connection
        'charset' => 'utf-8',   // charset: windows-1251, koi8-r or utf-8 (default)
        'debug' => false,    // debug mode
        'format' => (int)$arr['smsformat'],
        'sender' => $arr['smssender'],
    )
); ?>