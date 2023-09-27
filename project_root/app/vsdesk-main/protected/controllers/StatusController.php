<?php

/**
 * Class StatusController
 */
class StatusController extends Controller
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
                'roles' => ['listStatus'],
            ],
            [
                'allow',
                'actions' => ['create'],
                'roles' => ['createStatus'],
            ],
            [
                'allow',
                'actions' => ['update'],
                'roles' => ['updateStatus'],
            ],
            [
                'allow',
                'actions' => ['delete'],
                'roles' => ['deleteStatus'],
            ],

            [
                'deny',// deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     * @throws CHttpException
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
        $model = new Status;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Status'])) {
            $model->attributes = $_POST['Status'];
            $statuses = Status::model()->findByAttributes(array('enabled' => 1, 'close' => $_POST['Status']['close']));
            if (empty($statuses) or $_POST['Status']['close'] == 0) {
                if ($model->save()) {
                    $this->redirect(array('index'));
                }
            } else {
                throw new CHttpException(400, 'Невозможно изменить статус, т.к. тип закрытия заявки уже присвоен.');
            }
        }

        $this->render('create', array(
            'model' => $model,
            'messages' => Messages::all(),
            'smss' => Smss::all()
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['Status'])) {
            $statuses = Status::model()->findByAttributes(array('enabled' => 1, 'close' => $_POST['Status']['close']));
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);
            $model->attributes = $_POST['Status'];
            if ($model->name !== $_POST['Status']['name']) {
                $req = Request::model()->findAllByAttributes(array('Status' => $model->name));
                foreach ($req as $zayavka) {
                    Request::model()->updateByPk($zayavka->id, array(
                        'Status' => $_POST['Status']['name'],
                        'slabel' => '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: ' . $_POST['Status']['tag'] . '; vertical-align: baseline; white-space: nowrap; border: 1px solid ' . $_POST['Status']['tag'] . '; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $_POST['Status']['name'] . '</span>'
                    ));
                }
            }
            if (empty($statuses) or $_POST['Status']['close'] == 0 or $statuses->id == $id) {
                if ($model->save()) {
                    $this->redirect(array('index'));
                }
            } else {
                throw new CHttpException(400, 'Невозможно изменить статус, т.к. тип закрытия заявки уже присвоен.');
            }
        }

        $this->render('update', array(
            'model' => $model,
            'messages' => Messages::all(),
            'smss' => Smss::all(),
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CHttpException
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

    public function actionIndex()
    {
        $model = new Status('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Status'])) {
            $model->attributes = $_GET['Status'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return array|mixed|null
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Status::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'status-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
