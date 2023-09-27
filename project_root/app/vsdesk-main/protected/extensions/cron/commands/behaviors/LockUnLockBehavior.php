<?php

namespace yiicod\cron\commands\behaviors;

use Yii;
use CConsoleCommandBehavior;

/**
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */
class LockUnLockBehavior extends CConsoleCommandBehavior
{
    /**
     * File path.
     */
    protected $filePath;

    /**
     * Time live file, 8 hour 28800.
     */
    public $timeLock = 28800;

    /**
     * Parses the command line arguments and determines which action to perform.
     *
     * @param array $args command line arguments
     *
     * @return array the action name, named options (name=>value), and unnamed options
     *
     * @since 1.1.5
     */
    protected function resolveRequest($args)
    {
        $options = []; // named parameters
        $params = []; // unnamed parameters
        foreach ($args as $arg) {
            if (preg_match('/^--(\w+)(=(.*))?$/', $arg, $matches)) {  // an option
                $name = $matches[1];
                $value = isset($matches[3]) ? $matches[3] : true;
                if (isset($options[$name])) {
                    if (!is_array($options[$name])) {
                        $options[$name] = [$options[$name]];
                    }
                    $options[$name][] = $value;
                } else {
                    $options[$name] = $value;
                }
            } elseif (isset($action)) {
                $params[] = $arg;
            } else {
                $action = $arg;
            }
        }
        if (!isset($action)) {
            $action = $this->defaultAction;
        }

        return [$action, $options, $params];
    }

    public function beforeAction($event)
    {
        if (empty($this->filePath)) {
            $argv = array_diff($_SERVER['argv'], ['yiic']);
            list($action, $options, $args) = $this->resolveRequest($argv);
            $this->filePath = '/runtime/'.$event->sender->name.preg_replace('/[^A-Za-z0-9-]+/', '_', trim(implode(' ', $args)).' '.trim(implode(' ', $options))).'.txt';
        }

        if (!$this->_lock()) {
            Yii::app()->end();
        }

        return parent::beforeAction($event);
    }

    public function afterAction($event)
    {
        $this->_unLock();

        return parent::afterAction($event);
    }

    /**
     * Check the end of the process. 
     * If a thread is not locked, it is locked and start command. 
     *
     * @return bool
     */
    protected function _lock()
    {
        $lockFilePaht = Yii::app()->basePath.$this->filePath;

        // current time
        if (false === file_exists($lockFilePaht)) {
            file_put_contents($lockFilePaht, time());

            return true;
        } else {
            $timeSec = time();
            // time change file
            $timeFile = @filemtime($lockFilePaht) ? @filemtime($lockFilePaht) : time();

            // Now find out how much time has passed (in seconds)
            if (($timeSec - $timeFile) > $this->timeLock) {
                $this->_unLock();

                file_put_contents($lockFilePaht, time());

                return true;
            }
            echo "Cron run\n";

            return false;
        }
    }

    /**
     * Unlocking the process of sending letters.
     *
     * @return bool
     */
    protected function _unLock()
    {
        $lockFilePaht = Yii::app()->basePath.$this->filePath;
        if (true === file_exists($lockFilePaht)) {
            return unlink($lockFilePaht);
        } else {
            return true;
        }
    }
}
