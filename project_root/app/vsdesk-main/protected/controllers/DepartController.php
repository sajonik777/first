<?php

/**
 * Class DepartController
 */
class DepartController extends Controller
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
            //'postOnly + delete', // we only allow deletion via POST request
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
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'roles' => array('listDepart'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create'),
                'roles' => array('createDepart'),
            ),
            array(
                'allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('update', 'servicedelete', 'serviceadd'),
                'roles' => array('updateDepart'),
            ),
            array(
                'allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('deleteDepart'),
            ),

            array(
                'deny', // deny all users
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
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Depart the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Depart::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Depart;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Depart'])) {
            $model->attributes = $_POST['Depart'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
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
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Depart'])) {
            $units = Cunits::model()->findAllByAttributes(array('dept' => $model->name));
            foreach ($units as $unit) {
                Cunits::model()->updateByPk($unit->id, array('dept' => $_POST['Depart']['name']));
            }
            $assets = Asset::model()->findAllByAttributes(array('cusers_dept' => $model->name));
            foreach ($assets as $asset) {
                Asset::model()->updateByPk($asset->id, array('cusers_dept' => $_POST['Depart']['name']));
            }
            $users = CUsers::model()->findAllByAttributes(array('department' => $model->name));
            foreach ($users as $user) {
                Cusers::model()->updateByPk($user->id, array('department' => $_POST['Depart']['name']));
            }
            $model->attributes = $_POST['Depart'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
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
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        }else {
            throw new CHttpException(400, 'УПС! Неверный запрос, что-то вы делаете не так.');
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Depart('search');
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['deptPageCount'] = $_GET['pageCount'];
        }
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Depart'])) {
            $model->attributes = $_GET['Depart'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Depart $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'depart-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Открепляет сервис от модели.
     */
    public function actionServiceDelete()
    {
        //var_dump($_GET);exit;
        //$this->loadModel($id)->delete();
        $service_id = $_GET['service_id'];
        $depart_id = $_GET['depart_id'];
        $model = DepartServices::model()->findByAttributes(array(
            'service_id' => $service_id,
            'depart_id' => $depart_id
        ));
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    public function actionServiceAdd()
    {
        $model = new DepartServices;

        if (isset($_POST['service']) and isset($_GET['depart_id'])) {
            $model->service_id = $_POST['service'];
            $model->depart_id = $_GET['depart_id'];
            $model->save();
        }

        $depart = Depart::model()->findByAttributes(array('id' => $_GET['depart_id']));
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'services-grid',
            'dataProvider' => new CArrayDataProvider($depart->services),
            'type' => 'striped bordered condensed',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array(
                'name:text:'.Yii::t('main-ui', 'Services'),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{delete}',
                    'deleteButtonUrl' => 'Yii::app()->createUrl("/depart/servicedelete", array("service_id"=>$data->id, "depart_id"=>"' . $depart->id . '"))',
                ),
            ),
        ));
    }
}
