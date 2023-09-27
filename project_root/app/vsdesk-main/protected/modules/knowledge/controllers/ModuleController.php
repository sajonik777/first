<?php

class ModuleController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/design3';

    /**
     * @return array action filters
     */
    public
    function filters()
    {
        return array(
            'accessControl',// perform access control for CRUD operations
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
        $portal = Yii::app()->params['allowportal'];
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index'),
                'roles' => array('listKB'),
            ),
            $portal == 0 ? array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('view'),
                'roles' => array('viewKB'),
            ): array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('view'),
                'users' => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create'),
                'roles' => array('createKB'),
            ),

            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('match'),
                'roles' => array('viewKB'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('deletefile', 'update'),
                'roles' => array('updateKB'),
            ),
            array('allow', // allow manager user to perform 'admin' and 'delete' actions
                'actions' => array('delete'),
                'roles' => array('deleteKB'),
            ),
            array('allow', // deny all users
                'actions' => array('view', 'index'),
                'users' => array('*'),
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
    public
    function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);

        if ($this->check_access($id) == 1 or Yii::app()->user->checkaccess('systemAdmin')){
            $files = explode(",", $model->image);
            $model = $this->loadModel($id);
            $this->render('view',array(
                'model'=>$model,
                'history' => $model->knowledge_history,
                'files'=>$files,
            ));
        }else{
            throw new CHttpException(400, Yii::t('main-ui','You do not have sufficient rights to view this entry'));
        }

    }



    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public
    function actionCreate()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Knowledge;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Knowledge'])){
            $model->attributes = $_POST['Knowledge'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    public
    function actionDeleteFile($id, $file)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $model->attributes = $model->attributes;
        $os_type = DetectOS::getOS();
        $filename = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251' , $file) : $file;
        $documentPath = Yii::getPathOfAlias('webroot').'/media/kb/'.$model->id.'/'.$filename;
        if (is_file($documentPath))
            unlink($documentPath);
        $filelist = array();
        $path = Yii::getPathOfAlias('webroot').'/media/kb/'.$model->id;
        $filelist = $this->myscandir($path);
        $value = implode(",",$filelist);
        $value = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $value) : $value;
        Knowledge::model()->updateByPk($id,array('image'=>$value));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public
    function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $files = explode(",", $model->image);
        if(isset($_POST['Knowledge'])){
            $model->attributes = $_POST['Knowledge'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }
        if ($this->check_access($id) == 1 or Yii::app()->user->checkaccess('systemAdmin')){
            $this->render('update',array(
                'model'=>$model,
                'files'=>$files,
            ));
        }else{
            throw new CHttpException(400, Yii::t('main-ui','You do not have sufficient rights to view this entry'));
        }

    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public
    function actionDelete($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        //----
        $allFiles = [];
        $result = [];
        preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->content, $result);
        $result2 = [];
        preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->content, $result2);
        if (!empty($result[0][0])) {
            $allFiles = array_merge($allFiles, $result[1]);
        }
        if (!empty($result2[0][0])) {
            $allFiles = array_merge($allFiles, $result2[1]);
        }
        if (!empty($allFiles)) {
            foreach ($allFiles as $file) {
                $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                if (is_file($documentPath)) {
                    unlink($documentPath);
                }
            }
        }
        //----
        $files = explode(",",$model->image);
        foreach ($files as $file){
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251' , $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot').'/media/kb/'.$model->id.'/'.$file;
            if (is_file($documentPath))
                unlink($documentPath);
        }
        if(is_dir(Yii::getPathOfAlias('webroot').'/media/kb/'.$model->id)){
            rmdir(Yii::getPathOfAlias('webroot').'/media/kb/'.$model->id);
        }
        if(Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()){
            // we only allow deletion via POST request
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    /**
     * Manages all models.
     */
    public
    function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['knowPageCount'] = $_GET['pageCount'];
        }
        if (Yii::app()->user->isGuest){
            $criteria = new CDbCriteria;
            $criteria->compare('access', 'Гость', true);
            $criteria->order = ' id DESC';
            $model = Knowledge::model()->findAll($criteria);
        } else {
            $model = new Knowledge('search');
            $model->unsetAttributes();  // clear any default values
        }
        if(isset($_GET['Knowledge'])){
            $model->attributes = $_GET['Knowledge'];
        }
        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public
    function loadModel($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = Knowledge::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function check_access($id){
        $model = $this->loadModel($id);
        $access_true = 0;
        $access_list = Categories::model()->findByAttributes(array('name'=>$model->bcat_name));
        $roles = explode(",", $access_list->access);
        foreach ($roles as $role) {
            if(Yii::app()->user->isGuest){
                if('Гость' == $role){
                    $access_true = 1;
                }
            }else{
                $role_id = Roles::model()->findByAttributes(array('name'=>trim($role)));
                $rvalue = $role_id['value'];
                if(strtolower(Yii::app()->user->role) == $rvalue){
                    $access_true = 1;
                }
            }
        }
        return $access_true;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax'] === 'brecords-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function myscandir($dir, $sort=0)
    {
        $list = scandir($dir, $sort);

        // если директории не существует
        if (!$list) return false;

        // удаляем . и .. (я думаю редко кто использует)
        if ($sort == 0) unset($list[0],$list[1]);
        else unset($list[count($list)-1], $list[count($list)-1]);
        return $list;
    }


    /**
     * Возвращает или выводит объекты Knowledge, содержащие query.
     * @param string $query - строка запросы для поиска по БЗ
     * @param bool $return - true усли вернуть результат, false если вывести результат
     */
    public
    function actionMatch($query="", $return=false)
    {
        if($query == ""){
            $query = Yii::app()->request->getQuery('query');
        }

        if ($return) {
            return Knowledge::model()->searchSame($query);
        }
        $knowledges = Knowledge::model()->searchSame($query);
        $result = [];
        foreach($knowledges->getData() as $k){
            array_push($result,
                [
                    "id" => $k->id,
                    "name" => $k->name,
                    "content" => $k->content,
                    "created" => $k->created,
                    "bcat_name" => $k->bcat_name,
                ]
            );

        }
        echo json_encode($result);
    }


}
