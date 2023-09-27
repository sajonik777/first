<?php

class CategoryController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/design3';

    /**
     * @return array action filters
     */
    public
    function filters()
    {
        return array(
            'accessControl',// perform access control for CRUD operations
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'roles' => array('listKBCat'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create'),
                'roles' => array('createKBCat'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('update'),
                'roles' => array('updateKBCat'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('deleteKBCat'),
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
    public
    function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public
    function actionCreate()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Categories;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Categories'])){
            $model->attributes = $_POST['Categories'];
            if($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public
    function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['Categories'])){
            $model->attributes = $_POST['Categories'];
            if($model->save())
                $knowledge = Knowledge::model()->findAllByAttributes(array('bcat_name'=>$model->name));
            foreach ($knowledge as $item) {
               Knowledge::model()->updateByPk($item->id,array('access'=>$_POST['Categories']['access']));
           } 
           $this->redirect(array('index'));
       }

       $this->render('update',array(
        'model'=>$model,
    ));
   }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public
    function actionDelete($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        $brec = Knowledge::model()->findByAttributes(array('parent_id'=>$id));
        if(Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()){
            // we only allow deletion via POST request
            if ($brec or $id ==1){
                throw new CHttpException(400,'Невозможно удалить категорию, используемую в Базе знаний! Переназначьте категорию в Базе.');

            }
            else{
                $this->loadModel($id)->delete();
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public
    function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Categories('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Categories']))
            $model->attributes = $_GET['Categories'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public
    function loadModel($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = Categories::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'bcats-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
