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
                'actions' => array('index'),
                'roles' => array('listCategory'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createCategory'),
            ),
            array('allow',
                'actions' => array('update'),
                'roles' => array('updateCategory'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteCategory'),
            ),

            array('deny',// deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionCreate()
    {
        $model = new Category;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Category'])) {
            $model->attributes = $_POST['Category'];
            $categories = Category::model()->findByAttributes(array('enabled' => 1, 'incident' => $_POST['Category']['incident']));
            if (empty($categories) or $_POST['Category']['incident'] == 0) {
                if ($model->save())
                    $this->redirect(array('index'));
            } else {
                throw new CHttpException(400, 'Невозможно назначить категорию Инцидентом, т.к. тип "Инцидент" уже присвоен.');
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

        if (isset($_POST['Category'])) {
            $model->attributes = $_POST['Category'];
            $categories = Category::model()->findByAttributes(array('enabled' => 1, 'incident' => 1));
            if (empty($categories) or $_POST['Category']['incident'] == 0) {
                if ($model->save())
                    $this->redirect(array('index'));
            } else {
                throw new CHttpException(400, 'Невозможно назначить категорию Инцидентом, т.к. тип "Инцидент" уже присвоен.');
            }
        }
        $this->render('update', array(
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
        $model = Category::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
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
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Category('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Category']))
            $model->attributes = $_GET['Category'];

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
