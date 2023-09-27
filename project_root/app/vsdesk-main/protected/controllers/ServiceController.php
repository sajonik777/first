<?php


/**
 * Class ServiceController
 */
class ServiceController extends Controller
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
            'accessControl',// perform access control for CRUD operations
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
                'actions' => ['create', 'SelectGroup'],
                'roles' => ['createService'],
            ],
            [
                'allow',
                'actions' => ['view'],
                'roles' => ['viewService'],
            ],
            [
                'allow',
                'actions' => ['index', 'getServices'],
                'roles' => ['listService'],
            ],
            [
                'allow',
                'actions' => ['update', 'SelectGroup', 'EscalateSave', 'EscalateDel', 'AddSupportService', 'RemoveSupportService'],
                'roles' => ['updateService'],
            ],
            [
                'allow',
                'actions' => ['delete'],
                'roles' => ['deleteService',"RemoveSupportService"],
            ],

            [
                'deny',// deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     *
     */
    public function actionEscalateSave()
    {
        if (isset($_POST['Escalates']['id'])) {
            $model = Escalates::model()->findByPk($_POST['Escalates']['id']);
        } else {
            $model = new Escalates();
        }

        if (isset($_POST['Escalates'])) {
            $model->attributes = $_POST['Escalates'];
            $model->group_id = isset($_POST['Escalates']['group_id']) ? (int)$_POST['Escalates']['group_id'] : null;
            $model->manager_id = isset($_POST['Escalates']['manager_id']) ? (int)$_POST['Escalates']['manager_id'] : null;
            $model->save();
        }

        echo \CJSON::encode($model->getErrors());
    }

    /**
     * @throws CDbException
     */
    public function actionEscalateDel()
    {
        $model = Escalates::model()->findByPk($_POST['id']);

        if ($model->delete()) {
            echo \CJSON::encode(['message' => 'ok']);
        }

        echo \CJSON::encode($model->getErrors());
    }

    /**
     * @throws CDbException
     */
    public function actionAddSupportService()
    {
        $model = Service::model()->findByPk($_POST['user_service_id']);
        if ($model->add_support_service($_POST['support_service_id'])) {
            echo \CJSON::encode(['message' => 'ok']);
        }
        echo \CJSON::encode($model->getErrors());
    }

     /**
     * @throws CDbException
     */
    public function actionRemoveSupportService()
    {
        $model = Service::model()->findByPk($_POST['user_service_id']);
        if ($model->remove_support_service($_POST['support_service_id'])) {
            echo \CJSON::encode(['message' => 'ok']);
        }
        echo \CJSON::encode($model->getErrors());
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     * @throws CHttpException
     */
    public function actionView($id)
    {
        //setting dafault timezone to Moscow
        $model = $this->loadModel($id);
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->render('view', [
            'model' => $model,
            'history' => $model->service_history,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return array|mixed|Service|null
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Service::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Service;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Service'])) {
            $model->attributes = $_POST['Service'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     *
     */
    public function actionSelectGroup()
    {
        $model = new Service;
        if ($_POST['Service']['gtype'] == 1) {
            //$userlist = CUsers::all();
            $user_list = CUsers::all();
            $name = Yii::t('main-ui', 'Manager');
            $mod = 'manager';
        } else {
            $userlist = Groups::model()->findAll();
            $user_list = CHtml::listData($userlist, 'name', 'name');
            $name = Yii::t('main-ui', 'Group');
            $mod = 'group';
        }
        echo CHtml::activeLabelEx($model, $mod);
        echo CHtml::activeDropDownList($model, $mod, $user_list, array('class' => 'span12'));
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
        // var_dump($model);
        // die();
        if (isset($_POST['Service'])) {
            $model->attributes = $_POST['Service'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }
        // $model->sla = explode(',', $model->sla);

        $this->render('update', [
            'model' => $model,
            'escalateNew' => new Escalates(),
        ]);
    }

    /**
     * Lists all models.
     */

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        $zayavki = Request::model()->findByAttributes(array('service_name' => $model->name));
        $problems = Problems::model()->findByAttributes(array('service' => $model->name));
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            // we only allow deletion via POST request
            if ($zayavki or $problems) {
                throw new CHttpException(400,
                    'Невозможно удалить сервис, привязанный к одной или нескольким заявкам или проблемам! Удалите или измените заявки.');
            } else {
                $this->loadModel($id)->delete();
            }

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
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['servicesPageCount'] = $_GET['pageCount'];
        }
        $model = new Service('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Service'])) {
            $model->attributes = $_GET['Service'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     *
     */
    public function actionGetServices()
    {
        $model = new Report;
        if (isset($_POST['Report']['company']) and !empty($_POST['Report']['company'])) {
            $allServices = array();
            $company = Companies::model()->findByAttributes(['name' => $_POST['Report']['company']]);
            if ($company) {
                $companyServices = $company->getServicesArray();
                foreach ($companyServices as $key => $value) {
                    if (!isset($allServices[$key])) {
                        $allServices[$key] = $value;
                    }
                }
            }
            /** @var Depart $depart */
            $depart = Depart::model()->findAllByAttributes(['company' => $_POST['Report']['company']]);
            if ($depart) {
                foreach ($depart as $item) {
                    $dep = Depart::model()->findByPk($item->id);
                    $departServices = $dep->getServicesArray();
                    foreach ($departServices as $key => $value) {
                        if (!isset($allServices[$key])) {
                            $allServices[$key] = $value;
                        }
                    }
                }

            }
            foreach ($allServices as $service => $value) {
                $services[] = Service::model()->findByPk($service);
            }
        } else {
            $services = Service::model()->findAll();
        }
        echo CHtml::activeLabel($model, 'service');
        echo CHtml::activeDropDownList($model, 'service', CHtml::listData($services, 'id', 'name'),
            ['empty' => '', 'class' => 'span12']);
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'service-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
