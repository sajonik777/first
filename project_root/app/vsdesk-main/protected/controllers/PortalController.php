<?php

class PortalController extends Controller
{
    public $layout = '//layouts/design3';

    public
    function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CaptchaExtendedAction',
                'mode' => 'WORDS'
            ),
        );
    }

    public function actionIndex()
    {
        Yii::app()->session->remove('fields');
        if (Yii::app()->user->isGuest AND Yii::app()->params['allowportal'] == 1) {
            $this->createAction('captcha')->getVerifyCode(true);
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
            if (Yii::app()->user->isGuest) {
                if (!Yii::app()->user->checkaccess('systemAdmin')) {
                    $criteria->compare('access', 'Гость', true);
                }
            }
            $news = new News('searchmain');
            $faq = Knowledge::model()->findAll($criteria);

            $model = new PortalRequest;
            $this->render('index', array(
                'news' => $news,
                'username' => $username,
                'faq' => $faq,
                'model' => $model
            ));
        } else {
            $this->redirect(array('/site/index'));
        }
    }

    public function actionWidget()
    {
        $model = new PortalRequest;
        $this->createAction('captcha')->getVerifyCode(true);
        $this->layout = null;
        $this->renderPartial('widget',[
            'model' => $model
        ], false, true);
    }

    public function actionSelectService()
    {
        $priority = null;
        $priority = Service::model()->findByPk($_POST['PortalRequest']['service_id']);

        echo CJSON::encode(array(
            'fid' => $priority->fieldset,
            'content' => $priority->content,
            'description' => $priority->description,
            'watcher' => explode(',', $priority->watcher),
            'csrf' => Yii::app()->request->csrfToken,
        ));
    }

    public function actionSetFields()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $criteria = new CDbCriteria(array('order' => 'sid ASC'));
        $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $id), $criteria);
        $this->renderPartial('_ajaxform', array('fields' => $fields));
    }

    public function actionSetFields2()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $criteria = new CDbCriteria(array('order' => 'sid ASC'));
        $priority = Service::model()->findByPk($id);
        $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $priority->fieldset), $criteria);
        $this->renderPartial('_ajaxform', array('fields' => $fields));
    }

    public function actionFinish()
    {
        $this->layout = null;
        $this->renderPartial('finish',[], false, true);
    }

    public function actionCreatewidget()
    {
        $this->layout = null;
        $model = new PortalRequest;
        $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
        $model->channel = 'Widget';
        $model->channel_icon = 'fa-solid fa-code';

        if (isset($_POST['PortalRequest'])) {
            $username = CUsers::model()->findByAttributes(array('Email' => $_POST['PortalRequest']['depart'])); //depart validate email
            $nstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 1));
            if ($username) {
                $company = Companies::model()->findByAttributes(array('name' => $username->company));
                if(isset($company) AND !empty($company)){
                    $contracts = Contracts::model()->findAllByAttributes(['customer_id' => $company->id]);
                    foreach ($contracts as $contract){
                        if($contract->stopservice == 1){
                            $expiration = strtotime($contract->tildate);
                            $now = strtotime(date('Y-m-d'));
                            if($now > $expiration){
                                $_POST['PortalRequest'] = NULL;
                                Yii::app()->user->setFlash('danger', Yii::t('main-ui', 'Warning! Contract has expired! You can not create tickets. Prolongate contract: ') . ' №' .$contract->number);
                                //throw new CHttpException(404, Yii::t('main-ui', 'Warning! Contract has expired! You can not create tickets. Prolongate contract: ') . ' №' .$contract->number . ' - ' . $contract->name);
                            }
                        }
                    }
                }
            } else {
                $companies = Companies::model()->findAll();
                foreach ($companies as $comp) {
                    if(!empty($comp->domains) AND (isset($comp->domains))){
                        $domains = explode(',', $comp->domains);
                        foreach ($domains as $domain) {
                            $daddress = substr($_POST['PortalRequest']['depart'], strrpos($_POST['PortalRequest']['depart'], '@')+1);
                            if (trim($domain) == $daddress){
                                $company = $comp;
                            }
                        }
                    }
                }
            }
            $model->Name = $_POST['PortalRequest']['Name'];
            if(Yii::app()->params['WidgetService'] == 0){
                $model->Priority = Yii::app()->params['zdpriority'];
                if (Yii::app()->params['zdtype'] == 1) {
                    $model->Managers_id = Yii::app()->params['zdmanager'];
                    $manager = CUsers::model()->findByAttributes(array('Username' => Yii::app()->params['zdmanager']));
                } else {
                    $model->gfullname = Yii::app()->params['zdmanager'];
                    $group = Groups::model()->findByAttributes(array('name' => Yii::app()->params['zdmanager']));
                    $model->groups_id = $group->id;
                }
                $model->Type = '';
                $model->mfullname = $manager->fullname ? $manager->fullname : null;
            }

            if ($username) {
                $model->CUsers_id = $username->Username;
                $model->fullname = $username->fullname;
                $model->creator = $username->fullname;
                $model->company = $username->company ? $username->company : null;
                $model->company_id = $company->id ? $company->id : NULL;
                $depart = Depart::model()->findByAttributes(['name' => $username->department, 'company' => $username->company]);
                $model->depart_id = $depart->id;
                $model->room = $username->room ? $username->room : null;
                $model->phone = $username->Phone ? $username->Phone : null;
                $model->Address = $company->faddress ? $company->faddress : null;
            } else {
                $model->CUsers_id = $_POST['PortalRequest']['depart'];//depart validate email
                $model->fullname = $_POST['PortalRequest']['depart'];//depart validate email
                $model->creator = $_POST['PortalRequest']['depart'];//depart validate email
                $model->company = $company->name ? $company->name : null;
            }
            $model->depart = $_POST['PortalRequest']['depart'];
            if(Yii::app()->params['WidgetService'] == 1){
                $model->service_id = $_POST['PortalRequest']['service_id'];
            }
            $model->ZayavCategory_id = Yii::t('main-ui', 'Widget ticket');
            $model->Date = date("d.m.Y H:i");
            $model->timestamp = date('Y-m-d H:i:s');
            $model->cunits = '';
            $model->Status = $nstatus->name;
            $model->slabel = $nstatus->label;
            $model->verifyCode = $_POST['PortalRequest']['verifyCode'];
            $model->Content = $_POST['PortalRequest']['Content'];
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::app()->user->setFlash('info',
                        Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
//                    if($manager){
//                        $message = $model->Name . "\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
//                        $url = Yii::app()->createUrl("request/view", array("id" => $model->id));
//                        $manager->pushMessage($message, $url);
//                    }
                }
                $this->redirect(array('widget'));
            }
            if (!$model->validate()) {
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
                $this->layout = null;
                $this->renderPartial('widget',[
                    'model' => $model
                ], false, true);
            }
        }
    }

    public function actionCreate()
    {
        Yii::app()->session->remove('fields');
        $model = new PortalRequest;
        $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
        $model->channel = 'Portal';
        $model->channel_icon = 'fa-solid fa-house';

        if (isset($_POST['PortalRequest'])) {
            $username = CUsers::model()->findByAttributes(array('Email' => $_POST['PortalRequest']['depart'])); //depart validate email
            $nstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 1));
            if ($username) {
                $company = Companies::model()->findByAttributes(array('name' => $username->company));
                if(isset($company) AND !empty($company)){
                    $contracts = Contracts::model()->findAllByAttributes(['customer_id' => $company->id]);
                    foreach ($contracts as $contract){
                        if($contract->stopservice == 1){
                            $expiration = strtotime($contract->tildate);
                            $now = strtotime(date('Y-m-d'));
                            if($now > $expiration){
                                //$_POST['PortalRequest'] = NULL;
                                //Yii::app()->user->setFlash('danger', Yii::t('main-ui', 'Warning! Contract has expired! You can not create tickets. Prolongate contract: ') . ' №' .$contract->number . ' - ' . $contract->name);
                                throw new CHttpException(404, Yii::t('main-ui', 'Warning! Contract has expired! You can not create tickets. Prolongate contract: ') . ' №' .$contract->number);
                            }
                        }
                    }
                }
            } else {
                $companies = Companies::model()->findAll();
                foreach ($companies as $comp) {
                    if(!empty($comp->domains) AND (isset($comp->domains))){
                        $domains = explode(',', $comp->domains);
                        foreach ($domains as $domain) {
                            $daddress = substr($_POST['PortalRequest']['depart'], strrpos($_POST['PortalRequest']['depart'], '@')+1);
                            if (trim($domain) == $daddress){
                                $company = $comp;
                            }
                        }
                    }
                }
            }
            $model->Name = $_POST['PortalRequest']['Name'];
            if(Yii::app()->params['portalAllowService'] == 0){
                $model->Priority = Yii::app()->params['zdpriority'];
                if (Yii::app()->params['zdtype'] == 1) {
                    $model->Managers_id = Yii::app()->params['zdmanager'];
                    $manager = CUsers::model()->findByAttributes(array('Username' => Yii::app()->params['zdmanager']));
                } else {
                    $model->gfullname = Yii::app()->params['zdmanager'];
                    $group = Groups::model()->findByAttributes(array('name' => Yii::app()->params['zdmanager']));
                    $model->groups_id = $group->id;
                }
                $model->Type = '';
                $model->mfullname = $manager->fullname ? $manager->fullname : null;
            }

            if ($username) {
                $model->CUsers_id = $username->Username;
                $model->fullname = $username->fullname;
                $model->creator = $username->fullname;
                $model->company = $username->company ? $username->company : null;
                $model->company_id = $company->id ? $company->id : NULL;
                $depart = Depart::model()->findByAttributes(['name' => $username->department, 'company' => $username->company]);
                $model->depart_id = $depart->id;
                $model->room = $username->room ? $username->room : null;
                $model->phone = $username->Phone ? $username->Phone : null;
                $model->Address = $company->faddress ? $company->faddress : null;
            } else {
                $model->CUsers_id = $_POST['PortalRequest']['depart'];//depart validate email
                $model->fullname = $_POST['PortalRequest']['depart'];//depart validate email
                $model->creator = $_POST['PortalRequest']['depart'];//depart validate email
                $model->company = $company->name ? $company->name : null;
            }
            $model->depart = $_POST['PortalRequest']['depart'];
            if(Yii::app()->params['portalAllowService'] == 1) {
                $model->service_id = $_POST['PortalRequest']['service_id'];
            }
            $model->ZayavCategory_id = Yii::t('main-ui', 'Portal ticket');
            $model->Date = date("d.m.Y H:i");
            $model->timestamp = date('Y-m-d H:i:s');
            $model->cunits = '';
            $model->Status = $nstatus->name;
            $model->slabel = $nstatus->label;
            $model->verifyCode = $_POST['PortalRequest']['verifyCode'];
            $model->Content = $_POST['PortalRequest']['Content'];
            if ($model->validate()) {
                if ($model->save(false)) {
                    Yii::app()->user->setFlash('info',
                        Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
//                    if($manager){
//                        $message = $model->Name . "\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
//                        $url = Yii::app()->createUrl("request/view", array("id" => $model->id));
//                        $manager->pushMessage($message, $url);
//                    }
                }
                $this->redirect(array('index'));
            }
            if (!$model->validate()) {
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
                $news = new News('searchmain');
                $faq = Knowledge::model()->findAll($criteria);
                $this->render('index', array(
                    'news' => $news,
                    'faq' => $faq,
                    'model' => $model
                ));
            }
        }
    }
}
