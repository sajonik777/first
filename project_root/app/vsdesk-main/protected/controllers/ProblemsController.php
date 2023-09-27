<?php

class ProblemsController extends Controller
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
    function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'roles' => array('listProblem'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('view'),
                'roles' => array('viewProblem'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'createh'),
                'roles' => array('createProblem'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('deletefile', 'update'),
                'roles' => array('updateProblem'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('assign'),
                'roles' => array('canAssignProblem'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('deleteProblem'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('batchdelete'),
                'roles' => array('batchDeleteProblem'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('batchupdate'),
                'roles' => array('batchUpdateProblem'),
            ),

            array('deny', // deny all users
                'users' => array('*'),
            ),
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
        $unit = explode(",", $model->assets_names);
        $files = explode(",", $model->image);
        $zayavkis = array();
        $units = array();
        $history = $model->phistory;
        foreach ($unit as $item) {
            $units[] = Cunits::model()->findByAttributes(array('name' => $item));
        }
        $zayavki = explode(",", $model->incidents);
        foreach ($zayavki as $zayavka) {
            $zayavkis[] = Request::model()->findByPk($zayavka);
        }
        $this->render('view', array(
            'model' => $model,
            'zayav' => $zayavkis,
            'units' => $units,
            'files' => $files,
            'history' => $history,
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
        $model = Problems::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public
    function actionBatchDelete()
    {
        {
            //setting dafault timezone to Moscow
            date_default_timezone_set(Yii::app()->params['timezone']);
            if (Yii::app()->request->getIsAjaxRequest()) {
                $checkedIDs = $_GET['checked'];
                foreach ($checkedIDs as $id) {
                    $model = $this->loadModel($id);
                    //----
                    $allFiles = [];
                    $result = [];
                    preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->workaround, $result);
                    $result2 = [];
                    preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->workaround, $result2);

                    $result3 = [];
                    preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->decision, $result3);
                    $result4 = [];
                    preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->decision, $result4);
                    if (!empty($result[0][0])) {
                        $allFiles = array_merge($allFiles, $result[1]);
                    }
                    if (!empty($result2[0][0])) {
                        $allFiles = array_merge($allFiles, $result2[1]);
                    }
                    if (!empty($result3[0][0])) {
                        $allFiles = array_merge($allFiles, $result3[1]);
                    }
                    if (!empty($result4[0][0])) {
                        $allFiles = array_merge($allFiles, $result4[1]);
                    }
                    if (!empty($allFiles)) {
                        foreach ($allFiles as $file) {
                            $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                            if (is_file($documentPath)) {
                                unlink($documentPath);
                            }
                        }
                    }
                    //----
                    $files = explode(",", $model->image);
                    foreach ($files as $file) {
                        $os_type = DetectOS::getOS();
                        $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251' , $file) : $file;
                        $documentPath = Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id . '/' . $file;
                        if (is_file($documentPath))
                            unlink($documentPath);
                    }
                    if (is_dir(Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id)) {
                        rmdir(Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id);
                    }
                    $this->loadModel($id)->delete();
                }
            }
        }

    }

    public
    function actionBatchUpdate()
    {
        {
            //setting dafault timezone to Moscow
            date_default_timezone_set(Yii::app()->params['timezone']);
            if (Yii::app()->request->getIsAjaxRequest()) {
                $checkedIDs = $_GET['checked'];
                foreach ($checkedIDs as $id) {
                    Problems::model()->updateByPk($id, array('status' => 'Решена', 'slabel' => '<span class="label label-default">Решена</span>', 'enddate' => date("d.m.Y H:i")));
                    $problem = Problems::model()->findByPk($id);
                    $phistory = new Phistory;
                    $phistory->pid = $problem->id;
                    $phistory->date = date("d.m.Y H:i");
                    $phistory->user = Yii::app()->user->name;
                    $phistory->action = 'Изменен статус проблемы: ' . $problem->slabel;
                    $phistory->save(false);
                }
            }
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
        $model = new Problems;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Problems'])) {
            $model->attributes = $_POST['Problems'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public
    function actionAssign($id)
    {
        if (isset($_POST['users'])) {
            $model = $this->loadModel($id);
            $manager = CUsers::model()->findByAttributes(array('Username' => $_POST['users']));
            Problems::model()->updateByPk($id, array('manager' => $manager->fullname));
            $subject = '' . strip_tags($model->status) . ' проблема ' . $model->id . '';
            $this->AddHistory(Yii::t('main-ui', 'Manager is set to: ') . '<b>' . $manager->fullname . '</b>', $id);
            if ($manager->sendmail == 1) {
                $manager_address = $manager->Email;
                $message = 'Изменение проблемы со статусом {status} №{id} в категории {category} сервиса {service}. Проблеме назначен приоритет {priority}. Время недоступности сервиса {downtime}. Ответственный {manager}.';
                $this->Mailsend($manager_address, $subject, $message, $model);
            }

            $this->redirect(array('index'));
        }

    }

    public function AddHistory($action, $id)
    {
        $cusers_id = CUsers::model()->findByPk(Yii::app()->user->id);
        $history = new Phistory();
        $history->date = date("d.m.Y H:i");
        $history->user = $cusers_id->fullname;
        $history->pid = $id;
        $history->action = $action;
        $history->save(false);

    }

    public function Mailsend($address, $subject, $message, $model)
    {
        $afiles = array();
        $umessage = $this->MessageGen($message, $model);
        SendMail::send($address, $subject, $umessage, $afiles);
    }

    public function MessageGen($content, $model)
    {
        $s_message = Yii::t('message', "$content", array(
            '{id}' => $model->id,
            '{category}' => $model->category,
            '{status}' => $model->status,
            '{service}' => $model->service,
            '{priority}' => $model->priority,
            '{downtime}' => $model->downtime,
            '{description}' => $model->description,
            '{manager}' => $model->manager,
        ));
        return $s_message;
    }

    public function actionCreateh()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Problems;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Problems'])) {
            $model->attributes = $_POST['Problems'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create_problem', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $files = explode(",", $model->image);
        if (isset($_POST['Problems'])) {
            $model->attributes = $_POST['Problems'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
            'files' => $files,
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
        //----
        $allFiles = [];
        $result = [];
        preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->workaround, $result);
        $result2 = [];
        preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->workaround, $result2);

        $result3 = [];
        preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->decision, $result3);
        $result4 = [];
        preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->decision, $result4);
        if (!empty($result[0][0])) {
            $allFiles = array_merge($allFiles, $result[1]);
        }
        if (!empty($result2[0][0])) {
            $allFiles = array_merge($allFiles, $result2[1]);
        }
        if (!empty($result3[0][0])) {
            $allFiles = array_merge($allFiles, $result3[1]);
        }
        if (!empty($result4[0][0])) {
            $allFiles = array_merge($allFiles, $result4[1]);
        }
        if (!empty($allFiles)) {
            foreach ($allFiles as $file) {
                $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                if (is_file($documentPath)) {
                    unlink($documentPath);
                }
            }
        }
        //----

        $files = explode(",", $model->image);
        foreach ($files as $file) {
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251' , $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id . '/' . $file;
            if (is_file($documentPath))
                unlink($documentPath);
        }
        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id)) {
            rmdir(Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id);
        }
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    // Send Email function

    public
    function actionDeleteFile($id, $file)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $model->attributes = $model->attributes;
        //$filename = iconv("UTF-8", "CP1251", $file); //in Windows systems
        $os_type = DetectOS::getOS();
        $filename = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251' , $file) : $file;
        $documentPath = Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id . '/' . $filename;
        if (is_file($documentPath))
            unlink($documentPath);
        $filelist = array();
        $path = Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id;
        $filelist = $this->myscandir($path);
        $value = implode(",", $filelist);
        $value = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $value) : $value;
        $this->AddHistory(Yii::t('main-ui', 'Deleted file: ') . '<b>' . $file . '</b>', $id);
        Problems::model()->updateByPk($id, array('image' => $value));
    }

//Generate message content by some templates

    public function myscandir($dir, $sort = 0)
    {
        $list = scandir($dir, $sort);

        // если директории не существует
        if (!$list) return false;

        // удаляем . и .. (я думаю редко кто использует)
        if ($sort == 0) unset($list[0], $list[1]);
        else unset($list[count($list) - 1], $list[count($list) - 1]);
        return $list;
    }

    // Добавление записи в историю

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['problemsPageCount'] = $_GET['pageCount'];
        }
        $model = new Problems('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Problems']))
            $model->attributes = $_GET['Problems'];
        //Yii::app()->user->setFlash('info', '<strong>' . Yii::t('main-ui', 'Welcome') . ' ' . Yii::app()->user->name . '! </strong>' . Yii::t('main-ui', 'Here you can manage problems.'));
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'problems-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
