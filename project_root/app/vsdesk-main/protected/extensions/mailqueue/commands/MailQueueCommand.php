<?php

namespace yiicod\mailqueue\commands;

use CConsoleCommand;
use CDbCriteria;
use CMap;
use Exception;
use Yii;

/**
 * Console command
 * class MailQueueCommand.
 */
class MailQueueCommand extends CConsoleCommand
{
    /**
     * @var int Limit of mail
     */
    public $limit = 60;

    /**
     * Condition string.
     *
     * @var string
     */
    public $condition = 'status=:status';

    /**
     * @var string 'priority DESC'
     */
    public $order = null;

    /**
     * Params for condition.
     *
     * @var array
     */
    public $params = ['status' => 0];

    /**
     * Time live file, 1 hour.
     */
    public $timeLock = 3600;

    /**
     * Run send mail.
     */
    public function run($args)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = $this->condition;
        $criteria->params = $this->params;
        $criteria->order = $this->order;
        $criteria->limit = $this->limit;
        try {
            Yii::app()->mailQueue->delivery($criteria);
        } catch (Exception $e) {
            if (YII_DEBUG == false) {
                Yii::log('MailQueueCommand: '.$e->getMessage(), 'error', 'system.mailqueue');
            }
        }
    }

    public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), ['LockUnLockBehavior' => [
                        'class' => 'yiicod\cron\commands\behaviors\LockUnLockBehavior',
                        'timeLock' => $this->timeLock,
                    ]]
        );
    }
}
