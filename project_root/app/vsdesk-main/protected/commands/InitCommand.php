<?php

include Yii::app()->basePath . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CrontabManager.php';
include Yii::app()->basePath . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CronEntry.php';
include Yii::app()->basePath . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CliTool.php';
use php\manager\crontab\CrontabManager;

class InitCommand extends CConsoleCommand
{
    public $sqlArray = array();
    public $message;
    public $file;
    public $_path = null;

    public function run($args)
    {

        $dump_name = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . basename('database_ru.sql');
        $this->execSqlFile($dump_name);
        $this->writeConfig();
        $path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'installer.lock';
        file_put_contents($path, "installer lock file");
    }

    public function writeConfig()
    {
        $yii = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'yii' . DIRECTORY_SEPARATOR . 'yii.php';
        require_once($yii);
        $connection = new CDbConnection('mysql:host=localhost;dbname=univefservicedesk', 'root', 'root');
        $setnames = "SET NAMES UTF8";
        $sql = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (1,'Автоматическая обработка статусов заявок по расписанию','1','php " . Yii::app()->basePath . DIRECTORY_SEPARATOR . "cron.php getstatus >/dev/null 2>&1','*/5 * * * *')";
        $sql1 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (2,'Автоматическая проверка IMAP ящика для создания заявок','2','php " . Yii::app()->basePath . DIRECTORY_SEPARATOR . "cron.php getmail >/dev/null 2>&1 ','*/5 * * * *')";
        $sql2 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (3,'Автоматическое резервное копирование БД','3','php " . Yii::app()->basePath . DIRECTORY_SEPARATOR . "cron.php backup >/dev/null 2>&1 ','30 22 * * 5')";
        $sql4 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (5,'Автоматический импорт пользователей из Active Directory','5','php " . Yii::app()->basePath . DIRECTORY_SEPARATOR . "cron.php syncusers >/dev/null 2>&1 ','0 */1 * * *')";
        $sql5 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (6,'Проверка и создание запланированных завок','6','php " . Yii::app()->basePath . DIRECTORY_SEPARATOR . "cron.php cronreq >/dev/null 2>&1 ','*/5 * * * *')";
        $sql6 = "INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES (6,'Переподключение к Asterisk AMI','7','php " . Yii::app()->basePath . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "cron.php pamirun >/dev/null 2>&1 ','*/30 * * * *')";
        try {
            $connection->createCommand($setnames)->query();
            $connection->createCommand($sql)->execute();
            $connection->createCommand($sql1)->execute();
            $connection->createCommand($sql2)->execute();
            $connection->createCommand($sql4)->execute();
            $connection->createCommand($sql5)->execute();
            $connection->createCommand($sql6)->execute();
        } catch (CDbException $e) {
        }
        $user_sql = "SELECT * FROM `CUsers`";
        $users = $connection->createCommand($user_sql)->queryAll();
        foreach ($users as $key => $user) {
            $uid = $user['id'];

            $sql_users = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
                            ('cusers-grid_" . $uid . "','fullname||company||department||position||Email||Phone||role_name||Действия')";

            $sql_problems = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
                            ('problems-grid_" . $uid . "','slabel||date||creator||priority||category||manager||Действия')";

            $sql_request = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
                            ('request-grid_" . $uid . "','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия')";

            $sql_requestf = "INSERT INTO `tbl_columns` (`id`,`data`) VALUES
                            ('request-grid-full_" . $uid . "','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия')";
            try {
                $connection->createCommand($sql_users)->execute();
                $connection->createCommand($sql_problems)->execute();
                $connection->createCommand($sql_request)->execute();
                $connection->createCommand($sql_requestf)->execute();
            } catch (CDbException $e) {
            }
        }
        $connection->createCommand('
            CREATE TRIGGER `update_manager_request` AFTER UPDATE ON `CUsers` FOR EACH ROW
						IF(old.`fullname` <> new.`fullname`) THEN UPDATE `request` SET `mfullname` = new.`fullname` WHERE `Managers_id` = old.`Username`;
						UPDATE `request` SET `request`.`creator` = new.`fullname` WHERE `request`.`creator` = old.`fullname`;
						UPDATE `cunits` SET `cunits`.`fullname` = new.`fullname` WHERE `cunits`.`fullname` = old.`fullname`;
						UPDATE `asset` SET `asset`.`cusers_fullname` = new.`fullname` WHERE `asset`.`cusers_fullname` = old.`fullname`;
						UPDATE `request` SET `request`.`fullname` = new.`fullname` WHERE `request`.`CUsers_id` = old.`Username`;
						END IF;

            CREATE TRIGGER `company_update_users` AFTER UPDATE ON `companies` FOR EACH ROW
						IF(old.`name` <> new.`name`) THEN  UPDATE `CUsers` SET `CUsers`.`company` = new.`name` WHERE `CUsers`.`company` = old.`name`;
						UPDATE `request` SET `request`.`company` = new.`name` WHERE `request`.`company` = old.`name`;
						UPDATE `cunits` SET `cunits`.`company` = new.`name` WHERE `cunits`.`company` = old.`name`;
						UPDATE `depart` SET `depart`.`company` = new.`name` WHERE `depart`.`company` = old.`name`;
						END IF;

            CREATE TRIGGER `service_request_update` AFTER UPDATE ON `service` FOR EACH ROW
						IF(old.`name` <> new.`name`) THEN
						UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_name` = old.`name`;
						UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`;
						END IF;

            CREATE TRIGGER `sla_service_update` AFTER UPDATE ON `sla` FOR EACH ROW
						IF(old.`name` <> new.`name`) THEN
						UPDATE `service` SET `service`.`sla` = new.`name` WHERE `service`.`sla` = old.`name`;
						END IF;
            ')->query();
    }

    public function execSqlFile($sqlFile)
    {
        if (file_exists($sqlFile)) {
            $sqlArray = file_get_contents($sqlFile);
            $connection = new CDbConnection('mysql:host=localhost;dbname=univefservicedesk', 'root', 'root');
            $cmd = $connection->createCommand($sqlArray);
            try {
                $cmd->execute();
            } catch (CDbException $e) {
                $message = $e->getMessage();
            }
        }
    }

}
