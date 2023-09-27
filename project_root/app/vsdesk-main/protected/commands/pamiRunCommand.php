<?php

class PamiRunCommand extends CConsoleCommand
{
    public function run($args)
    {
      $os_type = DetectOS::getOS();
      $php_path = 'C:\univefservicedesk\modules\php\PHP-5.6-x64\php.exe';
      $baseDir = dirname(__FILE__);
      $command = dirname(__FILE__) . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR .'cron.php';
      $pidfile = $baseDir . DIRECTORY_SEPARATOR . 'pid_file.pid';

      if (file_exists($pidfile)) {    //Не будем делать сложные проверки на устойчивость и долбать сервер, pid-файл есть, значит всё работает.
          $pid = file_get_contents($pidfile);
          if ($os_type == 2){
              exec('taskkill /f /PID '.$pid);
          }else{
              exec("kill ".$pid);
          }
          unlink($pidfile);
          if ($os_type == 2){
              exec($php_path.' -q C:\univefservicedesk\domains\localhost\protected\cron.php pami &');
          }else{
            //exec("/Applications/MAMP/bin/php/php7.1.1/bin/php -q ".$command." pami &"); //developer mode run
            exec("php -q ".$command." pami &"); //production mode run
          }
          echo "{Process ".$pid." killed. Running new process}"; //1 уже запущен, убит и перезапущен
      } else {
        if ($os_type == 2){
            exec($php_path.' -q C:\univefservicedesk\domains\localhost\protected\cron.php pami &');
        }else{
          //exec("/Applications/MAMP/bin/php/php7.0.15/bin/php -q ".$command." pami &"); //developer mode run
          exec("php -q ".$command." pami &"); //production mode run
        }
          echo "{First run of command}"; //2 сейчас будет запущен
      }
    }

}
