<?php

class ServiceCategoriesController extends Controller
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
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
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
                'allow',
                'actions' => ['index'],
                'roles' => ['listServiceCategory'],
            ],
            [
                'allow',
                'actions' => ['create'],
                'roles' => ['createServiceCategory'],
            ],
            [
                'allow',
                'actions' => ['update', 'serviceDelete', 'serviceAdd'],
                'roles' => ['updateServiceCategory'],
            ],
            [
                'allow',
                'actions' => ['delete'],
                'roles' => ['deleteServiceCategory'],
            ],
            [
                'deny',// deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    // public function actionCreate()
    // {
    //     $model = new ServiceCategories;

    //     // Uncomment the following line if AJAX validation is needed
    //     // $this->performAjaxValidation($model);

    //     if (isset($_POST['ServiceCategories'])) {
    //         $model->attributes = $_POST['ServiceCategories'];
    //         if ($model->save()) {
    //             $this->redirect(['update', 'id' => $model->id]);
    //         }
    //     }

    //     $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ServiceCategories'])) {
            $model->attributes = $_POST['ServiceCategories'];
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
     * @throws CDbException
     */
    // public function actionDelete($id)
    // {
    //     $this->loadModel($id)->delete();

    //     // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    //     if (!isset($_GET['ajax'])) {
    //         $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
    //     }
    // }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new ServiceCategories('search');
        $model->unsetAttributes();
        if (isset($_GET['ServiceCategories'])) {
            $model->attributes = $_GET['ServiceCategories'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    /**
     * Открепляет сервис от модели.
     */
    public function actionServiceDelete($service_id)
    {
        /** @var Service $model */
        $model = Service::model()->findByPk($service_id);
        $model->category_id = null;
        $model->save();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    /**
     * @throws Exception
     */
    public function actionServiceAdd()
    {


        if (isset($_POST['service']) and isset($_GET['category_id'])) {
            /** @var Service $model */
            $model = Service::model()->findByPk($_POST['service']);
            $model->category_id = $_GET['category_id'];
            $model->save();
        }

        $model = $this->loadModel($_GET['category_id']);
        $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'services-grid',
            'dataProvider' => new CArrayDataProvider($model->services),
            'type' => 'striped bordered condensed',
            'htmlOptions' => ['style' => 'cursor: pointer'],
            'columns' => [
                'name:text:'.Yii::t('main-ui', 'Services'),
                [
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{delete}',
                    'deleteButtonUrl' => 'Yii::app()->createUrl("/serviceCategories/servicedelete", array("service_id"=>$data->id))',
                ],
            ],
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ServiceCategories the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ServiceCategories::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ServiceCategories $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'service-categories-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
