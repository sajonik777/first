<?php

class CusersController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/design3';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['index'],
                'roles' => ['listUser'],
            ],
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['view', 'call', 'getFullAddress'],
                'roles' => ['viewUser'],
            ],
            [
                'allow',
                'actions' => ['call'],
                'roles' => ['amiCalls'],
            ],
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['create', 'selectGroup', 'fastadd', 'get_attr'],
                'roles' => ['createUser'],
            ],
            [
                'allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['update', 'selectGroup', 'delimage'],
                'roles' => ['updateUser'],
            ],
            [
                'allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['delete'],
                'roles' => ['deleteUser'],
            ],
            [
                'allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['batchdelete'],
                'roles' => ['batchDeleteUser'],
            ],
            [
                'allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => ['export'],
                'roles' => ['exportUser'],
            ],

            [
                'deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function behaviors()
    {
        return array(
            'eexcelview' => array(
                'class' => 'ext.eexcelview.EExcelBehavior',
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $units = Cunits::model()->findAllByAttributes(array('user' => $model->Username));
        $this->render('view', array(
            'model' => $model,
            'units' => $units,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->user->checkAccess('systemUser')) {
            $model = CUsers::model()->findByPk(Yii::app()->user->id);
        } else {
            $model = CUsers::model()->findByPk($id);
        }
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function actionExport()
    {
        $connection = Yii::app()->db;
        $columns_query = 'SELECT * FROM `tbl_columns` `t` WHERE `t`.`id`="cusers-grid_'.Yii::app()->user->id.'"';
        $columns = $connection->createCommand($columns_query)->queryAll();
        $columns_array = explode('||', $columns[0]['data']);
        if (!empty($columns)) {
            foreach ($columns_array as $item) {
                if ($item !== 'Действия') {
                    if ($item !== 'slabel') {
                        $new_arr[]['name'] = $item;
                    } else {
                        $new_arr[]['name'] = 'status';
                    }
                }
            }
            $this->toExcel($_SESSION['users_records'],
                $columns = $new_arr,

                Yii::t('main-ui', 'Users'),
                array(
                    'creator' => 'Univef',
                    'title' => Yii::t('main-ui', 'Users'),
                ),
                'Excel5'
            );
        } else {
            throw new CHttpException(500, Yii::t('main-ui', 'Select columns settings to export data.'));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new CUsers;
        $lang = array();
        $lang_dir = dirname(__FILE__) . '/../messages/';
        $list = $this->myscandir($lang_dir, 0);
        foreach ($list as $key => $value) {
            $lang[$value] = Yii::t('main-ui', $value);
        }

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['CUsers'])) {
            $model->attributes = $_POST['CUsers'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'lang' => $lang,
        ));
    }

    public function actionFastAdd($call, $ticket)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new FRegisterForm;
        $model2 = new Companies;
        // Uncomment the following line if AJAX validation is needed
        if (isset($_POST['ajax'])) {
            if ($_POST['ajax']=='adduser-form') {
                echo CActiveForm::validate($model);
            }
            Yii::app()->end();
        }
        if (isset($_POST) AND !empty($_POST)) {
            if (isset($_POST['FRegisterForm'])) {
                $model->Username = $_POST['FRegisterForm']['Username'];
                $model->fullname = $_POST['FRegisterForm']['fullname'];
                $model->Password = $_POST['FRegisterForm']['Password'];
                $model->Phone = $_POST['FRegisterForm']['Phone'];
                $model->Email = $_POST['FRegisterForm']['Email'];
                $model->tbot = $_POST['FRegisterForm']['tbot'];
                $model->vbot = $_POST['FRegisterForm']['vbot'];
                $model->msbot = $_POST['FRegisterForm']['msbot'];
                $model->lang = Yii::app()->params['languages'];
            }
            if (isset($_POST['FRegisterForm']['company']) AND !empty(trim($_POST['FRegisterForm']['company']))) {
                $company = Companies::model()->findByAttributes(array('name' => $_POST['FRegisterForm']['company']));
                if (!isset($company)) {
                    $model2->name = $_POST['FRegisterForm']['company'];
                    $model2->save(false);
                    $model->company = $_POST['FRegisterForm']['company'];
                } else {
                    $model->company = $company->name;
                }
            }
            if ($model->save(false)) {
                Yii::app()->user->setFlash('info', Yii::t('main-ui',
                  '<strong>Congratulations!</strong> You successfully register. Check your email for password'));
                if(isset($_POST['FRegisterForm']['Email']) AND !empty($_POST['FRegisterForm']['Email'])){
                    $template = Messages::model()->findByAttributes(array('name'=>'{registration}'));
                if (isset($template)) {
                    $message = Yii::t('message', "$template->content", array(
                        '{login}' => $_POST['FRegisterForm']['Username'],
                        '{password}' => $_POST['FRegisterForm']['Password'],
                    ));
                    ;
                    SendMail::send($_POST['FRegisterForm']['Email'], $template->subject, $message, array());
                } else {
                    $message = Yii::t('main-ui',
                        '<h2>Successfull registration in Univef service desk.</h2><br/><b>Your login:</b> ') . $_POST['FRegisterForm']['Username'] . Yii::t('main-ui',
                        '<br/><b>Your password:</b> ') . $_POST['FRegisterForm']['Password'] . Yii::t('main-ui',
                        '<br/><br/>Now you can add your request here: ') . Yii::app()->params->homeUrl;
                        Sendmail::send($_POST['FRegisterForm']['Email'], Yii::t('main-ui', 'Univef Registration'), $message, array());
                    }
                }
                    if (!empty($call)) {
                        Calls::model()->updateByPk($call, array('dialer_name'=>$_POST['FRegisterForm']['fullname'], 'dialer'=>$_POST['FRegisterForm']['Username']));
                        $this->redirect(array('/calls/view', 'id' => $call));
                    }
                    if (!empty($ticket)) {
                        if (isset($_POST['FRegisterForm']['Email'])) {
                            $ticks = Request::model()->findAllByAttributes(array('fullname' => $_POST['FRegisterForm']['Email']));
                            if (isset($ticks)) {
                                foreach ($ticks as $item) {
                                    Request::model()->updateByPk($item->id, array('fullname'=>$_POST['FRegisterForm']['fullname'], 'CUsers_id'=>$_POST['FRegisterForm']['Username'], 'phone'=>$_POST['FRegisterForm']['Phone'], 'company'=>$_POST['FRegisterForm']['company'], 'Address'=>$company->uraddress?$company->uraddress:null));
                                }
                            }
                        }
                        Request::model()->updateByPk($ticket, array('fullname'=>$_POST['FRegisterForm']['fullname'], 'CUsers_id'=>$_POST['FRegisterForm']['Username'], 'phone'=>$_POST['FRegisterForm']['Phone'], 'company'=>$_POST['FRegisterForm']['company'], 'Address'=>$company->uraddress?$company->uraddress:null));
                        $this->redirect(array('/request/view', 'id' => $ticket));
                    } else if ($ticket == NULL) {
                        $this->redirect(array('/request/createfromcall?user='.$_POST['FRegisterForm']['fullname'].'&call='));
                    }
                }
            } else {
                throw new CHttpException(403, Yii::t('main-ui', 'You dont have rights to access this element!'));
            }
        }

        public function myscandir($dir, $sort = 0)
        {
            $list = scandir($dir, $sort);

        // если директории не существует
            if (!$list) {
                return false;
            }

        // удаляем . и .. (я думаю редко кто использует)
            if ($sort == 0) {
                unset($list[0], $list[1]);
            } else {
                unset($list[count($list) - 1], $list[count($list) - 1]);
            }
            return $list;
        }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cusers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $oldusername = $model->fullname;
        $lang = array();
        $lang_dir = __DIR__ . '/../messages/';
        $list = $this->myscandir($lang_dir, 0);
        foreach ($list as $key => $value) {
            $lang[$value] = Yii::t('main-ui', $value);
        }
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        if (isset($_POST['CUsers'])) {
            $model->attributes = $_POST['CUsers'];

            $model->image = CUploadedFile::getInstance($model, 'image');

            if ($model->save()) {

                if($model->image){
                    $path = __DIR__ . '/../../media/userphoto/' . $model->id . '.png';
                    $model->image->saveAs($path);
                    /** @var CImageComponent $image */
                    $image = Yii::app()->image->load($path);
                    $image->resize(96, 96);
                    $image->save();
                    $model->photo = 1;
                    $model->save(false);
                }

                if ($oldusername !== $_POST['CUsers']['fullname']) { //если изменилось имя пользователя
                    $connection = Yii::app()->db;
                    //обновляем в сервисах имя наблюдателя
                    $serv= 'SELECT * FROM `service` WHERE `watcher` LIKE \'%'.$oldusername.'%\'';
                    $services = $connection->createCommand($serv)->queryAll();
                    foreach ($services as $service) {
                        if (isset($service['watcher'])) {
                            $watchers = explode(',', $service['watcher']);
                            $newwatcher = array();
                            foreach ($watchers as $watcher) {
                                if ($watcher == $oldusername) {
                                    $newwatcher[] = $_POST['CUsers']['fullname'];
                                } else {
                                    $newwatcher[] = $watcher;
                                }
                            }
                            Service::model()->updateByPk($service['id'], array('watcher' => implode(',', $newwatcher)));
                        }
                    }

                    // обновляем в заявках имя наблюдателя
                    $tickets= 'SELECT * FROM `request` WHERE `watchers` LIKE \'%'.$oldusername.'%\'';
                    $ticket = $connection->createCommand($tickets)->queryAll();
                    if (isset($ticket) and !empty($ticket)) {
                        foreach ($ticket as $item) {
                            if (isset($item['watchers'])) {
                                $watchers = explode(',', $item['watchers']);
                                $newwatcher = array();
                                foreach ($watchers as $watcher) {
                                    if ($watcher == $oldusername) {
                                        $newwatcher[] = $_POST['CUsers']['fullname'];
                                    } else {
                                        $newwatcher[] = $watcher;
                                    }
                                }
                                Request::model()->updateByPk($item['id'], array('watchers' => implode(',', $newwatcher)));
                            }
                        }
                    }
                    //              !!!перенесено в триггеры БД
                    // // обновляем в заявках имя создателя
                    // $tickets= 'SELECT * FROM `request` WHERE `creator` LIKE \''.$oldusername.'\'';
                    // $ticket = $connection->createCommand($tickets)->queryAll();
                    // if (isset($ticket) AND !empty($ticket)){
                    //   foreach ($ticket as $item) {
                    //     if (isset($item['creator'])){
                    //       Request::model()->updateByPk($item['id'], array('creator' => $_POST['CUsers']['fullname']));
                    //     }
                    //   }
                    // }

                    //обновляем имя пользователя в активах и КЕ
                    // $unit= 'SELECT * FROM `cunits` WHERE `fullname` LIKE \''.$oldusername.'\'';
                    // $units = $connection->createCommand($unit)->queryAll();
                    // if (isset($units) AND !empty($units)){
                    //   foreach ($units as $item) {
                    //     if (isset($item['fullname'])){
                    //       Cunits::model()->updateByPk($item['id'], array('fullname' => $_POST['CUsers']['fullname']));
                    //     }
                    //   }
                    // }
                    // $asset= 'SELECT * FROM `asset` WHERE `cusers_fullname` LIKE \''.$oldusername.'\'';
                    // $assets = $connection->createCommand($asset)->queryAll();
                    // if (isset($assets) AND !empty($assets)){
                    //   foreach ($assets as $item) {
                    //     if (isset($item['cusers_fullname'])){
                    //       Asset::model()->updateByPk($item['id'], array('cusers_fullname' => $_POST['CUsers']['fullname']));
                    //     }
                    //   }
                    // }
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $this->render('update', array(
            'model' => $model,
            'lang' => $lang,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        $services = Service::model()->findByAttributes(array('manager' => $model->Username));
        $criteria = new CDbCriteria;
        $criteria->compare('users', $id, true);
        $groups = Groups::model()->findAll($criteria);
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            if ($services or $id == 1) {
                throw new CHttpException(400, 'Вы не можете удалять пользователя, прикрепленного к сервису. Для удаления замените исполнителя сервисов!');
            } else {
                if (Yii::app()->params['zdmanager'] == $model->Username) {
                    throw new CHttpException(400, 'Вы не можете удалять исполнителя, назначенного по-умолчанию! Перейдите в настройки заявок и замените исполнителя по-умолчанию');
                } else {
                    foreach ($groups as $group) {
                        $new_users = array();
                        $old_users = explode(",", $group->users);
                        foreach ($old_users as $item) {
                            if ($item !== $id) {
                                $new_users[] = $item;
                            }
                        }
                        Groups::model()->updateByPk($group->id, array('users' => implode(",", $new_users)));
                    }
                    $this->loadModel($id)->delete();
                }
            }


            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionBatchDelete()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $checkedIDs = $_GET['checked'];
            foreach ($checkedIDs as $id) {
                $model = $this->loadModel($id);
                $services = Service::model()->findByAttributes(array('manager' => $model->Username));
                $criteria = new CDbCriteria;
                $criteria->compare('users', $id, true);
                $groups = Groups::model()->findAll($criteria);
                if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
                    if ($services or $id == 1) {
                        throw new CHttpException(400, 'Вы не можете удалять пользователя, прикрепленного к сервису. Для удаления замените исполнителя сервисов!');
                    } else {
                        if (Yii::app()->params['zdmanager'] == $model->Username) {
                            throw new CHttpException(400, 'Вы не можете удалять исполнителя, назначенного по-умолчанию! Перейдите в настройки заявок и замените исполнителя по-умолчанию');
                        } else {
                            foreach ($groups as $group) {
                                $new_users = array();
                                $old_users = explode(",", $group->users);
                                foreach ($old_users as $item) {
                                    if ($item !== $id) {
                                        $new_users[] = $item;
                                    }
                                }
                                Groups::model()->updateByPk($group->id, array('users' => implode(",", $new_users)));
                            }
                            $this->loadModel($id)->delete();
                        }
                    }
                }
            }

        }
    }

    public function actionSelectGroup()
    {
        $model = new CUsers();
        if (isset($_POST['CUsers']['company'])) {
            $depart_list = Depart::call($_POST['CUsers']['company']);
            echo CHtml::activeLabelEx($model, 'department');
            echo CHtml::activeDropDownList($model, 'department', $depart_list, array('class' => 'span12'));
        }
    }

    public function actionGet_attr()
    {
        //if (isset($_POST['user'])) {
        $user = CUsers::model()->findByAttributes(array('fullname' => $_POST['user']));
        $json = array();
        $json['phone'] = $user->Phone;
        $json['email'] = $user->Email;
        $json['position'] = $user->position;
        $json['company'] = $user->company;
        //}
        echo json_encode($json);
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['usersPageCount'] = $_GET['pageCount'];
        }
        //Yii::app()->user->setFlash('info', Yii::t('main-ui', '<strong>Attention!</strong> Here you can manage user accounts!'));
        $model = new CUsers('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['CUsers'])) {
            $model->attributes = $_GET['CUsers'];
        }
        $this->render('admin', [
            'model' => $model,
        ]);
    }

    /**
     * @throws \PAMI\Client\Exception\ClientException
     */
    public function actionCall()
    {
        if (isset($_POST['number'])) {
            $user = CUsers::model()->findByPk(Yii::app()->user->id);
            if (!empty($user->intphone)) {
                if (Asterisk::call($user->intphone, $_POST['number'])) {
                    die('Ok');
                }
            }
        }

        die('Error');
    }

    /**
     * @param $id
     */
    public function actionDelimage($id)
    {
        /** @var CUsers $user */
        $user = CUsers::model()->findByPk($id);
        if ($user->photo) {
            $user->photo = 0;
            $user->save(false);
            @unlink(__DIR__ . '/../../media/userphoto/' . $user->id . '.png');
        }
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionGetFullAddress() {
        if ($_GET['name']){
            $location_name = $_GET['name'];
        }

        $location = Companies::model()->findByAttributes(array('name'=>$location_name));
        $city = Cities::model()->findByPk($location->city);
        $street = Streets::model()->findByPk($location->street);
        $result = $city->name;
        $result .= ', ' . $street->name;
        if ($location->building) {
            $result .= ', ' . $location->building;
        }
        if ($location->bcorp) {
            $result .= ', корпус ' . $location->bcorp;
        }
        if ($location->bblock) {
            $result .= ', строение ' . $location->bblock;
        }
        echo $result;
    }
}
