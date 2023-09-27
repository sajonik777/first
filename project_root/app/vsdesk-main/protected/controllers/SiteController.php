<?php

class SiteController extends Controller
{
    public $layout = '//layouts/design3';

    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CaptchaExtendedAction',
                'mode' => 'WORDS'

            ),
            // page action renders "static" pages stored under 'protected / views / site / pages'
            // They can be accessed via: index.php?r = site / page & view = FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function filters()
    {
        return array(
            'accessControl',// perform access control for CRUD operations
        );
    }

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array('push', 'addSubscriber', 'delsubscriber', 'getevents'),
                'roles' => array('@'),
            ),
        );
    }


    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */

    public function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->user->isGuest) {
            if (Yii::app()->params['allowportal'] == 1) {
                $this->redirect(array('/portal'));
            } else {
                $this->redirect(array('site/login'));
            }
        }
        $model = new Request('searchmain');
        $news = new News('searchmain');
        $problems2 = new Problems('searchmain');
        $connection = Yii::app()->db;

        $criteria = new CDbCriteria;
        $criteria->limit = 5;
        $criteria->order = ' id DESC';
        $user_sql = 'SELECT * FROM `CUsers` `t` WHERE `t`.`id`=' . Yii::app()->user->id . ' LIMIT 1';
        $user = Yii::app()->user->id ? $connection->createCommand($user_sql)->queryRow() : '';
        if (!Yii::app()->user->isGuest) {
            $username = $user['fullname'];
        } else {
            $username = 'Гость';
        }
        $role_sql = 'SELECT * FROM `roles` `t` WHERE `t`.`value`="' . Yii::app()->user->role . '" LIMIT 1';
        $role_name = $connection->createCommand($role_sql)->queryRow();
        if ($role_name) {
            if (!Yii::app()->user->checkaccess('systemAdmin')) {
                $criteria->compare('access', $role_name['name'], true);
            }
        }
        $faq = Knowledge::model()->findAll($criteria);
        //$model->unsetAttributes(); // clear any default values
        $services_sql = 'SELECT * FROM `service`';
        $services = $connection->createCommand($services_sql)->queryAll();
        $problems = Problems::model()->countByAttributes([
            'status' => 'Зарегистрирована',
        ]);
        if ((int)$problems > 0) {
            foreach ($services as $service) {
                //$request_sql = 'SELECT * FROM `problems` `t` WHERE `t`.`status`= "Зарегистрирована" AND MONTH(`t`.`timestamp`) = MONTH(NOW()) AND YEAR(`t`.`timestamp`) = YEAR(NOW()) AND `t`.`service`= "'.$service['name'].'"';
                //$request = $connection->createCommand($request_sql)->queryAll();
                $pcount = Problems::model()->countByAttributes([
                    'status' => 'Зарегистрирована',
                    'service' => $service['name'],
                ]);
                if ((int)$pcount > 0) {
                    $data6[] = $service['name'];
                    $data7[] = (int)$pcount;
                }
            }
        } else {
            $data6[] = '';
            $data7[] = 0;
        }
        if(Yii::app()->user->checkAccess('showTicketCalendar')){
            $dp = $model->searchmain();
            $allCronReqs = $dp->getData();
            $json = [];
            if (!empty($allCronReqs)) {
                foreach ($allCronReqs as $cronReq) {
                    $status = Status::model()->findByAttributes(array('name' => $cronReq->Status));
                    /** @var $cronReq CronReq */
                        $json[] = [
                            'id' => $cronReq->id,
                            'title' => $cronReq->Name,
                            'overlap' => true,
                            'start' => date("H:i", strtotime($cronReq->EndTime)),
                            'end' => date("H:i", strtotime($cronReq->EndTime . "+1 minutes")),
                            'color' =>  $status->tag,
                            'allDay' => false,
                            'dow' => [0, 1, 2, 3, 4, 5, 6],
                            'ranges' => [
                                [
                                    'start' => date("Y-m-d H:m:i", strtotime($cronReq->timestampEnd)),
                                    'end' => date('Y-m-d', strtotime($cronReq->timestampEnd)). ' 23:59:59',
                                ]
                            ]
                        ];
                }
            }

        } else {
            $json = NULL;
        }
        $graph2 = $this->getGraf();
        $know = new Knowledge('search');
        $this->render('index', array(
            'model' => $model,
            'know' => $know,
            'news' => $news,
            'problems' => $problems2,
            'username' => $username,
            'faq' => $faq,
            'data6' => $data6,
            'data7' => $data7,
            'graph2' => $graph2[0],
            'data5' => $graph2[1],
            'name' => $graph2[2],
            'json' => json_encode($json),
        ));
    }

    public function actionEnvironment()
    {
        if(Yii::app()->user->checkAccess('systemAdmin')){
            $this->layout = '//layouts/envlayout';
            $this->render('env', array());
        }else{
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    private function getGraf()
    {
        $connection = Yii::app()->db;
        $criteria = new CDbCriteria();
        $startDate = date('Y-m-d', strtotime(date("Y-m-d H:i:s"). "-1 month"));
        $endDate = date('Y-m-d', strtotime(date("Y-m-d H:i:s"). "+1 month"));
        $criteria->select = 'id';
        $statuses = Status::model()->findAllByAttributes(array('show' => 1));
        $graph = array();
        $data3 = array();
        $name = null;
        $user = CUsers::model()->findByPk(Yii::app()->user->id);
        foreach ($statuses as $status_name) {
            $request = array();
            if (Yii::app()->user->checkAccess('mainGraphAllGroupsManagers')) {
                $groups = Groups::model()->findAll();
                foreach ($groups as $group) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`groups_id`=\''.$group->id.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[] = (int)$count;
                    $data3[] = $group->name;
                }
                $name = 'по группам исполнителей';
            } elseif (Yii::app()->user->checkAccess('mainGraphAllUsers')) {
                $declarers = CUsers::model()->findAllByAttributes(array('active' => 1));
                foreach ($declarers as $declarer) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`CUsers_id`=\''.$declarer->Username.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[] = (int)$count;
                    $data3[] = $declarer->fullname;
                }
                $name = 'по заявителям';
            } elseif (Yii::app()->user->checkAccess('mainGraphManagers')) {
                $managers = CUsers::model()->All();
                foreach ($managers as $key => $value) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`Managers_id`=\''.$key.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[] = (int)$count;
                    $data3[] = $value;
                }
                $name = 'по исполнителям';
            } elseif (Yii::app()->user->checkAccess('mainGraphAllCompanys')) {
                $companys = Companies::model()->findAll();
                foreach ($companys as $company) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`company`=\''.$company->name.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[] = (int)$count;
                    $data3[] = $company->name;
                }
                $name = 'по компаниям';
            } elseif (Yii::app()->user->checkAccess('mainGraphCurentUserStatus')) {
                if (Yii::app()->user->checkAccess('systemUser')) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`CUsers_id`=\''.$user->Username.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[] = (int)$count;
                    $data3[] = $user->fullname;
                    $name = 'текущего пользователя';
                } elseif (Yii::app()->user->checkAccess('systemManager')) {
                    $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`Managers_id`=\''.$user->Username.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                    $count = $connection->createCommand($query)->queryScalar();
                    $request[] = (int)$count;
                    $data3[] = $user->fullname;
                    $name = 'текущего исполнителя';
                }
            } elseif (Yii::app()->user->checkAccess('mainGraphCompanyCurentUserStatus')) {
                $query = 'SELECT COUNT(*) FROM `request` `t` WHERE `t`.`company`=\''.$user->company.'\' AND `t`.`Status`=\''.$status_name->name.'\' AND (timestamp BETWEEN \''.$startDate . ' 00:00:00\' AND \''.$endDate . ' 00:00:00\');';
                $count = $connection->createCommand($query)->queryScalar();
                $request[] = (int)$count;
                $data3[] = $user->company;
                $name = 'по компании';
            }

            $t = array(
                'name' => $status_name->name,
                'data' => $request,
                'color' => $status_name->tag,
            );
// echo '<pre>';
//             var_dump($t);
//             echo '</pre>';

            $graph[] = $t;
        }

        return [$graph, $data3, $name];
    }

    public function actionFreeSearch()
    {
        if (isset($_POST) AND $_POST['search_field'] !== '') {
            $keyword = $_POST['search_field'];
            $role = CUsers::model()->findByPk(Yii::app()->user->id);
            $role_name = Roles::model()->findByAttributes(array('value' => $role->role_name));
            $search = str_replace(" ", "|", $keyword);
            $connection = Yii::app()->db;
            $sql = 'SELECT * FROM `brecords` `t` WHERE ((LOWER(`t`.`name`) RLIKE "' . mb_strtolower($search) . '") OR (LOWER(`t`.`content`) RLIKE "' . mb_strtolower($search) . '")) AND (`t`.`access` LIKE "%' . $role_name->name . '%")';
            $model = $connection->createCommand($sql)->queryAll();
            unset($_POST['search_field']);
            $this->render('_freesearch', array('model' => $model));
        } else {
            $this->redirect('/');
        }
    }


    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    // вспомогательная функция, возвращает шестнадцатеричный дамп строки
    // для NTLM авторизации
    private function hex_dump($str)
    {
        return substr(preg_replace('#.#se', 'sprintf("%02x ",ord("$0"))', $str), 0, -1);
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        //var_dump(Yii::app()->ldap_conf);
        //exit;
        $fastAuth = Yii::app()->session->get('fastAuth');
        if (
            Yii::app()->ldap_conf->ad_enabled == 1
            and Yii::app()->ldap_conf->fastAuth == 1
            and (empty($fastAuth) or $fastAuth == 'yes')
        ) {
            $headers = apache_request_headers();        // получаем все заголовки клиента
            if (!isset($headers['Authorization'])) {    // если заголовка авторизации нет
                header('HTTP/1.1 401 Unauthorized');    // требуем от клиента авторизации
                header('WWW-Authenticate: NTLM');       // тип требуемой авторизации - NTLM
                Yii::app()->end();                      // завершаем выполнение скрипта
            }
            // заголовок авторизации от клиента пришёл
            if (substr($headers['Authorization'], 0, 5) == 'NTLM ') {         // проверяем, что это NTLM-аутентификация
                $chain = base64_decode(substr($headers['Authorization'], 5)); // получаем декодированное значение
                $domain = null;
                $user = null;
                $host = null;
                // смотрим номер этапа процесса идентификации
                switch (ord($chain{8})) {
                    case 3: // этап 5 - приём сообщения type-3
                        foreach (array('LM_resp', 'NT_resp', 'domain', 'user', 'host') as $k => $v) {
                            extract(unpack('vlength/voffset', substr($chain, $k * 8 + 14, 4)));
                            $val = substr($chain, $offset, $length);
                            //echo "$v: " . ($k < 2 ? $this->hex_dump($val) : iconv('UTF-16LE', 'UTF-8', $val)) . "<br>\r\n";

                            if ($v == 'domain') {
                                $domain = ($k < 2 ? $this->hex_dump($val) : iconv('UTF-16LE', 'UTF-8', $val));
                            }
                            if ($v == 'user') {
                                $user = ($k < 2 ? $this->hex_dump($val) : iconv('UTF-16LE', 'UTF-8', $val));
                            }
                            if ($v == 'host') {
                                $host = ($k < 2 ? $this->hex_dump($val) : iconv('UTF-16LE', 'UTF-8', $val));
                            }
                        }
                        //echo $domain.'<br>';
                        //echo $user.'<br>';
                        //echo $host.'<br>';

                        $model = new LoginForm;
                        $model->username = $user;
                        $model->password = 'fastAuth';
                        //$model->domain = $domain;

                        if ($model->validate() && $model->login()) {
                            $this->redirect(Yii::app()->user->returnUrl);
                        }

                        Yii::app()->end();
                    case 1: // этап 3 (тут было == 0xB2, я исправил на 130). 178 -> B2 или 130 -> 82
                        // 0x82 возвращают мозилла и опера при обычном вводе руками, а 0xB2 возвращает IE при параметре "исользовать текущие логин и пароль"
                        if (ord($chain{13}) == 0x82 || ord($chain{13}) == 0xB2) { // проверяем признак NTLM 0x82 по смещению 13 в сообщении type-1:
                            $chain = "NTLMSSP\x00" .// протокол
                                "\x02" /* номер этапа */ . "\x00\x00\x00\x00\x00\x00\x00" .
                                "\x28\x00" /* общая длина сообщения */ . "\x00\x00" .
                                "\x01\x82" /* признак */ . "\x00\x00" .
                                "\x00\x02\x02\x02\x00\x00\x00\x00" . // nonce
                                "\x00\x00\x00\x00\x00\x00\x00\x00";
                            header('HTTP/1.1 401 Unauthorized');
                            header('WWW-Authenticate: NTLM ' . base64_encode($chain)); // отправляем сообщение type-2
                            Yii::app()->end();
                        }
                }
            }

        }


        $this->layout = '//layouts/login';
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $message = 'User ' . Yii::app()->user->name . ' log in to system';
                Yii::log($message, 'info', 'LOGIN');
                $redirect = Yii::app()->params['redirectUrl'] ? Yii::app()->params['redirectUrl'] : Yii::app()->user->returnUrl;
                $this->redirect($redirect);
            } else {
                $message = 'User ' . $_POST['LoginForm']['username'] . ' failed to log in to system';
                Yii::log($message, 'error', 'LOGIN');
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionRegister()
    {
        $this->layout = '//layouts/login';
        $model = new RegisterForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['RegisterForm'])) {
            $model->attributes = $_POST['RegisterForm'];
            $model->lang = Yii::app()->params['languages'];
            if ($model->save()) {
                $message_log = 'User ' . $_POST['RegisterForm']['Username'] . ' succesfully registered in to system';
                Yii::log($message_log, 'info', 'REGISTER');
                Yii::app()->user->setFlash('info', Yii::t('main-ui',
                    '<strong>Congratulations!</strong> You successfully register. Check your email for password'));
                $template = Messages::model()->findByAttributes(array('name'=>'{registration}'));
                if(isset($template)){
                  $message = Yii::t('message', "$template->content", array(
                    '{login}' => $_POST['RegisterForm']['Username'],
                    '{password}' => $_POST['RegisterForm']['Password'],
                  ));;
                  $this->Mailsend($_POST['RegisterForm']['Email'], $template->subject, $message);

                }else{
                  $message = Yii::t('main-ui',
                          '<h2>Successfull registration in Univef service desk.</h2><br/><b>Your login:</b> ') . $_POST['RegisterForm']['Username'] . Yii::t('main-ui',
                          '<br/><b>Your password:</b> ') . $_POST['RegisterForm']['Password'] . Yii::t('main-ui',
                          '<br/><br/>Now you can add your request here: ') . Yii::app()->params->homeUrl;
                          $this->Mailsend($_POST['RegisterForm']['Email'], Yii::t('main-ui', 'Univef Registration'), $message);
                }
                if (isset($_POST['RegisterForm']['Email'])){
                    $ticks = Request::model()->findAllByAttributes(array('fullname' => $_POST['RegisterForm']['Email']));
                    if(isset($ticks)){
                        foreach($ticks as $item){
                            Request::model()->updateByPk($item->id,array('fullname'=>$_POST['RegisterForm']['fullname'], 'CUsers_id'=>$_POST['RegisterForm']['Username'], 'phone'=>$_POST['RegisterForm']['Phone'], 'company'=>$_POST['RegisterForm']['company']?$_POST['RegisterForm']['company']:NULL)); 
                        }
                    }
                }
                $this->redirect('login');
            }

        }
        // display the login form
        if (Yii::app()->params->allow_register) {
            $this->render('register', array('model' => $model));
        } else {
            $this->redirect('login');
        }

    }

    public function actionRecovery()
    {
        $this->layout = '//layouts/login';
        $model = new RecoveryForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'recovery-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['RecoveryForm'])) {
            $model->attributes = $_POST['RecoveryForm'];
            if ($model->validate()) {
                $salt = uniqid('', true);
                CUsers::model()->updateByPk($model->Username, array('push_id' => $salt));
                $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(array("/site/reset")),
                        array("email" => $_POST['RecoveryForm']['Email'], "code" => $salt));
                Yii::app()->user->setFlash('info', Yii::t('main-ui',
                    '<strong>Congratulations!</strong> Your request for password recovery has been sent. Check Email for password change.'));
                $message = Yii::t('main-ui',
                        'You have requested password recovery. Click the link to change the password ') . $activation_url;
                $this->Mailsend($_POST['RecoveryForm']['Email'], Yii::t('main-ui', 'Recovery password request'),
                    $message);
                $this->redirect('login');
            }
        }
        // display the login form
        if (Yii::app()->params->allow_register) {
            $this->render('recovery', array('model' => $model));
        } else {
            $this->redirect('login');
        }

    }

    public function actionReset($email, $code)
    {
        $this->layout = '//layouts/login';
        $model = new ResetForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'reset-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['ResetForm']) AND isset($email) AND isset($code)) {
            $user = CUsers::model()->findByAttributes(array('Email' => $email, 'push_id' => $code));
            if (isset($user)) {
                $model->attributes = $_POST['ResetForm'];
                $password = $_POST['ResetForm']['password'];
                $new_password = md5('mdy65wtc76' . $password);
                CUsers::model()->updateByPk($user->id, array('Password' => $new_password, 'push_id' => null));
                Yii::app()->user->setFlash('info', Yii::t('main-ui',
                    '<strong>Congratulations!</strong> Your request for password change complete successfully. Type new password to login!'));
                $this->redirect('login');
            }
        }
        // display the login form
        if (Yii::app()->params->allow_register) {
            $this->render('reset', array('model' => $model));
        } else {
            $this->redirect('login');
        }

    }

    public function Mailsend($address, $subject, $message)
    {
        $afiles = array();
        SendMail::send($address, $subject, $message, $afiles);
    }

    public function actionToggle()
    {
        $cookie = $_COOKIE['NAVCOLLAPSE'];
        if ($cookie == "1") {
            setcookie("NAVCOLLAPSE", "2", time() + 2400000, "/");
        } else {
            setcookie("NAVCOLLAPSE", "1", time() + 2400000, "/");
        }
        echo $_COOKIE['NAVCOLLAPSE'];
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        $message = 'User ' . Yii::app()->user->name . ' log out of system';
        Yii::log($message, 'info', 'LOGOUT');
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Загрузка картинок для redactorRow
     */
    public function actionImageUpload()
    {
        if (!Yii::app()->user->checkAccess('uploadFilesRequest')) {
            exit;
        }

        $path = '/media/redactor/';
        $dir = ROOT_PATH . $path;
        $_FILES['file']['type'] = strtolower($_FILES['file']['type']);

        if ($_FILES['file']['type'] == 'image/png'
            || $_FILES['file']['type'] == 'image/jpg'
            || $_FILES['file']['type'] == 'image/gif'
            || $_FILES['file']['type'] == 'image/jpeg'
            || $_FILES['file']['type'] == 'image/pjpeg'
        ) {
            $filid = md5(date('YmdHis'));
            $filename = $filid . '.jpg';
            $file = $dir . $filename;

            if (!is_dir($dir)) {
                mkdir($dir);
            }

            move_uploaded_file($_FILES['file']['tmp_name'], $file);

            $array = array(
                //'filelink' => Yii::app()->request->hostInfo . $path . $filename,
                'url' => $path . $filename,
                'id' => $filid
            );

            echo stripslashes(json_encode($array));
        } else {
            $array = array(
                'error' => true,
                'message' => 'File no allow'
            );
            echo stripslashes(json_encode($array));
        }
    }

    /**
     * Загрузка файлов для redactorRow
     */
    public function actionFileUpload()
    {
        if (!Yii::app()->user->checkAccess('uploadFilesRequest')) {
            exit;
        }

        $path = '/media/redactor/';
        $dir = ROOT_PATH . $path;

        $file = CUploadedFile::getInstanceByName('file');

        $exts = explode(',', Yii::app()->params->extensions);

        if (
            (in_array($file->extensionName, $exts))
            and
            (((int)$file->size) < ((int)Yii::app()->params->max_file_size * 1024))
        ) {
            $filename = md5(date('YmdHis')) . '.' . $file->extensionName;
            $file = $dir . $filename;

            if (!is_dir($dir)) {
                mkdir($dir);
            }

            move_uploaded_file($_FILES['file']['tmp_name'], $file);

            $array = array(
                'filelink' => $path . $filename,
                'filename' => $filename
            );
            echo stripslashes(json_encode($array));

        } else {
            $array = array(
                'error' => true,
                'message' => 'File no allow'
            );
            echo stripslashes(json_encode($array));
        }

    }

    public function actionGetmsgcount()
    {
        $newMessages = null;
        if (Yii::app()->user->checkAccess('readChat')) {
            $fullname = CUsers::model()->findByPk(Yii::app()->user->id);
            $connection = Yii::app()->db;
            $sql = "SELECT COUNT(*) FROM chat WHERE (`reader`='" . $fullname->fullname . "' AND `rstate`=0) OR (`reader`='main' AND `name` != '" . $fullname->fullname . "' AND `rstate`=0)";
            $count = $connection->createCommand($sql)->queryScalar();
            if ($count) {
                $newMessages = $count;
            }
            echo $newMessages;
        }
    }

    public function actionGetprivcount()
    {
        $name = $_POST['reader'];
        if ($name !== 'main') {
            $newMessages = null;
            if (Yii::app()->user->checkAccess('readChat')) {
                $reader = CUsers::model()->findByPk(Yii::app()->user->id);
                $user = CUsers::model()->findByAttributes(array('fullname' => $name));
                $connection = Yii::app()->db;
                $sql = "SELECT COUNT(*) FROM chat WHERE (`reader`='" . $reader->fullname . "' AND `name` = '" . $name . "' AND `rstate`=0)";
                $count = $connection->createCommand($sql)->queryScalar();
                if ($count AND $count > 0) {
                    $json = [
                        'name' => $user->Username,
                        'count' => $count,
                    ];
                    $newMessages = json_encode($json);
                    echo $newMessages;
                }
            }
        } else {
            $newMessages2 = null;
            if (Yii::app()->user->checkAccess('readChat')) {
                $reader = CUsers::model()->findByPk(Yii::app()->user->id);
                $connection = Yii::app()->db;
                $sql = "SELECT COUNT(*) FROM chat WHERE (`reader`='main' AND `name` != '" . $reader->fullname . "' AND `rstate`=0)";
                $count = $connection->createCommand($sql)->queryScalar();
                if ($count AND $count > 0) {
                    $json2 = [
                        'name' => 'main',
                        'count' => $count,
                    ];
                    $newMessages2 = json_encode($json2);
                    unset($connection, $count, $sql);
                    echo $newMessages2;
                }
            }
        }

    }

    /**
     * Отправка push уведомлений
     */
    public function actionPush()
    {
        $endpoint = isset($_POST['url']) ? $_POST['url'] : null;
        if (null !== $endpoint) {
            PushAPI::push($endpoint);
        } else {
            echo '{"response": "ERROR"}';
        }
    }

    /**
     * Регистрация подписчика на push уведомления.
     */
    public function actionAddSubscriber()
    {
        $token = isset($_POST['token']) ? $_POST['token'] : null;
        if (null !== $token and !Yii::app()->user->isGuest) {
            echo PushAPI::add($token, Yii::app()->user->id);
        } else {
            echo '{"response": "ERROR"}';
        }
    }

    /**
     * Отписка от push уведомлений.
     */
    public function actionDelSubscriber()
    {
        $token = isset($_POST['token']) ? $_POST['token'] : null;
        if (null !== $token and !Yii::app()->user->isGuest) {
            echo PushAPI::del($token);
        } else {
            echo '{"response": "ERROR"}';
        }
    }

    /**
     * Проверка наличия модулей.
     */
    public function HasModule($module){
        $pathAlias = 'application.modules.'.strtolower($module);
        $path = Yii::getPathOfAlias($pathAlias);
        $class = $module.'Module';
        if(file_exists($path) and is_dir($path) and file_exists(Yii::getPathOfAlias($pathAlias.'.'.$class).'.php')){
            Yii::import($pathAlias.'.'.$class);
            return in_array('CWebModule', class_parents ($class));
        }
        return false;
    }
}
