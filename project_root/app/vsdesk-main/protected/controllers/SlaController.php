<?php

class SlaController extends Controller
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
        return array(
            array('allow',
                'actions' => array('create'),
                'roles' => array('createSla'),
            ),
            array('allow',
                'actions' => array('view'),
                'roles' => array('viewSla'),
            ),
            array('allow',
                'actions' => array('getsla'),
                'roles' => array('viewSla'),
            ),
            array('allow',
                'actions' => array('getola'),
                'roles' => array('viewSla'),
            ),
            array('allow',
                'actions' => array('index'),
                'roles' => array('listSla'),
            ),
            array('allow',
                'actions' => array('update', 'loadxml'),
                'roles' => array('updateSla'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteSla'),
            ),

            array('deny',// deny all users
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
        // $model = $this->loadModel($id);
        // date_default_timezone_set(Yii::app()->params['timezone']);
        // $this->render('view', [
        //     'model' => $model,
        //     'history' => $model->service_history,
        // ]);
        date_default_timezone_set(Yii::app()->params['timezone']);

        $model = $this->loadModel($id);

        $this->render('view', array(
            'model' => $model,
            'history' => $model->sla_history,
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
        $model = Sla::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public
    function actionLoadXml()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $xml = simplexml_load_file('http://xmlcalendar.ru/data/ru/'.date("Y").'/calendar.xml');
            if($xml->days){
                $days = $xml->days->day;
                $holidays = array();
                foreach ($days as $key => $value){
                    if($value["t"] == 1){
                        $val = explode('.', $value["d"]);
                        $date = $val[1].'.'.$val[0].'.*';
                        $holidays[] = $date;
                    }
                }
                $holidays_value = implode(',', $holidays);
                echo $holidays_value;
            } else {
                echo "false";
            }

        }
    }

    public
    function actionGetSLA()
    {
        $slas = Sla::model()->findAllByAttributes(['sla_type'=>'sla']);
        $res = [];
        foreach($slas as $s){
            $res[$s['id']] = $s['name'];
        }
        echo CJSON::encode($res);
    }

    public
    function actionGetOLA()
    {
        $slas = Sla::model()->findAllByAttributes(['sla_type'=>'ola']);
        $res = [];
        foreach($slas as $s){
            $res[$s['id']] = $s['name'];
        }
        echo CJSON::encode($res);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public
    function actionCreate()
    {
        $model = new Sla;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Sla'])) {
            $model->attributes = $_POST['Sla'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
    public
    function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        //$services = Service::model()->findAllByAttributes(array('sla' => $model->name));

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Sla'])) {
            $model->attributes = $_POST['Sla'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
    public
    function actionDelete($id)
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
            }
        $model = $this->loadModel($id);
        $services = Service::model()->findByAttributes(array('sla' => $model->name));
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            // we only allow deletion via POST request
            if ($services) {
                throw new CHttpException(400, 'Невозможно удалить уровень сервиса, привязанный к одному или более сервисам!');

            } else {
                $this->loadModel($id)->delete();
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public
    function actionIndex()
    {
        $model = new Sla('search');
        $model->unsetAttributes();  // clear any default values
        //Yii::app()->user->setFlash('info', '<strong>'.Yii::t('main-ui','Welcome').' '.Yii::app()->user->name.'!</strong><br/>');
        if (isset($_GET['Sla']))
            $model->attributes = $_GET['Sla'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sla-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
