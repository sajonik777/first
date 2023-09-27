<?php

class GroupsController extends Controller
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
    public
    function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'roles' => array('listGroup'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'add_user', 'delete_user'),
                'roles' => array('createGroup'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('update', 'add_user', 'delete_user'),
                'roles' => array('updateGroup'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('deleteGroup'),
            ),

            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionCreate()
    {
        $model = new Groups;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Groups'])) {
            $model->attributes = $_POST['Groups'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $userids = explode(',', $model->users);
        foreach ($userids as $uid) {
            $usernames[] = CUsers::model()->findByAttributes(array('id' => $uid));
        }
        if (isset($_POST['Groups'])) {

            $model->attributes = $_POST['Groups'];
            if ($model->save())
                $this->redirect(array('index'));
        }
        $this->render('update', array(
            'model' => $model,
            'users' => $usernames,
        ));
    }

    public function loadModel($id)
    {
        $model = Groups::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        $service = Service::model()->findByAttributes(array('group' => $model->name));
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
// we only allow deletion via POST request
            if ($service) {
                throw new CHttpException(400, 'Невозможно удалить группу, привязанную к одному или нескольким сервисам! Измените настройки сервиса.');
            } else {
                $this->loadModel($id)->delete();
            }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
        throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionIndex()
    {
        $model = new Groups('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Groups']))
            $model->attributes = $_GET['Groups'];
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionAdd_user($id)
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            if (isset($_POST['users']) AND !empty($_POST['users'])) {
                //$group = Groups::model()->findByPk($id);
                $oldusers = explode(",", $model->users);
                $newusers = $_POST['users'];
                $values = array_merge($oldusers, $newusers);
                $values2 = array_unique($values);
                $values3 = array_filter($values2);
                $value = implode(",", $values3);

                Groups::model()->updateByPk($id, array('users' => $value));
                $this->redirect(array('update', 'id' => $id));
            } else {
              $this->redirect(array('update', 'id' => $id));
          }
      }
  }

  public function actionDelete_user($id, $mid)
  {
        //setting dafault timezone to Moscow
    date_default_timezone_set(Yii::app()->params['timezone']);
    if (Yii::app()->request->isPostRequest) {
        $newvalues = array();
        $model = $this->loadModel($mid);
        $users = explode(",", $model->users);
        foreach ($users as $item) {
            $uid = CUsers::model()->findByPk($id);
            if ($uid->id == $item) {
            } else {
                $newvalues[] = $item;
            }
        }
        $values2 = array_filter($newvalues);
        $value = implode(",", $values2);
        Groups::model()->updateByPk($mid, array('users' => $value));
    }

}

protected function performAjaxValidation($model)
{
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'groups-form') {
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }
}
}
