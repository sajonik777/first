<?php


class ClearLogCommand extends CConsoleCommand
{
    public $clearLog;

    public function run($args)
    {
        $connection = Yii::app()->db;
        $clearLog = "DELETE FROM YiiLog";
        $connection->createCommand($clearLog)->query();
    }

}
