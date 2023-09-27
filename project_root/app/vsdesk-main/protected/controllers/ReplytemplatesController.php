<?php

class ReplyTemplatesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/design3';

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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
            	'actions' => array('index'),
            	'roles' => array('listTemplates'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
            	'actions' => array('create'),
            	'roles' => array('createTemplates'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
            	'actions' => array('update'),
            	'roles' => array('updateTemplates'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
            	'actions' => array('delete'),
            	'roles' => array('deleteTemplates'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ReplyTemplates;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ReplyTemplates']))
		{
			$model->attributes=$_POST['ReplyTemplates'];
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
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ReplyTemplates']))
		{
			$model->attributes=$_POST['ReplyTemplates'];
			if($model->save())
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
	public function actionDelete($id)
	{
		if (Yii::app()->request->getIsAjaxRequest()) {
			$id = $_GET['id'];
		}
		if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
			$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		} else {
			throw new CHttpException(400, 'УПС! Неверный запрос, что-то вы делаете не так.');
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model=new ReplyTemplates('search');
		if (isset($_GET['pageCount'])) {
			Yii::app()->session['rmessPageCount'] = $_GET['pageCount'];
		}
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ReplyTemplates']))
			$model->attributes=$_GET['ReplyTemplates'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ReplyTemplates the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ReplyTemplates::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ReplyTemplates $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='reply-templates-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
