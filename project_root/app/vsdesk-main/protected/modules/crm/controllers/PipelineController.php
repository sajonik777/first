<?php

class PipelineController extends Controller
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
                'roles' => array('viewPipeline'),
            ),
            array('allow',
                'actions' => array('index', 'sort'),
                'roles' => array('listPipeline'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createPipeline'),
            ),
            array('allow',
                'actions' => array('update', 'move'),
                'roles' => array('updatePipeline'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deletePipeline'),
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
        $model = new Pipeline;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Pipeline'])) {
            $model->attributes = $_POST['Pipeline'];
            if ($model->save())
                $this->redirect(array('/crm/pipeline'));
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

        if (isset($_POST['Pipeline'])) {
            $model->attributes = $_POST['Pipeline'];
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
            Yii::app()->session['PipelinePageCount'] = $_GET['pageCount'];
        }
        $model = new Pipeline('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Pipeline']))
            $model->attributes = $_GET['Pipeline'];

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
        $model = Pipeline::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionSort()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $items = json_encode($_POST['lists']);
            $items = explode('&', $items);
            $i = 0;
            foreach ($items as $item){
                $i = $i+1;
                $item_id = preg_replace('/[^0-9]/', '', $item);
                Pipeline::model()->updateByPk($item_id, ['sort_id' => $i]);
            }
            unset($_POST);
        }

    }

    public function actionMove()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $items = $_POST['items'];
            $status_id = $_POST['tasklist_id'];
            $add = $_POST['add_or_remove'];
            $i = 0;
            if($add == 'add'){
                $items = explode('&', $items);
                foreach ($items as $item){
                    if(count($items) > 1 AND strcasecmp($item, 'input-list[]=') !== 1 AND !empty($item)){
                        echo($item);
                        $i = $i+1;
                        $item = preg_replace('/[^0-9]/', '', json_encode($item));
                        $status = Pipeline::model()->findByPk($status_id);
                        Leads::model()->updateByPk($item, ['status_id' => $status_id, 'status' => $status->label, 'sort_id' => $i]);
                    } elseif (strcasecmp($item, 'input-list[]=') !== 1 AND !empty($item)) {
                        $item = preg_replace('/[^0-9]/', '', json_encode($item));
                        $status = Pipeline::model()->findByPk($status_id);
                        Leads::model()->updateByPk($item, ['status_id' => $status_id, 'status' => $status->label]);
                    }

                }

            } else {
                $items = explode('&', $items);
                foreach ($items as $item){
                    if(count($items) > 1 AND strcasecmp($item, 'input-list[]=') !== 1 AND !empty($item)){
                        echo($item);
                        $i = $i+1;
                        $item = preg_replace('/[^0-9]/', '', json_encode($item));
                        Leads::model()->updateByPk($item, ['sort_id' => $i]);
                    }

                }
            }
            unset($_POST);
        }

    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pipeline-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
