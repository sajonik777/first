<?php

class m170621_101254_40630 extends CDbMigration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';
        if (Yii::app()->db->schema->getTable('subscribers', true) === null) {
            $this->createTable('subscribers', [
                'user_id' => 'int  NOT NULL',
                'subscriber_id' => 'varchar(160) NOT NULL',
                'chrome' => 'boolean'
            ], $tableOptions);
            $this->createIndex('idx_uq_subscribers_user_id', 'subscribers', 'user_id', true);
            $this->createIndex('idx_uq_subscribers_subscriber_id', 'subscribers', 'subscriber_id', true);
            $this->createIndex('idx_subscribers_user_subscriber', 'subscribers', ['user_id', 'subscriber_id'], true);
        }

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MEMORY';
        if (Yii::app()->db->schema->getTable('pushs', true) === null) {
            $this->createTable('pushs', [
                'id' => 'pk',
                'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
                'user_id' => 'int NOT NULL',
                'notification' => 'varchar(128) NOT NULL',
                'url' => 'varchar(128) NOT NULL',
            ], $tableOptions);
            $this->createIndex('idx_pushs_user_id', 'pushs', 'user_id', false);
            $this->createIndex('idx_pushs_user_id_created_at', 'pushs', ['user_id', 'created_at'], false);
        }
    }

    public function down()
    {
        echo "m170621_101254_40630 does not support migration down.\n";
        return false;
    }
}
