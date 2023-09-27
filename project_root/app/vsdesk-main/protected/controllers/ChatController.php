<?php

class ChatController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/design3';

    public function filters()
    {
        return array(
            'accessControl',// perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'control', 'privates', 'chats', 'read'),
                'roles' => array('readChat'),
            ),
            array('allow',
                'actions' => array('admin', 'control'),
                'roles' => array('adminChat'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        //$this->redirect('/chat/privates?user=main');
    }

    public function actionAdmin()
    {
        $this->render('admin');
    }


    public function actionChats()
    {
        $this->layout = null;
        $chats = Chats::model()->findAllByAttributes(['reader' => 'main'], ['order' => 'id DESC']);
        foreach ($chats as $chat) {
            /* @var $chat Chats */
            echo '<div class="direct-chat-msg"><div class="direct-chat-info clearfix">';
            echo '<span class="direct-chat-name pull-left">' . $chat->name . '</span>';
            echo '<span class="direct-chat-timestamp pull-right">' . $chat->created . '</span></div>';
            echo '<img class="direct-chat-img" src="/images/profle.png">';
            echo '<div class="direct-chat-text">' . $chat->message . '</div></div>';
            $chat->setRead();
        }
    }

    public function actionPrivates()
    {
        $fullname = CUsers::model()->findByPk(Yii::app()->user->id);

        $connection = Yii::app()->db;
        $sql = "SELECT DISTINCT `name`, `reader` FROM chat WHERE (`reader`='" . $fullname->fullname . "') OR (`reader` IS NOT NULL AND `name`='" . $fullname->fullname . "');";
        $members = $connection->createCommand($sql)->queryAll();
        $all = [];
        foreach ($members as $member) {
            $all[] = $member["name"];
            $all[] = $member["reader"];
        }
        $all = array_unique($all);
        $key = array_search($fullname->fullname, $all);
        unset($all[$key]);

        $all2 = [];
        foreach ($all as $item) {
            $sql = "SELECT COUNT(*) FROM chat WHERE `name`='" . $item . "' AND `reader`='" . $fullname->fullname . "' AND `rstate`=0;";
            $count = $connection->createCommand($sql)->queryScalar();
            $all2[$item] = $count;
        }
        $this->render('privates', ['all' => $all2]);
    }

    /**
     * @return string
     */
    public function actionRead()
    {
        if (isset($_POST['sender']))
        $name = trim($_POST['user']);
        $user = CUsers::model()->findByPk($_POST['sender']);
        $sender = $user->fullname;
        $dbconfig = dirname(__FILE__) . '/../config/dbconfig.php';
        $config = require($dbconfig);
        $connectionString = $config['connectionString'] . ';charset=' . $config['charset'];
        $connection = new PDO($connectionString, $config['username'], $config['password']);
        if ($name == 'main'){
            $x = $connection->prepare("UPDATE chat SET `rstate`=1 WHERE (`reader`= :main AND `rstate`=0)");
            $x->execute([':main' => 'main']);
        } else {
            $x = $connection->prepare("UPDATE chat SET `rstate`=1 WHERE (`reader`=:sender AND `name`=:uname AND `rstate`=0)");
            $x->execute([':sender' => $sender, ':uname' => $name]);
        }
        $connection = null;
        unset($dbconfig, $config, $connectionString, $connection, $chats, $x);
    }

    public function actionControl($act)
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $address = '127.0.0.1';
        $port = 8889;

        $baseDir = ROOT_PATH . '/ws/';

        $os_type = DetectOS::getOS();
        if(version_compare(PHP_VERSION, '7.0.0', '>=')){
            $php_path = 'c:\univefservicedesk\modules\php\PHP-7.0-x64\php.exe';    
        }else{
            $php_path = 'c:\univefservicedesk\modules\php\PHP-5.6-x64\php.exe';
        }
        $pidfile = $baseDir . 'pid_file.pid';
        $offfile = $baseDir . 'off_file.pid';

        if (empty($act))
            Yii::app()->end();

        if ($act == 'start') { //Если происходит действите старт, инициализируем игру
            if ($os_type == 2){
                exec($php_path. " -q ". $baseDir . "echows.php &");
            }else{
                exec("php -q " . $baseDir . "echows.php &");
            }

            //воткнуть паузу 0,5 для того, чтобы ws сервак мог нормально стартануть
            usleep(300000);
            $this->status($pidfile);
            exit();
        } elseif ($act == 'stop') { //Если действите старт не произошло и игра не инициализирована, то выходим

            $pid = $this->getstatus($pidfile);
            if ($pid == -1) {
                //echo "{color:\"grey\",msg:\"[<b>".date("Y.m.d-H:i:s")."</b>] ws echo server already stopped\"}";//Не работает передача - это JSON
                $this->status($pidfile);
                Yii::app()->end();
            }
            //создаём offfile только зная что процесс запущен, чтобы избежать глюков при следующем запуске процесса
            file_put_contents($offfile, $pid);//СОХРАНЯЕМ PID в OFF файле

            usleep(300000);

            //Для того, чтобы полностью отключить сервер, нужно отправить ему сообщение, чтобы у него сработал read
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket < 0) {/* Ошибка */
            }
            $connect = socket_connect($socket, $address, $port);
            if ($connect === false) { /* echo "Ошибка : ".socket_strerror(socket_last_error())."<br />"; */
            } else { //Общение
                //echo 'Сервер сказал: '; $awr = socket_read($socket, 1024); echo $awr."<br />";
                //$msg = "Hello Сервер!"; echo "Говорим серверу \"".$msg."\"..."; socket_write($socket, $msg, strlen($msg));
            }

            if (isset($socket)) socket_close($socket);

            //воткнуть паузу для того, чтобы сервак мог нормально завершить работу
            usleep(500000);

            $this->status($pidfile);
            Yii::app()->end();
        } elseif ($act == 'status') { //Если действите старт не произошло и игра не инициализирована, то выходим
            $this->status($pidfile);
            Yii::app()->end();
        }
    }

    function status($pidfile)
    {

        if (file_exists($pidfile)) {
            $pid = file_get_contents($pidfile);

            //получаем статус процесса
            $output = null;
            $os_type = DetectOS::getOS();
            if($os_type == 2) {
                exec('tasklist /FI "PID eq '.$pid.'"', $output);
            } else {
                exec("ps aux " . $pid, $output);
            }

            if (count($output) > 1) {//Если в результате выполнения больше одной строки то процесс есть! т.к. первая строка это заголовок, а вторая уже процесс
                echo "{color:\"green\",msg:\"[<b>" . date("Y.m.d-H:i:s") . "</b>] ws echo server is running with PID =" . $pid . "<br />";
                if($os_type == 2) {
                    echo $output[3] . "\"}";//строка с информацией о процессе
                }else{
                    echo $output[0] . "<br />";//строка с информацией о процессе
                    echo $output[1] . "\"}";//строка с информацией о процессе
                }
                return;
            } else {
                //pid-файл есть, но процесса нет
                echo "{color:\"red\",msg:\"[<b>" . date("Y.m.d-H:i:s") . "</b>] ws echo server is down cause abnormal reason with PID =" . $pid . "<br />\"}";
                return;
            }
        }
        echo "{color:\"grey\",msg:\"[<b>" . date("Y.m.d-H:i:s") . "</b>] ws echo server is off, press start\"}";
    }

    function getstatus($pidfile)
    {

        if (file_exists($pidfile)) {
            $pid = file_get_contents($pidfile);

            //получаем статус процесса
            $output = null;
            $os_type = DetectOS::getOS();
            if($os_type == 2) {
                exec('tasklist /FI "PID eq '.$pid.'"', $output);
            } else {
                exec("ps aux " . $pid, $output);
            }

            if (count($output) > 1) {//Если в результате выполнения больше одной строки то процесс есть! т.к. первая строка это заголовок, а вторая уже процесс
                return $pid;
            } else {
                //pid-файл есть, но процесса нет
                return -1;
            }
        }
        return -1;//файла и процесса нет
    }
}