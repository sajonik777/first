<?php

class AutoarchCommand extends CConsoleCommand
{
    const REQUEST_PER_ITERATION = 500;

    /**
     * @param array $args
     * @return int|void
     * @throws CException
     * @throws Exception
     */
    public function run($args)
    {
        define('ROOT_PATH', dirname(__FILE__));
        $configPath = dirname(__FILE__) . '/../config/request.inc';
        $content = file_get_contents($configPath);
        $conf = unserialize(base64_decode($content));
        $enabled = $conf['autoarch'];
        $archdays = $conf['autoarchdays'];

        if (YII_DEBUG == true) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if(isset($enabled) AND $enabled == 1){
            Yii::import('application.models.Request', true);
            $criteria = new CDbCriteria();
            $criteria->addNotInCondition('closed', array('10'), 'OR');
            $dataProvider = new CActiveDataProvider("Request", [
                'criteria' => $criteria,
                'sort' => [
                    'defaultOrder' => 't.id DESC'
                ]
            ]);
            $archstatus = Status::model()->findByAttributes(['close' => 11]); //find archive status
            $tickets = new CDataProviderIterator($dataProvider, self::REQUEST_PER_ITERATION);
            $now = strtotime(date("Y-m-d H:i:s"));
            $days = $archdays ? $archdays : 30;

            foreach ($tickets as $item) {
                if($item->id !== NULL) {
                    $archdate = strtotime("+" .$days." days", strtotime($item->timestamp));
                    if($now >= $archdate){
                        echo("Archiving ticket #".$item->id."\n");
                        Request::model()->updateByPk($item->id, [
                            'closed' => 10,
                            'Status' => $archstatus->name ? $archstatus->name : 'Archive',
                            'slabel' => $archstatus->label ? $archstatus->label : '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #8fc763; vertical-align: baseline; white-space: nowrap; border: 1px solid #8fc763; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">Archive</span>',
                        ]);
                        $this->AddHistory('Изменен статус: ' . $archstatus->label . '', $item);
                    }
                }

            }
        }

    }


    /**
     * Adding new record to history action
     *
     * @param $action
     * @param $item
     */
    public function AddHistory($action, $item)
    {
        $history = new History();
        $history->datetime = date("d.m.Y H:i");
        $history->cusers_id = 'system';
        $history->zid = $item->id;
        $history->action = $action;
        $history->save(false);

    }
}
