<?php

 spl_autoload_register(function ($className) {
     $className = ltrim($className, '\\');
     $fileName = '';
     if ($lastNsPos = strripos($className, '\\')) {
         $namespace = substr($className, 0, $lastNsPos);
         $className = substr($className, $lastNsPos + 1);
         $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
     }
     $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'asterisk' . DIRECTORY_SEPARATOR .$fileName . $className . '.php';
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
 use PAMI\Message\Event\HangupEvent;
 use PAMI\Message\Event\NewstateEvent;
 use React\EventLoop\Factory;


class PamiTestCommand extends CConsoleCommand
{
    public $loop;
    public function run($args)
    {
      ini_set('display_errors', 'Off');
      error_reporting(0);
      date_default_timezone_set(Yii::app()->params['timezone']);
      $this->loop = Factory::create();
      $pamiClientOptions = array(
          'host' => '217.144.98.154',
          'scheme' => 'tcp://',
          'port' => 5038,
          'username' => 'testasi',
          'secret' => 'HGqqzkX37VZcWGX',
          'connect_timeout' => 10000,
          'read_timeout' => 10000
      );
      $pamiClient = new PamiClient($pamiClientOptions);
      // Open the connection
      $status = $pamiClient->open();
      echo("Client open\n");
      $pamiClient->registerEventListener(function (EventMessage $event) {
        if($event instanceof DialEvent && $event->getSubEvent() == 'Begin' || $event instanceof HangupEvent || $event instanceof NewstateEvent){
          $event_type = $event->getName();
          $uid = $event->getUniqueID();
          //var_dump($event);
          if($event_type == "Dial"){
            echo("Dial event\n");
            $duid = $event->getDestUniqueID();
            $dialer = $event->getCallerIDNum();
            $dialed = $event->getDialString();
            //$user = CUsers::model()->findByAttributes(array('intphone'=>$dialer));
            $connection = Yii::app()->db;
            $user_sql = 'SELECT * FROM `CUsers` `t` WHERE `t`.`intphone` LIKE "%' . $dialer . '%" LIMIT 1';
            $user = $connection->createCommand($user_sql)->queryRow();
            $model = new Calls();
            if(isset($user)){
              $model->dialer = $user->Username;
              $model->dialer_name = $user->fullname;
              $caller = $user->fullname?$user->fullname:$dialer;
              $company = $user->company?$user->company:"Не указано";
              $model->dr_company = $company;
            }
            $model->dr_number = $dialer;
            $manager = CUsers::model()->findByAttributes(array('intphone'=>$dialed));
            if(isset($manager)){
              $model->dialed = $manager->Username;
              $model->dialed_name = $manager->fullname;
              $message = "Входящий звонок!\r\nВам звонит: " . $caller . "\r\nКомпания: " . $company;
              $url = $user?"/cusers/".$user->id:"/";
              $manager->pushMessage($message, $url);
            }
            $model->dd_number = $dialed;
            $model->uniqid = $uid;
            $model->duniqid = $duid;
            //$model->date = date("Y-m-d H:i:s");
            $model->save(false);

          }
          if($event_type == 'Newstate'){
            $up = $event->getChannelStateDesc();
            if($up == 'Up'){
              $call = Calls::model()->findByAttributes(array('uniqid'=> $uid));
              if(isset($call)){
                Calls::model()->updateByPk($call->id, array('adate' => date("Y-m-d H:i:s"), 'status' => 'Answered', 'shown' => 1));
              }
            }
          }
          if($event_type == 'Hangup'){
            $call = Calls::model()->findByAttributes(array('uniqid'=> $uid));
            if(isset($call) AND empty($call->adate)){
              Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'status' => 'Hangup', 'shown' => 1));
            } elseif (isset($call) AND !empty($call->adate)){
              Calls::model()->updateByPk($call->id, array('edate' => date("Y-m-d H:i:s"), 'shown' => 1));
            }
          }
        }
      });
      $running = true;
      // Main loop
      while($running) {
        try {
          $pamiClient->process();
          //echo('Process');
        } catch (Exception $exc) {
          if ($retries-- <= 0) {
            echo($exc->getMessage()."\n");
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
      }
      //TESTING PCNTL_FORK BUT NOT IN WINDOWS OS
      // $this->loop->addPeriodicTimer(1, function () use (&$pamiClient) {
      //        $pid = \pcntl_fork();
      //        if ($pid < 0) { // ошибка создания exit;
      //        }elseif ($pid) { // родитель, ждет выполнения потомков
      //            \pcntl_waitpid($pid, $status, WUNTRACED);
      //            if ($status > 0) {
      //                // если произошла ошибка в канале, пересоздаем
      //                $pamiClient->close();
      //                usleep(1000);
      //                $pamiClient->open();
      //            }
      //
      //            return;
      //        } else {
      //            // выполнение дочернего процесса
      //            try {
      //                $pamiClient->process();
      //                exit(0);
      //            } catch (\Exception $e) {
      //                exit(1);
      //            }
      //        }
      //    });
      //
      //    // восстановление подпроцессов
      //    $this->loop->addPeriodicTimer(30, function () {
      //        while (($pid = \pcntl_waitpid(0, $status, WNOHANG)) > 0) {
      //            echo "process exit. pid:" . $pid . ". exit code:" . $status . "\n";
      //        }
      //    });
      // Close the connection
      $pamiClient->close();
    }

}
