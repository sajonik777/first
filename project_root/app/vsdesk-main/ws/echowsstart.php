<?php
require __DIR__ . '/../protected/components/DetectOS.php';
$baseDir = dirname(__FILE__);
$pidfile = $baseDir . '/pid_file.pid';

if (file_exists($pidfile)) {    //Не будем делать сложные проверки на устойчивость и долбать сервер, pid-файл есть, значит всё работает.
    echo "{run:1}"; //1 уже запущен
} else {
	$os_type = DetectOS::getOS();
	
    if ($os_type == 2){
		    	if(version_compare(PHP_VERSION, '7.0.0', '>=')){
		            $php_path = 'c:\univefservicedesk\modules\php\PHP-7.0-x64\php.exe';    
		        }else{
		            $php_path = 'c:\univefservicedesk\modules\php\PHP-5.6-x64\php.exe';
		        }
                exec($php_path. " -q ". $baseDir . "echows.php &");
            }else{
                exec("php -q " . $baseDir . "echows.php &");
            }
    echo "{run:2}"; //2 сейчас будет запущен
}
?>