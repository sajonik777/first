<?php

class SelectsController extends Controller
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
            array(
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'roles' => array('listSelects'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'delete_item', 'add_item'),
                'roles' => array('createSelects'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('deleteSelects'),
            ),
            array(
                'deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * @param $id
     * @param $value
     */
    public function actionDelete_item($id, $value)
    {
        $model = $this->loadModel($id);
        $values = explode(',', $model->select_value);
        if (!empty($values)) {
            $ret = array_search($value, $values);
            if ($ret !== false) {
                unset($values[$ret]);
                $model->select_value = implode(',', $values);
                if ($model->save(false)) {
                    FieldsetsFields::model()->updateAll(['value' => $model->select_value], 'select_id=' . $model->id);
                }
            }
        }
    }

    /**
     * @param $id
     */
    public function actionAdd_item($id)
    {
        $model = $this->loadModel($id);
        $values = [];
        if (!empty($model->select_value)) {
            $values = explode(',', $model->select_value);
        }
        $values[] = str_replace(',', '', $_POST['value']);
        $model->select_value = implode(',', $values);
        $model->save(false);
        if ($model->save(false)) {
            FieldsetsFields::model()->updateAll(['value' => $model->select_value], 'select_id=' . $model->id);
            CompanyFieldset::model()->updateAll(['value' => $model->select_value], 'select_id=' . $model->id);
        }
        $this->redirect(['update', 'id' => $id]);
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
        $model = new Selects;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Selects'])) {
            $model->attributes = $_POST['Selects'];
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
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Selects'])) {
            $model->attributes = $_POST['Selects'];
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
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Selects('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Selects'])) {
            $model->attributes = $_GET['Selects'];
        }

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
        $model = Selects::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'selects-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
