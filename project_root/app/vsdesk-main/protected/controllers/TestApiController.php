<?php

class TestApiController extends Controller
{

    public function actionIndex()
    {
      $host = 'http://192.168.1.65/api/requests/';
      $username = 'admin';
      $password = 'admin';

      $postFields = http_build_query(['service_id' => 3, 'CUsers_id' => 'admin', 'Status' => 'Открыта',
                             'ZayavCategory_id' => 'Заявка через API', 'Priority' => 'Низкий',
                            'Name' => 'Название', 'Content' => 'текст заявки']);
      $process = curl_init($host);
      curl_setopt($process, CURLOPT_HEADER, 1);
      curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
      curl_setopt($process, CURLOPT_TIMEOUT, 30);
      curl_setopt($process, CURLOPT_POST, 1);
      curl_setopt($process, CURLOPT_POSTFIELDS, $postFields);
      curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
      $postFields = curl_exec($process);
      curl_close($process);
      echo $postFields;
    }

}
