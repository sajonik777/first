<?php

include ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CrontabManager.php';
include ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CronEntry.php';
include ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CliTool.php';
use php\manager\crontab\CrontabManager;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'main';
        $rewrite = self::get_rewrite();
        $this->render('index', array('rewrite' => $rewrite));
    }

    public function actionCheckdb()
    {
        $link = mysqli_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
        if (!$link) {
            printf("ОШИБКА: %s\n", mysqli_connect_error());
            exit();
        }
        print('ОК');
        mysqli_close($link);
    }

    public function actionWrite()
    {
        if ($this->dbTables()) {
            $this->configWrite();
            $this->finishInstaller();
        }
    }

    static function get_rewrite()
    {
        ob_end_flush();
        ob_start();
        phpinfo(8);
        $inf = ob_get_contents();
        ob_end_clean();
        if (preg_match('/Loaded Modules.*mod_rewrite/i', $inf)) {
            $rewrite = true;
        } else {
            $rewrite = false;
        }
        return $rewrite;
    }

    public function dbTables()
    {
        $ret = true;
        # First test the connection
        $link = mysqli_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
        if (!$link) {
            printf("ОШИБКА: %s\n", mysqli_connect_error());
            $ret = false;
        } else {

            # Select the DB
            $db_selected = mysqli_select_db($link, $_POST['dbname']);
            if (!$db_selected && $_POST['create_database'] === '0') {
                echo 'ОШИБКА: Не найдена база ' . $_POST['dbname'];
                $ret = false;
            } elseif (!$db_selected && $_POST['create_database'] === '1') {
                # Create the DB
                $result = mysqli_query($link, "CREATE DATABASE {$_POST['dbname']}");
                $db_selected = mysqli_select_db($link, $_POST['dbname']);
                if (!$result) {
                    echo 'ОШИБКА: Не удалось создать базу ' . $_POST['dbname'];
                    $ret = false;
                }
            }

            if (isset($_POST['new_install']) and $_POST['new_install'] != '0') {
                $path = ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'database_ru.sql';
            } else {
                //$path = ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'update_schema_2.0.sql';
                $path = NULL;
            }

            if (!file_exists($path)) {
                echo 'ОШИБКА: Не удалось открыть файл базы ' . $path;
                $ret = false;
            }

            $count = 0;
            $errors_count = 0;
            $errors = array();

            $sqlArray = file_get_contents($path);

//            if (mysqli_multi_query($link, $sqlArray)) {
//                do {
//                    /* store first result set */
//                    if ($result = mysqli_store_result($link)) {
//                        //do nothing since there's nothing to handle
//                        mysqli_free_result($result);
//                    }
//                    /* print divider */
//                    if (mysqli_more_results($link)) {
//                        //I just kept this since it seems useful
//                        //try removing and see for yourself
//                        $count++;
//                    }
//                    if (!mysqli_more_results($link)) {
//                        $error_level = error_reporting(0);
//                    }
//                } while (mysqli_next_result($link));
//            }
//            if (mysqli_error($link)) {
//                $errors_count++;
//                $errors[] = mysqli_error($link);
//            }
            //error_reporting($error_level);
            exec('mysql -u' . $_POST['dbuser'] . ' -p' .  $_POST['dbpass'] .' '. $_POST['dbname']. ' < ' . $path);

            $error_string = '';

            # Did we had any errors?
            if (count($errors)) {
                $error_string = "<br /><br />" . sprintf("<h4>Получены следующие ошибки:</h4><br /> %s", implode("<br /><br />", $errors));
            }

            # Redirect
            $path = ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dbconfig.inc';
            $dbnm = $_POST['dbname'];
            $dbhst = $_POST['dbhost'];
            $data = array(
                'charset' => 'utf8',
                'connectionString' => 'mysql:host=' . $dbhst . ';dbname=' . $dbnm . '',
                'username' => $_POST['dbuser'],
                'password' => $_POST['dbpass'],
                //'schemaCachingDuration' => 3600,
            );
            file_put_contents($path, json_encode($data));
            printf("Создание таблиц выполнено. Всего <b>%s</b> запросов было выполнено. Из них <b>%s</b> с ошибками. %s", $count, $errors_count, $error_string);

        }
        return $ret;
    }

    public function configWrite()
    {
        $yii = ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'yii' . DIRECTORY_SEPARATOR . 'yii.php';
        require_once($yii);

        $file = ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'params.inc';
        $content = file_get_contents($file);
        $arr = unserialize(base64_decode($content));
        $model = new ConfigForm();
        $model->setAttributes($arr);

        $config = array(
            'use_rapid_msg' => 0,
            'allow_register' => 1,
            'allow_select_company' => 1,
            'homeUrl' => 'http://' . $_SERVER['HTTP_HOST'],
            'adminEmail' => $_POST['adminEmail'],
            'smhost' => $_POST['smhost'],
            'smport' => $_POST['smport'],
            'smtpauth' => '1',
            'smusername' => $_POST['smusername'],
            'smpassword' => $_POST['smpassword'],
            'smfrom' => $_POST['smfrom'],
            'smfromname' => $_POST['smfromname'],
            //'languages' => isset($_GET['lang']) ? $_GET['lang'] : 'ru',
            'languages' => $_POST['lang'],
            'timezone' => 'Europe/Moscow',
            'useiframe' => 0,
            'allowportal'=> 1
        );
        $str = base64_encode(serialize($config));
        file_put_contents($file, $str);
        $model->setAttributes($config);
        //$connection = Yii::app()->db;
        $connection = new CDbConnection('mysql:host=' . $_POST['dbhost'] . ';dbname=' . $_POST['dbname'], $_POST['dbuser'], $_POST['dbpass']);
        if ($_POST['install_cron'] == true) {
            $crontab = new CrontabManager();
            $job = $crontab->newJob();
            $job->on("*/5 * * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php getstatus >/dev/null 2>&1');

            $job2 = $crontab->newJob();
            $job2->on("*/5 * * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php getmail >/dev/null 2>&1');

            $job3 = $crontab->newJob();
            $job3->on("30 22 * * 5")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php backup >/dev/null 2>&1');

            $job5 = $crontab->newJob();
            $job5->on("0 */1 * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php syncusers >/dev/null 2>&1');

            $job6 = $crontab->newJob();
            $job6->on("*/5 * * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php cronreq >/dev/null 2>&1');

            $job7 = $crontab->newJob();
            $job7->on("*/5 * * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php pamirun >/dev/null 2>&1');

            $job8 = $crontab->newJob();
            $job8->on("* * * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php mailqueue >/dev/null 2>&1');

            $job9 = $crontab->newJob();
            $job9->on("* * * * *")->doJob('(sleep 30; php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php mailqueue >/dev/null 2>&1)');

            $job10 = $crontab->newJob();
            $job10->on("0 0 1 * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php clearlog >/dev/null 2>&1');

            $job11 = $crontab->newJob();
            $job11->on("0 0 * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'cron.php autoarch >/dev/null 2>&1');

            $crontab->add($job);
            $crontab->add($job2);
            $crontab->add($job3);
            $crontab->add($job5);
            $crontab->add($job6);
            $crontab->add($job7);
            $crontab->add($job8);
            $crontab->add($job9);
            $crontab->add($job10);
            $crontab->add($job11);

            $crontab->save(false);
            $setnames = "SET NAMES UTF8";
            $sql = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (1,'Автоматическая обработка статусов заявок по расписанию','1','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php getstatus >/dev/null 2>&1','*/5 * * * *')";
            $sql1 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (2,'Автоматическая проверка IMAP ящика для создания заявок','2','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php getmail >/dev/null 2>&1 ','*/5 * * * *')";
            $sql2 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (3,'Автоматическое резервное копирование БД','3','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php backup >/dev/null 2>&1 ','30 22 * * 5')";
            $sql4 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (5,'Автоматический импорт пользователей из Active Directory','5','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php syncusers >/dev/null 2>&1 ','0 */1 * * *')";
            $sql5 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (6,'Проверка и создание запланированных заявок','6','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php cronreq >/dev/null 2>&1 ','*/5 * * * *')";
            $sql6 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (7,'Переподключение к Asterisk AMI','7','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php pamirun >/dev/null 2>&1 ','*/5 * * * *')";
            $sql7 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (8,'Отправка почты из очереди раз в минуту','8','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php mailqueue >/dev/null 2>&1 ','* * * * *')";
            $sql8 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (9,'Отправка почты из очереди раз в 30 секунд','9','(sleep 30; php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php mailqueue >/dev/null 2>&1)','* * * * *')";
            $sql9 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (10,'Очистка логов системы раз в месяц','10','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php clearlog >/dev/null 2>&1','0 0 1 * *')";
            $sql10 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (11,'Автоархивация заявок','11','php " . ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php autoarch >/dev/null 2>&1','0 0 * * *')";

            try {
                $connection->createCommand($setnames)->query();
                $connection->createCommand($sql)->execute();
                $connection->createCommand($sql1)->execute();
                $connection->createCommand($sql2)->execute();
                $connection->createCommand($sql4)->execute();
                $connection->createCommand($sql5)->execute();
                $connection->createCommand($sql6)->execute();
                $connection->createCommand($sql7)->execute();
                $connection->createCommand($sql8)->execute();
                $connection->createCommand($sql9)->execute();
                $connection->createCommand($sql10)->execute();
            } catch (CDbException $e) {
            }

        }
        //$connection = Yii::app()->db;
//        $user_sql = "SELECT * FROM `CUsers`";
//        $users = $connection->createCommand($user_sql)->queryAll();
//        foreach ($users as $key => $user) {
//            $uid = $user['id'];
//
//            $sql_users = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
//                            ('cusers-grid_" . $uid . "','fullname||company||department||position||Email||Phone||role_name||Действия')";
//
//            $sql_phonebook = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
//                            ('phonebook-grid_" . $uid . "','fullname||city||department||position||Email||Phone||intphone||mobile||Email')";
//
//            $sql_problems = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
//                            ('problems-grid_" . $uid . "','slabel||date||creator||priority||category||manager||Действия')";
//
//            $sql_request = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
//                            ('request-grid_" . $uid . "','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия')";
//
//            $sql_requestf = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
//                            ('request-grid-full_" . $uid . "','rating||slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия')";
//
//            $sql_requestf2 = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
//                            ('request-grid-full2_" . $uid . "','rating||slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия')";
//            try {
//                $connection->createCommand($sql_users)->execute();
//                $connection->createCommand($sql_phonebook)->execute();
//                $connection->createCommand($sql_problems)->execute();
//                $connection->createCommand($sql_request)->execute();
//                $connection->createCommand($sql_requestf)->execute();
//                $connection->createCommand($sql_requestf2)->execute();
//            } catch (CDbException $e) {
//            }
//        }
//        $connection->createCommand('
//            CREATE TRIGGER `update_manager_request` AFTER UPDATE ON `CUsers` FOR EACH ROW
//						IF(old.`fullname` <> new.`fullname`) THEN UPDATE `request` SET `mfullname` = new.`fullname` WHERE `Managers_id` = old.`Username`;
//						UPDATE `request` SET `request`.`creator` = new.`fullname` WHERE `request`.`creator` = old.`fullname`;
//						UPDATE `cunits` SET `cunits`.`fullname` = new.`fullname` WHERE `cunits`.`fullname` = old.`fullname`;
//						UPDATE `asset` SET `asset`.`cusers_fullname` = new.`fullname` WHERE `asset`.`cusers_fullname` = old.`fullname`;
//						UPDATE `request` SET `request`.`fullname` = new.`fullname` WHERE `request`.`CUsers_id` = old.`Username`;
//                        END IF;
//
//            CREATE TRIGGER `update_user_name` BEFORE UPDATE ON `CUsers` FOR EACH ROW
//                        IF(old.`Username` <> new.`Username`) THEN UPDATE `request` SET `request`.`CUsers_id` = new.`Username` WHERE `request`.`CUsers_id` = old.`Username`;
//                        UPDATE `request` SET `request`.`phone` = new.`Phone` WHERE `request`.`CUsers_id` = old.`Username`;
//						UPDATE `cunits` SET `cunits`.`user` = new.`Username` WHERE `cunits`.`user` = old.`Username`;
//						UPDATE `asset` SET `asset`.`cusers_name` = new.`Username` WHERE `asset`.`cusers_name` = old.`Username`;
//						END IF;
//
//            CREATE TRIGGER `company_update_users` AFTER UPDATE ON `companies` FOR EACH ROW
//						IF(old.`name` <> new.`name`) THEN  UPDATE `CUsers` SET `CUsers`.`company` = new.`name` WHERE `CUsers`.`company` = old.`name`;
//						UPDATE `request` SET `request`.`company` = new.`name` WHERE `request`.`company` = old.`name`;
//						UPDATE `cunits` SET `cunits`.`company` = new.`name` WHERE `cunits`.`company` = old.`name`;
//						UPDATE `depart` SET `depart`.`company` = new.`name` WHERE `depart`.`company` = old.`name`;
//						END IF;
//
//            CREATE TRIGGER `service_request_update` AFTER UPDATE ON `service` FOR EACH ROW
//						IF(old.`name` <> new.`name`) THEN
//						UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_id` = old.`id`;
//						UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`;
//						END IF;
//
//            CREATE TRIGGER `sla_service_update` AFTER UPDATE ON `sla` FOR EACH ROW
//						IF(old.`name` <> new.`name`) THEN
//						UPDATE `service` SET `service`.`sla` = new.`name` WHERE `service`.`sla` = old.`name`;
//						END IF;
//            ')->query();
    }

    public function finishInstaller()
    {
        # Lock the installer
        $path = ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'installer.lock';
        file_put_contents($path, "installer lock file");
        echo '<h3>' . Yii::t('install', 'The installation is complete you can go to the main page') . ' <a href="/">Univef service desk</a></h3>';
    }
}
