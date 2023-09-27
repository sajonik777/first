<?php

class MsgController extends Controller
{
    public function ActionDelete()
    {
        if (isset($_POST['id'])){
            $last_id = (int)$_POST['id'];
            Alerts::model()->deleteByPk($last_id);
        }

    }

    public function ActionDeletecall()
    {
        if (isset($_POST['id'])){
            $last_id = (int)$_POST['id'];
            Calls::model()->updateByPk($last_id, array('shown' => 1));
        }

    }

    public function ActionRelease()
    {
        if (isset($_POST['id'])){
            $id = (int)$_POST['id'];
            Request::model()->updateByPk($id, array('update_by' => NULL));
        }

    }

    public function ActionDeleteall()
    {
        Alerts::model()->deleteAll('`user` = :user', array(':user' => Yii::app()->user->name,));
    }

    public function ActionComet()
    {
        // number of second the script allowed to run. setting to 6 minutes
        $limit = 30;
        $time = time();
        $user = Yii::app()->user->name;
        $connection = Yii::app()->db;

        // getting last loaded value
        $last_id = (int)$_GET['id'];

        // just to be sure that script will be killed
        set_time_limit($limit + 5);
        function escape($str)
        {
            return str_replace('"', '\"', $str);
        }

        // цикл, проверяющий новые сообщения каждые 5 секунд
        while (time() - $time < $limit) {
            $alerts_sql = 'SELECT * FROM `alerts` `t` WHERE `t`.`id`>' . $last_id . ' AND `t`.`user`="' . $user . '" ORDER BY `id` ASC';
            $alerts = $connection->createCommand($alerts_sql)->queryAll();

            $calls_sql = 'SELECT * FROM `calls` `t` WHERE `t`.`id`>' . $last_id . ' AND `t`.`dialed`="' . $user . '" AND `t`.`shown` IS NULL ORDER BY `id` ASC';
            $calls = $connection->createCommand($calls_sql)->queryAll();

            if (count($alerts)) {
                foreach ($alerts as $item) {
                    // пишем js-скрипт, который выполнится у клиента
                    echo 'self.putMessage("alert","' . date('d.m.Y H:i:s') . '","' . $item['id'] . '","' . escape($item['name']) . '","' . escape($item['user']) . '","' . escape($item['message']) . '");';
                }
                // выбрасываем все данные и выходим, чтобы клиент смог их обработать
                flush();
                exit;
            }
            if (count($calls)) {
                foreach ($calls as $item) {
                    // пишем js-скрипт, который выполнится у клиента
                    echo 'self.putMessage("call","' . date('d.m.Y H:i:s') . '","' . $item['id'] . '","' . escape($item['dialer_name']) . '","' . escape($item['dr_number']) . '","' . escape($item['dr_company']) . '","' . escape($item['dialer']) . '");';
                }
                // выбрасываем все данные и выходим, чтобы клиент смог их обработать
                flush();
                exit;
            }
            // если данных нет - ждём 5 секунд
            sleep(5);
        }
    }
}
