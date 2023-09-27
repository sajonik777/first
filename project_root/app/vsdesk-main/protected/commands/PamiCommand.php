<?php

spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'asterisk' . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
    if (file_exists($fileName)) {
        require $fileName;

        return true;
    }

    return false;
});

use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Event\EventMessage;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\DialEvent;
use PAMI\Message\Event\DialBeginEvent;
use PAMI\Message\Event\HangupEvent;
use PAMI\Message\Event\NewstateEvent;
use PAMI\Message\Event\TransferEvent;
use PAMI\Message\Event\AttendedTransferEvent;
use PAMI\Message\Event\BlindTransferEvent;
use React\EventLoop\Factory;


class PamiCommand extends CConsoleCommand
{
    private $enabled = false;
    private $push = false;
    private $loop;
    private $asterisk;
    private $interval = 0.1;
    private $retries = 10;
    private $opened = false;
    private $runned = false;
    private $options = array();

    public function __construct()
    {
        ini_set('display_errors', 'Off');
        error_reporting(0);
        set_time_limit(0);
        date_default_timezone_set(Yii::app()->params['timezone']);

        $baseDir = dirname(__FILE__);
        $pidfile = $baseDir . '/pid_file.pid';
        file_put_contents($pidfile, getmypid());//СОХРАНЯЕМ PID в файле

        $configFile = dirname(__FILE__) . '/../config/ami.inc';
        $content = file_get_contents($configFile);
        $options = unserialize(base64_decode($content));
        $this->options['host'] = $options[amiHost];
        $this->options['scheme'] = $options[amiScheme];
        $this->options['port'] = $options[amiPort];
        $this->options['username'] = $options[amiUsername];
        $this->options['secret'] = $options[amiSecret];
        $this->options['connect_timeout'] = $options[amiConnectTimeout];
        $this->options['read_timeout'] = $options[amiReadTimeout];
        if ($options[amiEnabled] == 1) {
            $this->enabled = true;
        }
        if ($options[amiSendPush] == 1) {
            $this->push = true;
        }
    }

    public function __destruct()
    {
        $baseDir = dirname(__FILE__);
        $pidfile = $baseDir . '/pid_file.pid';
        if ($this->loop && $this->runned) {
            $this->loop->stop();
        }

        if ($this->asterisk && $this->opened) {
            $this->asterisk->close();
        }
        unlink($pidfile);
    }

    public function run($args)
    {
        if ($this->enabled !== true) {
            exit;
        }
        $this->loop = Factory::create();
        $pamiClient = new PamiClient($this->options);
        $this->asterisk = $pamiClient;
        $pamiClient->registerEventListener(function (EventMessage $event) {
            if ($event instanceof DialEvent && $event->getSubEvent() == 'Begin' || $event instanceof HangupEvent || $event instanceof NewstateEvent || $event instanceof DialBeginEvent || $event instanceof TransferEvent || $event instanceof AttendedTransferEvent || $event instanceof BlindTransferEvent) {

                $event_type = $event->getName();
                if ($event instanceof BlindTransferEvent) {
                    $uid = $event->getTransfererlinkedid();
                } else {
                    if ($event instanceof AttendedTransferEvent) {
                        $uid = $event->getOrigTransfererLinkedid();
                    } else {
                        $uid = $event->getUniqueID();
                    }
                }

                if ($event_type == "Dial" OR $event_type == "DialBegin") {
                    echo("Dial event\n");
                    $duid = $event->getDestUniqueID();
                    $dialer = $event->getCallerIDNum();
                    echo("Dialer number: " . $dialer . "\n");
                    $dialed = $event->getDialString();
                    echo("Dialed: " . $dialed . "\n");
                    if ($event_type == "DialBegin") {
                        $dialed2 = $event->getDestCallerIDNum();
                        echo("Dialed: " . $dialed2 . "\n");
                    }
                    //$user = CUsers::model()->findByAttributes(array('intphone'=>$dialer));
                    if(!empty($dialer) AND $dialer !== NULL){
                        $connection = Yii::app()->db;
                        $user_sql = 'SELECT * FROM `CUsers` `t` WHERE `t`.`intphone` LIKE "' . $dialer . '" LIMIT 1';
                        $user = $connection->createCommand($user_sql)->queryRow();
                        $user_sql2 = 'SELECT * FROM `CUsers` `t` WHERE `t`.`Phone` LIKE "' . $dialer . '" LIMIT 1';
                        $user2 = $connection->createCommand($user_sql2)->queryRow();
                        $user_sql3 = 'SELECT * FROM `CUsers` `t` WHERE `t`.`mobile` LIKE "' . $dialer . '" LIMIT 1';
                        $user3 = $connection->createCommand($user_sql3)->queryRow();
                        unset($model);
                        $model = new Calls;
                        //check user in local database to show name and company
                        if (isset($user) AND !empty($user)) {
                            echo("Dialer name: " . $user['fullname'] . "\n");
                            $model->dialer = $user['Username'];
                            $model->dialer_name = $user['fullname'];
                            $caller = $user['fullname'] ? $user['fullname'] : $dialer;
                            $company = $user['company'] ? $user['company'] : "Не указано";
                            $model->dr_company = $company;
                        }
                        if (isset($user2) AND !empty($user2)) {
                            echo("Dialer name: " . $user2['fullname'] . "\n");
                            $model->dialer = $user2['Username'];
                            $model->dialer_name = $user2['fullname'];
                            $caller = $user2['fullname'] ? $user2['fullname'] : $dialer;
                            $company = $user2['company'] ? $user2['company'] : "Не указано";
                            $model->dr_company = $company;
                        }
                        if (isset($user3) AND !empty($user3)) {
                            echo("Dialer name: " . $user3['fullname'] . "\n");
                            $model->dialer = $user3['Username'];
                            $model->dialer_name = $user3['fullname'];
                            $caller = $user3['fullname'] ? $user3['fullname'] : $dialer;
                            $company = $user3['company'] ? $user3['company'] : "Не указано";
                            $model->dr_company = $company;
                        }
                        $model->dr_number = $dialer;
                        if ($event_type == "Dial") {
                            $manager = CUsers::model()->findByAttributes(array('intphone' => $dialed));
                        } else {
                            if ($event_type == "DialBegin") {
                                $manager = CUsers::model()->findByAttributes(array('intphone' => $dialed2));
                            }
                        }

                        //check manager number in database to route alert or don't write the call log
                        if (isset($manager)) {
                            $model->dialed = $manager->Username;
                            $model->dialed_name = $manager->fullname;
                            $model->dd_number = ($event_type == "Dial") ? $dialed : $dialed2;
                            $model->uniqid = $uid;
                            $model->duniqid = $duid;
                            $model->date = date("Y-m-d H:i:s");
                            if ($model->save(false)) {
                                if ($this->push == true) {
                                    $message = "Входящий звонок!\r\nВам звонит: " . $caller . "\r\nКомпания: " . $company;
                                    $url = Yii::app()->params->homeUrl . "/calls/" . $model->id;
                                    $manager->pushMessage($message, $url);
                                }
                            }
                        }
                    }
                }
                if ($event_type == 'Newstate') {
                    $up = $event->getChannelStateDesc();
                    if ($up == 'Up') {
                        $calls = Calls::model()->findAllByAttributes(array('uniqid' => $uid));
                        if(!isset($calls) OR empty($calls)){
                            $calls = Calls::model()->findAllByAttributes(array('duniqid' => $uid));
                        }
                        foreach ($calls as $call) {
                            if (isset($call)) {
                                //Calls::model()->updateByPk($call->id, array('adate' => date("Y-m-d H:i:s"), 'status' => 'Answered', 'shown' => 1));
                                Calls::model()->updateByPk($call->id, array('adate' => date("Y-m-d H:i:s"), 'status' => 'Answered'));
                            }
                        }
                    }
                }
                if ($event_type == 'Hangup') {
                    $calls = Calls::model()->findAllByAttributes(array('uniqid' => $uid));
                    foreach ($calls as $call) {
                        if (isset($call) AND empty($call->adate)) {
                            //Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'status' => 'Hangup', 'shown' => 1));
                            Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'status' => 'Hangup'));
                        } elseif (isset($call) AND !empty($call->adate)) {
                            //Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'shown' => 1));
                            Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s")));
                        }
                    }
                }
                if ($event_type == 'Transfer' OR $event_type == 'AttendedTransfer' OR $event_type == 'BlindTransfer') {
                    $calls = Calls::model()->findAllByAttributes(array('uniqid' => $uid));
                    foreach ($calls as $call) {
                        if (isset($call) AND empty($call->adate)) {
                            //Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'status' => 'Transfered', 'shown' => 1));
                            Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'status' => 'Transfered'));
                        } elseif (isset($call) AND !empty($call->adate)) {
                            //Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'shown' => 1));
                            Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s")));
                        }
                    }
                }
            }
        });
        // Open the connection
        $pamiClient->open();
        echo("Client open\n");
        $this->opened = true;
        $retries = $this->retries;

        $this->loop->addPeriodicTimer($this->interval, function () use ($pamiClient, $retries) {
            try {
                $pamiClient->process();
            } catch (Exception $exc) {
                if ($retries-- <= 0) {
                    $msg = $exc->getMessage();
                    Yii::log($msg, 'error', 'AMI_ERR');
                    echo($msg . "\n");
                    $pamiClient->close();
                    echo("Close\n");
                    usleep(1000);
                    $pamiClient->open();
                    echo("Client reopen\n");
                    $pamiClient->process();
                    echo("Client reprocess\n");
                }
                sleep(10);
            }
        });
        $this->runned = true;
        $this->loop->run();
    }
}