<?php

class MessagesController extends Controller
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
    public
    function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index'),
                'roles' => array('listETemplate'),
            ),
            array('allow',
                'actions' => array('view'),
                'roles' => array('viewETemplate'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createETemplate'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateETemplate'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteETemplate'),
            ),

            array('deny',// deny all users
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Messages::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Messages;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Messages'])) {
            $model->attributes = $_POST['Messages'];
            $model->static = 0;
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create', array(
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
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Messages'])) {
            $model->attributes = $_POST['Messages'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
// we only allow deletion via POST request
            $model = $this->loadModel($id);
            $messages = Status::model()->findByAttributes(array('message' => $model->name));
            $mmessages = Status::model()->findByAttributes(array('mmessage' => $model->name));
            $gmessages = Status::model()->findByAttributes(array('gmessage' => $model->name));
            if ($messages or $mmessages or $gmessages or $id == 1) {
                throw new CHttpException(400, 'Шаблон используется статусом, перед удалением, для статуса необходимо назначить другой шаблон.');

            } else {
                $this->loadModel($id)->delete();
            }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionIndex()
    {
        $model = new Messages('search');
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['messPageCount'] = $_GET['pageCount'];
        }
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Messages']))
            $model->attributes = $_GET['Messages'];

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'messages-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
