<?php

class m181112_061452_51114 extends CDbMigration
{
	public function up()
	{
		$requests = Request::model()->findAllByAttributes(['closed' => 3]);
        foreach ($requests as $request) {
            $manager = CUsers::model()->findByAttributes(['Username' => $request->Managers_id]);
            if ($manager) {
                Request::model()->updateByPk($request->id, ['gr_id' => $manager->id]);
            }
        }

        $table = Yii::app()->db->schema->getTable('request');
        if (!isset($table->columns['jira'])) {
            $this->addColumn('request', 'jira', 'varchar(255)');
        }

        $table2 = Yii::app()->db->schema->getTable('sms');
        if(isset($table2->columns['content'])) {
            $this->alterColumn('sms', 'content', 'varchar(500)');
        }
	}

	public function down()
	{
		echo "m181112_061452_51114 does not support migration down.\n";
		return false;
	}
}