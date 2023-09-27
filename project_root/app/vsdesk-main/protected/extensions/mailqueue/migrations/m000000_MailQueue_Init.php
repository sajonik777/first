<?php

class m000000_MailQueue_Init extends CDbMigration
{
    public function up()
    {
        $this->execute('
            CREATE TABLE `MailQueue` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `from` varchar(100) NOT NULL,
              `to` varchar(100) NOT NULL,
              `subject` varchar(100) NOT NULL,
              `body` text,
              `attachs` text,
              `priority` SMALLINT (2) NOT NULL DEFAULT 0,
              `status` TINYINT(1) DEFAULT NULL,
              `createDate` datetime DEFAULT NULL,
              `updateDate` datetime DEFAULT NULL,              
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
    }

    public function down()
    {
        $this->execute('
            DROP TABLE `MailQueue`;
        ');
    }
}
