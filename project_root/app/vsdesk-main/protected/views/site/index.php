<?php

$arr = array(
    'model' => $model,
    'news' => $news,
    'problems' => $problems,
    'username' => $username,
    'faq' => $faq,
    'data6' => $data6,
    'data7' => $data7,
    'know' => $know,
    'graph2' => $graph2,
    'data5' => $data5,
    'name' => $name,
    'json' => $json,
);
$this->renderPartial('_admin', $arr);