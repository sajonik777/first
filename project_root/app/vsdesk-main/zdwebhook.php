<?php

if (isset($_GET['zd_echo'])) {
    exit($_GET['zd_echo']);
}
$yii = __DIR__ . '/protected/vendors/yii/yii.php';
$config = __DIR__ . '/protected/config/main.php';
require_once($yii);
Yii::createWebApplication($config);

ini_set('display_errors', 'Off');
error_reporting(0);

if ('' == ini_get('date.timezone')) {
    date_default_timezone_set(Yii::app()->params['timezone']);
}

$push = true;


$log = fopen('update_log.txt', 'a');
$str = $_POST['caller_id'];
$time = date('Y-m-d H:i:s');
fwrite($log, "$str ($time)\n");
fclose($log);

if (isset($_POST)) {
    if ($_POST['event'] == 'NOTIFY_START' OR $_POST['event'] == 'NOTIFY_INTERNAL') {
        $uid = $_POST['pbx_call_id'];
        $dialer = $_POST['caller_id'];
        $dialed = $_POST['called_did'];
        if(!empty($dialer) AND $dialer !== NULL){
            $connection = Yii::app()->db;
            $user_sql = 'SELECT * FROM `CUsers` `t` WHERE `t`.`intphone` LIKE "' . $dialer . '" LIMIT 1';
            $user = $connection->createCommand($user_sql)->queryRow();
            $user_sql2 = 'SELECT * FROM `CUsers` `t` WHERE `t`.`Phone` LIKE "' . $dialer . '" LIMIT 1';
            $user2 = $connection->createCommand($user_sql2)->queryRow();
            $user_sql3 = 'SELECT * FROM `CUsers` `t` WHERE `t`.`mobile` LIKE "' . $dialer . '" LIMIT 1';
            $user3 = $connection->createCommand($user_sql3)->queryRow();
            unset($model);
            $model = new Calls;
            //check user in local database to show name and company
            if (isset($user) AND !empty($user)) {
                $model->dialer = $user['Username'];
                $model->dialer_name = $user['fullname'];
                $caller = $user['fullname'] ? $user['fullname'] : $dialer;
                $company = $user['company'] ? $user['company'] : "Не указано";
                $model->dr_company = $company;
            }
            if (isset($user2) AND !empty($user2)) {
                $model->dialer = $user2['Username'];
                $model->dialer_name = $user2['fullname'];
                $caller = $user2['fullname'] ? $user2['fullname'] : $dialer;
                $company = $user2['company'] ? $user2['company'] : "Не указано";
                $model->dr_company = $company;
            }
            if (isset($user3) AND !empty($user3)) {
                $model->dialer = $user3['Username'];
                $model->dialer_name = $user3['fullname'];
                $caller = $user3['fullname'] ? $user3['fullname'] : $dialer;
                $company = $user3['company'] ? $user3['company'] : "Не указано";
                $model->dr_company = $company;
            }
            $model->dr_number = $dialer;
            $manager = CUsers::model()->findByAttributes(array('intphone' => $dialed));

            //check manager number in database to route alert or don't write the call log
            $call = Calls::model()->findByAttributes(array('uniqid' => $uid));
            if (isset($manager) AND !isset($call)) {
                $model->dialed = $manager->Username;
                $model->dialed_name = $manager->fullname;
                $model->dd_number = $dialed;
                $model->uniqid = $uid;
                $model->duniqid = $uid;
                $model->date = date("Y-m-d H:i:s");
                if ($model->save(false)) {
                    if ($push == true) {
                        $caller_id = $caller ? $caller : $dialer;
                        $company_id =  $company ? $company : "Не указано";
                        $message = "Входящий звонок!\r\nВам звонит: " . $caller_id . "\r\nКомпания: " . $company_id;
                        $url = Yii::app()->params->homeUrl . "/calls/" . $model->id;
                        $manager->pushMessage($message, $url);
                    }
                }
            }
        }
    }
    if ($_POST['event'] == 'NOTIFY_ANSWER') {
        $uid = $_POST['pbx_call_id'];
        $call = Calls::model()->findByAttributes(array('uniqid' => $uid));
        if (isset($call)) {
            Calls::model()->updateByPk($call->id, array('adate' => date("Y-m-d H:i:s"), 'status' => 'Answered'));
        }
    }
    if ($_POST['event'] == 'NOTIFY_END') {
        $uid = $_POST['pbx_call_id'];
        $calls = Calls::model()->findAllByAttributes(array('uniqid' => $uid));
        foreach ($calls as $call) {
            if (isset($call) AND empty($call->adate)) {
                Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'status' => 'Hangup'));
            } elseif (isset($call) AND !empty($call->adate)) {
                Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s")));
            }
        }
    }
}