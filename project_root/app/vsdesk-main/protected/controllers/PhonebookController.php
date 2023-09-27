<?php

class PhonebookController extends Controller
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
        $portal = Yii::app()->params['portalPhonebook'];
        return array(
            $portal == 0 ? array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'roles' => array('viewPhonebook'),
            ): array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('view'),
                'roles' => array('viewPhonebook'),
            ),
//            array('allow',
//                'actions' => array('index'),
//                'roles' => array('listPhonebook'),
//            ),

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
        $model = CUsers::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['CUsersPageCount'] = $_GET['pageCount'];
        }
        $model = new CUsers('psearch');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CUsers']))
            $model->attributes = $_GET['CUsers'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cusers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
