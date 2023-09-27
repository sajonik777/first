<?php

class m170817_101337_40830 extends CDbMigration
{
	public function up()
	{
		$tableOptions = 'ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';

		if (Yii::app()->db->schema->getTable('calls', true) === null) {
				$this->createTable('calls', [
						'id' => 'pk',
						'rid' => 'int(11) DEFAULT NULL',
						'uniqid' => 'varchar(50) DEFAULT NULL',
						'duniqid' => 'varchar(50) DEFAULT NULL',
						'date' => 'timestamp NULL DEFAULT NULL',
					  'adate' => 'timestamp NULL DEFAULT NULL',
					  'edate' => 'timestamp NULL DEFAULT NULL',
					  'dialer' => 'varchar(200) DEFAULT NULL',
					  'dialer_name' => 'varchar(200) DEFAULT NULL',
					  'dr_number' => 'varchar(200) DEFAULT NULL',
					  'dr_company' => 'varchar(200) DEFAULT NULL',
					  'dialed' => 'varchar(200) DEFAULT NULL',
					  'dialed_name' => 'varchar(200) DEFAULT NULL',
					  'dd_number' => 'varchar(200) DEFAULT NULL',
					  'status' => 'varchar(200) DEFAULT NULL',
					  'slabel' => 'varchar(200) DEFAULT NULL',
					  'shown' => 'int(1) DEFAULT NULL',
				], $tableOptions);
			}

			$table = Yii::app()->db->schema->getTable('CUsers');
			if(isset($table->columns['company'])) {
					$this->alterColumn('CUsers', 'company', 'varchar(100) DEFAULT NULL');
			}
			$table2 = Yii::app()->db->schema->getTable('request');
			if(!isset($table2->columns['lastactivity'])) {
					$this->addColumn('request', 'lastactivity', 'datetime DEFAULT NULL');
			}
	}

	public function down()
	{
		echo "m170817_101337_asterisk does not support migration down.\n";
		return false;
	}
}
