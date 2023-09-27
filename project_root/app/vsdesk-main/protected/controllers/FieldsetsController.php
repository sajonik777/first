<?php

class FieldsetsController extends Controller
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
                'actions' => array('index', 'reorder'),
                'roles' => array('listFieldsets'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create'),
                'roles' => array('createFieldsets'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('update', 'add_field', 'delete_field', 'select', 'update_field'),
                'roles' => array('updateFieldsets'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('delete'),
                'roles' => array('deleteFieldsets'),
            ),
            array(
                'deny',  // deny all users
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
    public function actions() {
        return array(
            'reorder' => array(
                'class' => 'bootstrap.actions.TbSortableAction',
                'modelName' => 'FieldsetsFields'
            ));
    }

    public function loadModel($id)
    {
        $model = Fieldsets::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function actionSelect()
    {
        if ($_POST['FieldsetsFields']['type'] == 'select') {
            $models = Selects::model()->findAll();
            $list = CHtml::listData($models, 'id', 'select_name');
            echo CHtml::dropDownList('FieldsetsFields[value]', '', $list, array('empty' => '', 'class'=>'span12'));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Fieldsets;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Fieldsets'])) {
            $model->attributes = $_POST['Fieldsets'];
            if ($model->save()) {
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionAdd_field($id)
    {
        $model = new FieldsetsFields;
        $exists = FieldsetsFields::model()->findAllByAttributes(array('fid'=>$id));
        if(isset($exists)){
          $arr = array(NULL);
            foreach ($exists as $value) {
              $arr[] = $value->sid;
            }
            $count = max($arr);
        } else {
            $count = 0;
        }
        if (isset($_POST['FieldsetsFields']) AND !empty($_POST['FieldsetsFields']['name'])) {
            $model->fid = $id;
            $model->sid = ((int)$count + 1);
            $model->name = $_POST['FieldsetsFields']['name'];
            $model->type = $_POST['FieldsetsFields']['type'];
            $model->req = $_POST['FieldsetsFields']['req'];
            if (isset($_POST['FieldsetsFields']['value'])) {
                $select = Selects::model()->findByPk($_POST['FieldsetsFields']['value']);
                $model->value = $select->select_value;
                $model->select_id = $select->id;
            }
            if ($model->save(false)) {
                $this->redirect(array('update', 'id' => $id));
            }
        } else {
          $this->redirect(array('update', 'id' => $id));
      }
  }

  public function actionDelete_field($id)
  {
    if (Yii::app()->request->getIsAjaxRequest()) {
        $id = $_GET['id'];
    }
    FieldsetsFields::model()->deleteByPk($id);
}

public function actionUpdate_field($id)
{
    $model = FieldsetsFields::model()->findByPk($id);
    if (isset($_POST['FieldsetsFields'])) {
        $oldName = $model->name;
        $model->attributes = $_POST['FieldsetsFields'];
        if ($model->save()) {
            $rFields = RequestFields::model()->updateAll(['name' => $model->name], 'name="' . $oldName . '"');
        }
        $this->redirect(array('update', 'id' => $model->fid));
    }
    $this->render('update_field', array('model' => $model));
}

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model2 = new FieldsetsFields();
        $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $model->id));

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Fieldsets'])) {
            $model->attributes = $_POST['Fieldsets'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'model2' => $model2,
            'fields' => $fields,
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
        $fieldsets = Fieldsets::model()->findAll();
        foreach($fieldsets as $fieldset){
         $fields = FieldsetsFields::model()->findAllByAttributes(array('fid'=>$fieldset->id));
         $i = 0;
         foreach($fields as $field){
            if(empty($field->sid)){
               FieldsetsFields::model()->updateByPk($field->id, array('sid' => $i+1));
               $i = $i+1;
           }
       }
   }
   $model = new Fieldsets('search');
   if (isset($_GET['pageCount'])) {
    Yii::app()->session['fieldsetsPageCount'] = $_GET['pageCount'];
}
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Fieldsets'])) {
            $model->attributes = $_GET['Fieldsets'];
        }

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'fieldsets-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
