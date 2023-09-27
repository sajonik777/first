<?php

/**
 * Class m180207_070507_refact_request_fields
 */
class m180207_070507_50303 extends CDbMigration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        $this->execute("ALTER TABLE `request_fields` CHANGE `name` `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `request_fields` CHANGE `type` `type` ENUM('toggle','date','textFieldRow','select') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `request_fields` CHANGE `value` `value` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `request_fields` ADD INDEX(`type`);");
        $this->execute("ALTER TABLE `request_fields` ADD INDEX(`value`);");
        $this->execute("ALTER TABLE `request_fields` ENGINE = InnoDB;");
        $this->execute("OPTIMIZE TABLE `request_fields`;");
        //$this->execute("FLUSH TABLE `request_fields`;");

        $table = Yii::app()->db->schema->getTable('groups');
        if(!isset($table->columns['phone'])) {
                $this->addColumn('groups', 'phone', 'varchar(100)');
        }
        if(!isset($table->columns['email'])) {
                $this->addColumn('groups', 'email', 'varchar(100)');
        }
        $table2 = Yii::app()->db->schema->getTable('companies');
        if(!isset($table->columns['domains'])) {
                $this->addColumn('companies', 'domains', 'text');
        }
    }

    public function down()
    {
        return false;
    }
}