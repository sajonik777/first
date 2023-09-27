<?php

class AssetAttribValueController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
            array('allow',// allow all users to perform 'index' and 'view' actions
                'actions' => array('create', 'delete', 'update'),
                'roles' => array('systemUser'),
            ),
            array('allow',// allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'index', 'view', 'update', 'create'),
                'roles' => array('systemAdmin'),
            ),
            array('allow',// allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'index', 'view', 'update', 'create'),
                'roles' => array('systemManager'),
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
    public
    function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->render('view', array(
            'model' => $this->loadModel($id),
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
        $model = AssetAttribValue::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    function actionCreate()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new AssetAttribValue;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AssetAttribValue'])) {
            $model->attributes = $_POST['AssetAttribValue'];
            if ($model->save()){
                $ids = array();
                $values = AssetValues::model()->findAllByAttributes(array('asset_attrib_id' => $_POST['AssetAttribValue']['asset_attrib_id']));
                foreach ($values as $value) {
                    $ids[] = $value->asset_id;
                }
                $ids_filter = array_unique($ids);
                foreach ($ids_filter as $key => $value) {
                    $asset_real = Asset::model()->findAll();
                    foreach ($asset_real as $asset_item){
                        if ($asset_item->id == $value){
                            $model2 = new AssetValues;
                            $model2->asset_id = $value;
                            $model2->asset_attrib_id = $_POST['AssetAttribValue']['asset_attrib_id'];
                            $model2->asset_attrib_name = $_POST['AssetAttribValue']['name'];
                            $model2->value = NULL;
                            $model2->save(false);
                        }
                    }
                }
                $this->redirect(array('/assetAttrib/update', 'id' => $_POST['AssetAttribValue']['asset_attrib_id']));
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
    public
    function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AssetAttribValue'])) {
            $model->attributes = $_POST['AssetAttribValue'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
    public
    function actionDelete($asset_attrib_id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($asset_attrib_id);
            $asset_value_name = AssetAttribValue::model()->findByPk($asset_attrib_id);
            $asset_values = AssetValues::model()->findAllByAttributes(array('asset_attrib_name'=>$asset_value_name->name, 'asset_attrib_id'=>$asset_value_name->asset_attrib_id));
            foreach($asset_values as $value){
              $item = AssetValues::model()->findByPk($value->id);
                $history = new Ahistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->aid = $item->asset_id;
                $history->action = Yii::t('main-ui', 'Deleted value').' "'.$item->asset_attrib_name.'" ' . $item->value;
                $history->save(false);
              $item->delete();
            }
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public
    function actionIndex()
    {

    }

    /**
     * Manages all models.
     */
    public
    function actionAdmin()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new AssetAttribValue('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AssetAttribValue']))
            $model->attributes = $_GET['AssetAttribValue'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'asset-attrib-value-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
