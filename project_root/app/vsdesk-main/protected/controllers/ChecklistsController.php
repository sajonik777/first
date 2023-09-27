<?php

/**
 * Class ChecklistsController
 */
class ChecklistsController extends Controller
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
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'reorder'],
                'roles' => ['listChecklists'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create'],
                'roles' => ['createChecklists'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['update', 'add_field', 'delete_field', 'select', 'update_field'],
                'roles' => ['updateChecklists'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['delete'],
                'roles' => ['deleteChecklists'],
            ],
            [
                'deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function actions()
    {
        return [
            'reorder' => [
                'class' => 'bootstrap.actions.TbSortableAction',
                'modelName' => 'ChecklistFields'
            ]
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Checklists;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Checklists'])) {
            $model->attributes = $_POST['Checklists'];
            if ($model->save()) {
                $this->redirect(['update', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $modelChecklistFields = new ChecklistFields();
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Checklists'])) {
            $model->attributes = $_POST['Checklists'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
            'modelChecklistFields' => $modelChecklistFields,
            'fields' => $model->checklistFields,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Checklists');
        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Checklists('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Checklists'])) {
            $model->attributes = $_GET['Checklists'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function actionAdd_field($id)
    {
        $model = new ChecklistFields();
        $exists = ChecklistFields::model()->findAllByAttributes(['checklist_id' => $id]);
        if (isset($exists)) {
            $arr = [null];
            foreach ($exists as $value) {
                $arr[] = $value->sorting;
            }
            $count = max($arr);
        } else {
            $count = 0;
        }
        if (isset($_POST['ChecklistFields']) && !empty($_POST['ChecklistFields']['name'])) {
            $model->checklist_id = $id;
            $model->sorting = ((int)$count + 1);
            $model->name = $_POST['ChecklistFields']['name'];
            if ($model->save(false)) {
                $this->redirect(['update', 'id' => $id]);
            }
        } else {
            $this->redirect(['update', 'id' => $id]);
        }
    }

    public function actionDelete_field($id)
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        ChecklistFields::model()->deleteByPk($id);
    }

    public function actionUpdate_field($id)
    {
        $model = ChecklistFields::model()->findByPk($id);
        if (isset($_POST['ChecklistFields'])) {
            $model->attributes = $_POST['ChecklistFields'];
            $model->save();
            $this->redirect(['update', 'id' => $model->checklist_id]);
        }
        $this->render('update_field', ['model' => $model]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Checklists the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Checklists::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Checklists $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'checklists-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
