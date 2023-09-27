<?php

class m210903_200003_wbot extends CDbMigration
{
    public function up()
    {
        $table = Yii::app()->db->schema->getTable('CUsers');
        if (!isset($table->columns['wbot'])) {
            $this->addColumn('CUsers', 'wbot', 'varchar(50)');
        }
        if (!isset($table->columns['send_wbot'])) {
            $this->addColumn('CUsers', 'send_wbot', 'int(1)');
        }
        if (!isset($table->columns['send_tbot'])) {
            $this->addColumn('CUsers', 'send_tbot', 'int(1)');
        }
        if (!isset($table->columns['send_vbot'])) {
            $this->addColumn('CUsers', 'send_vbot', 'int(1)');
        }

        $table2 = Yii::app()->db->schema->getTable('request');
        if (!isset($table2->columns['wbot_id'])) {
            $this->addColumn('request', 'wbot_id', 'varchar(255) default NULL');
        }
    }

    public function down()
    {
        echo "m210903_200003_wbot does not support migration down.\n";
        return false;
    }
}
