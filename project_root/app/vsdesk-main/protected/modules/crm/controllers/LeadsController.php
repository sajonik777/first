<?php

class LeadsController extends Controller
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
        return array(
            array('allow',
                'actions' => array('view'),
                'roles' => array('viewLeads'),
            ),
            array('allow',
                'actions' => array('index'),
                'roles' => array('listLeads'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createLeads'),
            ),
            array('allow',
                'actions' => array('update', 'updName'),
                'roles' => array('updateLeads'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteLeads'),
            ),

            array('allow',// deny all users
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Leads;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Leads'])) {
            $model->attributes = $_POST['Leads'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdName($id)
    {
        Leads::model()->updateByPk($id,
            array($_POST['name'] => $_POST['value']));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Leads'])) {
            $model->attributes = $_POST['Leads'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionSetStatusOne()
    {
        if (isset($_GET['checked']) and isset($_GET['status'])) {
            $requestId = $_GET['checked'];
            $user = CUsers::model()->findByAttributes(array('Username' => $_GET['user']));
            $request = Leads::model()->findByPk($requestId);
            $status = Pipeline::model()->findByPk($_GET['status']);
            if(isset($status) AND !empty($status) AND ($request->status_id !== $status->id)){
                $_POST['Leads']['changer'] = $user;
                $_POST['Leads']['status_id'] = $status->id;
                $_POST['Leads']['status'] = $status->label;
                $_POST['Leads']['changed'] = date('Y-m-d H:i:s');
                $request->attributes = $_POST['Leads'];

                if ($request->save()) {
                    if ($status->send_email == 1){
                        $email = $request->contact_email;
                        if (isset($email) AND !empty($email)){
                            $message  = self::MessageGen($status->email_template, $request);
                            Email::send($email,'CRM TEST SUBJECT', $message, NULL);
                        }

                    }
                    unset($_POST);
                }
            }
        }
    }

    public function MessageGen($content, $request)
    {

        $s_message = Yii::t('message', "$content", array(
            '{name}' => $request->name,
            '{status}' => $request->status,
            '{contact}' => $request->contact,
            '{manager}' => $request->manager,

        ));
        return $s_message;
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['LeadsPageCount'] = $_GET['pageCount'];
        }
        $model = new Leads('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Leads']))
            $model->attributes = $_GET['Leads'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Leads::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'leads-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
