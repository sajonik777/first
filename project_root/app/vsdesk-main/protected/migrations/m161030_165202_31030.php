<?php
define('ROOT_PATH', dirname(__DIR__));
include ROOT_PATH  . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CrontabManager.php';
include ROOT_PATH  . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CronEntry.php';
include ROOT_PATH  . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'CliTool.php';
use php\manager\crontab\CrontabManager;

class m161030_165202_31030 extends CDbMigration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        if (Yii::app()->db->schema->getTable('chat', true) === NULL){
            $this->createTable('chat', array(
                'id' => 'pk',
                'created' => 'TIMESTAMP NOT NULL',
                'name' => 'varchar(32) NOT NULL',
                'reader' => 'varchar(32) default NULL',
                'message' => 'varchar(255) NOT NULL',
                'rstate' => 'int(1) default 0',
            ), $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('chat_read', true) === NULL){
            $this->createTable('chat_read', array(
                'user' => 'int NOT NULL',
                'chat' => 'int NOT NULL',
                'UNIQUE KEY `user_chat_fk` (`user`,`chat`)'
            ), $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('cron_req', true) === NULL){
            $this->createTable('cron_req', array(
                'id' => 'pk',
                'service_id' => 'int NOT NULL',
                'CUsers_id' => 'varchar(32) NOT NULL',
                'Status' => 'varchar(32) NOT NULL',
                'ZayavCategory_id' => 'varchar(32) NOT NULL',
                'Priority' => 'varchar(50) NOT NULL',
                'Name' => 'varchar(100) NOT NULL',
                'Content' => 'text NOT NULL',
                'watchers' => 'varchar(500)',
                'cunits' => 'varchar(500)',
                'Date' => 'datetime NOT NULL',
                'repeats' => 'int(1) default 0',
                'enabled' => 'int(1) default 0',
                'color' => 'varchar(50)',
                'fields' => 'text',
            ), $tableOptions);
        }

//        $crontab = new CrontabManager();
//        $job = $crontab->newJob();
//        $job->on("*/5 * * * *")->doJob('php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'cron.php cronreq >/dev/null 2>&1');
//        $crontab->add($job);
//        $crontab->save(false);
//
//        $this->insert('cron', array('name'=>'Проверка и создание запланированных завок', 'job_id'=>'6', 'job'=>'php ' . ROOT_PATH . DIRECTORY_SEPARATOR . 'cron.php cronreq >/dev/null 2>&1', 'time' => '*/5 * * * *'));

    }

    public function down()
    {
        $this->dropTable('cron_req');
        $this->dropTable('chat');
        $this->dropTable('chat_read');
    }
}