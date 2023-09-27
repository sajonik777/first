<?php

/**
 * Class CompaniesController
 */
class CompaniesController extends Controller
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
            'accessControl',// perform access control for CRUD operations
            //'postOnly + delete',// we only allow deletion via POST request
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
                'allow',
                'actions' => array('view', 'SelectTemplate', 'send_message', 'getStreets', 'getStreetsAndSelected'),
                'roles' => array('viewCompany'),
            ),
            array(
                'allow',
                'actions' => array('index'),
                'roles' => array('listCompany'),
            ),
            array(
                'allow',
                'actions' => array('create'),
                'roles' => array('createCompany'),
            ),

            array(
                'allow',
                'actions' => array('fields', 'select', 'add_field', 'update_field', 'delete_field', 'reorder'),
                'roles' => array('fieldsCompany'),
            ),
            array(
                'allow',
                'actions' => array('update', 'servicedelete', 'serviceadd', 'deletefile'),
                'roles' => array('updateCompany'),
            ),
            array(
                'allow',
                'actions' => array('delete'),
                'roles' => array('deleteCompany'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('batchdelete'),
                'roles' => array('batchDeleteCompany'),
            ),

            array(
                'deny',// deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'reorder' => array(
                'class' => 'bootstrap.actions.TbSortableAction',
                'modelName' => 'CompanyFieldset'
            ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if(Yii::app()->user->checkAccess('listContracts')){
            $criteriac = new CDbCriteria;
            $criteriac->addSearchCondition('customer_id', $id, false, 'OR', 'LIKE');
            $criteriac->addSearchCondition('company_id', $id, false, 'OR', 'LIKE');
            $contracts = Contracts::model()->findAll($criteriac);
        }
        $model = $this->loadModel($id);
        $city = Cities::model()->findByPk($model['city']);
        $model['city'] = $city['name'];
        $street = Streets::model()->findByPk($model['street']);
        $model['street'] = $street['name'];
        $this->render('view', array(
            'model' => $model,
            'contracts' => $contracts ? $contracts : null,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $usr = Yii::app()->user->id;
        $manager = Yii::app()->user->name;
        $model = Companies::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function actionSelectTemplate($id)
    {
        $data = ReplyTemplates::model()->findByPk($_POST['Comments']['theme']);
        echo CJSON::encode(array(
            'content' => $data->content,
            'desc' => $data->name,
        ));
    }
    public function actionSend_message($id)
    {
        $images = CUploadedFile::getInstancesByName('image');
        if (isset($images) && count($images) > 0) {
            foreach ($images as $image) {
                $image->saveAs(Yii::getPathOfAlias('webroot') . '/uploads/' . $image->name);
                $afiles[] = Yii::getPathOfAlias('webroot') . '/uploads/' . $image->name;
            }
        }
        $model = $this->loadModel($id);
        $subject = $_POST['Comments']['author'];
        $message = $_POST['Comments']['comment'];
        SendMail::send($model->email, $subject, $message, $afiles);
        foreach ($afiles as $key => $value) {
            unlink($value);
        }
        Yii::app()->user->setFlash('info',
                    Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully sent Email.'));
        $this->redirect(array('view', 'id' => $model->id));

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Yii::app()->session->remove('cfields');
        $model = new Companies;
        $fields = new CompanyFields;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Companies'])) {
            $model->attributes = $_POST['Companies'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'fields' => $fields
        ));
    }

    public function actionFields()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new CompanyFieldset;
        $fields = CompanyFieldset::model()->findAll();

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['CompanyFieldet'])) {
            $model->attributes = $_POST['CompanyFieldet'];
            if ($model->save()) {
                $this->redirect(array('fields'));
            }
        }

        $this->render('fields', array(
            'model' => $model,
            'fields' => $fields,
        ));
    }

    public function actionSelect()
    {
        if ($_POST['CompanyFieldset']['type'] == 'select') {
            $models = Selects::model()->findAll();
            $list = CHtml::listData($models, 'id', 'select_name');
            echo CHtml::dropDownList('CompanyFieldset[value]', '', $list, array('empty' => '', 'class'=>'span12'));
        }
    }

    public function actionAdd_field()
    {
        $model = new CompanyFieldset;
        $exists = CompanyFieldset::model()->findAll();
        if(isset($exists)){
          $arr = array(NULL);
            foreach ($exists as $value) {
              $arr[] = $value->sid;
            }
            $count = max($arr);
        } else {
            $count = 0;
        }
        if (isset($_POST['CompanyFieldset']) AND !empty($_POST['CompanyFieldset']['name'])) {
            $model->fid = NULL;
            $model->sid = ((int)$count + 1);
            $model->name = $_POST['CompanyFieldset']['name'];
            $model->type = $_POST['CompanyFieldset']['type'];
            $model->req = $_POST['CompanyFieldset']['req'];
            if (isset($_POST['CompanyFieldset']['value'])) {
                $select = Selects::model()->findByPk($_POST['CompanyFieldset']['value']);
                $model->value = $select->select_value;
                $model->select_id = $select->id;
            }
            if ($model->save(false)) {
                $this->redirect(array('fields'));
            }
        } else {
          $this->redirect(array('fields'));
      }
  }

    public function actionDelete_field($id)
  {
    if (Yii::app()->request->getIsAjaxRequest()) {
        $id = $_GET['id'];
    }
    CompanyFieldset::model()->deleteByPk($id);
    CompanyFields::model()->deleteAllByAttributes(array('fid' => $id)); 
}

public function actionUpdate_field($id)
{
    $model = CompanyFieldset::model()->findByPk($id);
    if (isset($_POST['CompanyFieldset'])) {
        $oldName = $model->name;
        $model->attributes = $_POST['CompanyFieldset'];
        if ($model->save()) {
            $rFields = CompanyFields::model()->updateAll(['name' => $model->name], 'name="' . $oldName . '"');
        }
        $this->redirect(array('fields', 'id' => $model->fid));
    }
    $this->render('update_field', array('model' => $model));
}

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'companies-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        Yii::app()->session->remove('cfields');
        $model = $this->loadModel($id);
        $fields = $model->flds;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['Companies'])) {
            $model->attributes = $_POST['Companies'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'fields' => $fields
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        //print_r($_GET);

        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            $model->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

    }

    public function actionDeleteFile($id, $file)
    {
        //$filename = iconv("UTF-8", "CP1251", $file); //in Windows systems
        $os_type = DetectOS::getOS();
        $filename = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file; //in Unix systems
        $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $id . '/' . $filename;
        if (is_file($documentPath)) {
            unlink($documentPath);
        }
        $path = Yii::getPathOfAlias('webroot') . '/media/' . $id;
        $files = $this->myscandir($path);
        foreach ($files as $item) {
            if (!is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $id . '/' . $item)) {
                $files2[] = $item;
            }
        }
        $value = implode(",", $files2);
        $value = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $value) : $value;
        Companies::model()->updateByPk($id, array('image' => $value));

    }

    public function actionBatchDelete()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $checkedIDs = $_GET['checked'];
            foreach ($checkedIDs as $id) {
                $this->loadModel($id)->delete();
            }
        }
    }

    /**
     * Lists all models.
     */

    public function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['compPageCount'] = $_GET['pageCount'];
        }
        $model = new CompaniesFull('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CompaniesFull'])) {
            $model->attributes = $_GET['CompaniesFull'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Открепляет сервис от модели.
     */
    public function actionServiceDelete()
    {
        //var_dump($_GET);exit;
        //$this->loadModel($id)->delete();
        $service_id = $_GET['service_id'];
        $company_id = $_GET['company_id'];
        $model = CompanyServices::model()->findByAttributes(array(
            'service_id' => $service_id,
            'company_id' => $company_id
        ));
        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    public function actionServiceAdd()
    {
        $model = new CompanyServices;

        if (isset($_POST['service']) and isset($_GET['company_id'])) {
            $model->service_id = $_POST['service'];
            $model->company_id = $_GET['company_id'];
            $model->save();
        }

        $company = Companies::model()->findByAttributes(array('id' => $_GET['company_id']));
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'services-grid',
            'dataProvider' => new CArrayDataProvider($company->services),
            'type' => 'striped bordered condensed',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array(
                'name:text:'.Yii::t('main-ui', 'Services'),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{delete}',
                    'deleteButtonUrl' => 'Yii::app()->createUrl("/companies/servicedelete", array("service_id"=>$data->id, "company_id"=>"' . $company->id . '"))',
                ),
            ),
        ));
    }

    public static function actionGetStreets(){
        if (isset($_GET['city_id'])) {
            $city_id = (int)$_GET['city_id'];
        }
        
        $streets = Streets::model()->findAllByAttributes(array('cid' => $city_id));
        $array = array();
        foreach ($streets as $one) {
            $array[$one->id] = $one->name;
        }
        echo json_encode($array);
    }

    public static function actionGetStreetsAndSelected(){
        if (isset($_GET['city_id'])) {
            $city_id = (int)$_GET['city_id'];
        }
        if (isset($_GET['company_id'])) {
            $company_id = (int)$_GET['company_id'];
        }
        $company = Companies::model()->findByPk($company_id);
        $streets = Streets::model()->findAllByAttributes(array('cid' => $city_id));
        $array = array();
        foreach ($streets as $one) {
            $array[$one->id] = $one->name;
        }
        $result = [$array, $company['street']];
        echo json_encode($result);
    }
}
