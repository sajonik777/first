<?php

class m160914_074331_30914 extends CDbMigration
{
    public function safeUp()
    {
        $table = Yii::app()->db->schema->getTable('request');
        if(!isset($table->columns['delayed_start'])) {
            $this->addColumn('request', 'delayed_start', 'boolean default 0');
        }

        if(!isset($table->columns['delayed_end'])) {
            $this->addColumn('request', 'delayed_end', 'boolean default 0');
        }

        if(!isset($table->columns['timestampClose'])) {
            $this->addColumn('request', 'timestampClose', 'datetime default NULL');
        }

        if(!isset($table->columns['delayedHours'])) {
            $this->addColumn('request', 'delayedHours', 'integer default 0');
        }

        $sla = Yii::app()->db->schema->getTable('sla');
        if(!isset($sla->columns['autoCloseHours'])) {
            $this->addColumn('sla', 'autoCloseHours', 'integer default 0');
        }

        $comments = Yii::app()->db->schema->getTable('comments');
        if(!isset($comments->columns['readership'])) {
            $this->addColumn('comments', 'readership', 'varchar(255)');
        }

        //drop the comment column values
        $req = Request::model()->findAll();
        foreach ($req as $value) {
            Request::model()->updateByPk($value->id, array('Comment' => ''));
        }

        //status find and fix delayed tickets
        $status = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 5));
        if ($status)
            $zayavki = Request::model()->findAllByAttributes(['Status' => $status->name]);
        else
            $zayavki = Request::model()->findAllByAttributes(['Status' => 'Просрочено исполнение']);

        if (!empty($zayavki)) {
            foreach ($zayavki as $item) {
                if(!empty($item->timestampfStart)){
                    if(strtotime($item->timestampStart) < strtotime($item->timestampfStart))
                        Request::model()->updateByPk($item->id, ['delayed_start' => 1, 'delayed_end' => 1]);
                    else
                        Request::model()->updateByPk($item->id, ['delayed_end' => 1]);
                } else {
                    Request::model()->updateByPk($item->id, ['delayed_start' => 1, 'delayed_end' => 1]);
                }
            }
        }
    }

    public function safeDown()
    {

    }
}