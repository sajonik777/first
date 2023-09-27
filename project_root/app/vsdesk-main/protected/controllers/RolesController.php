<?php

class RolesController extends Controller
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'create', 'delete', 'update', 'toggle', 'reload'),
                'roles' => array('rolesSettings'),
            ),

            array('deny',  // deny all users
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
        $model = Roles::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actions()
    {
        return array(
            'toggle' => array(
                'class' => 'bootstrap.actions.TbToggleAction',
                'modelName' => 'RolesRights',
            )
        );
    }

    public function actionReload()
    {
        $all_roles = Roles::model()->findAll(); //Get roles from 'roles' table
        foreach ($all_roles as $role_item) {// check every role
            $arr_exists = array(NULL);
            $arr_new = array(NULL);
            $diff = array(NULL);
            $exists = RolesRights::model()->findAllByAttributes(array('rid' => $role_item->id)); //get existing roles_rights
            if ($exists) {
                foreach ($exists as $item) {
                    $arr_exists[$item->name] = array(
                        'description' => $item->description,
                        'data' => $item->category,
                    );
                }
                $roles = Yii::app()->authManager->roles;
                foreach ($roles as $role => $value) {
                    $arr_new[$role] = array(
                        'description' => $value->description,
                        'data' => $value->data,
                    );
                }
                $diff = array_diff_assoc(array_map('serialize', $arr_new), array_map('serialize', $arr_exists));
                $diff = array_map('unserialize', $diff);
                if ($diff) {
                    foreach ($diff as $role => $value) {
                        if ($role !== 'guest') {
                            $rights = new RolesRights();
                            $rights->rid = $role_item->id;
                            $rights->rname = $role_item->name;
                            $rights->name = $role;
                            $rights->description = $value['description'];
                            $rights->category = $value['data'];
                            $rights->value = 0;
                            $rights->save(false);
                        }
                    }
                }
            } else {
                $roles = Yii::app()->authManager->roles;
                foreach ($roles as $role => $value) {
                    if ($role !== 'guest') {
                        $rights = new RolesRights();
                        $rights->rid = $role_item->id;
                        $rights->rname = $role_item->name;
                        $rights->name = $role;
                        $rights->description = $value->description;
                        $rights->category = $value->data;
                        $rights->value = 0;
                        $rights->save(false);
                    }
                }
            }
        }
        $this->redirect('index');
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Roles;
// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            if ($model->save())
                $this->redirect(array('update', 'id' => $model->id));
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
        $filter_arr = array();
        $filtersForm = new FiltersForm;
        if (isset($_GET['FiltersForm'])) {
            $filtersForm->filters = $_GET['FiltersForm'];
        }

        // Get rawData and create dataProvider
        $criteria = new CDbCriteria;
        $criteria->compare('rid', $id);
        $sort = new CSort;
        $sort->defaultOrder = 'category ASC';
        $rawData = RolesRights::model()->findAll($criteria);
        $filteredData = $filtersForm->filter($rawData);
        $dataProvider = new CArrayDataProvider($filteredData, array(
            'pagination' => array(
                'pageSize' => 15,
            ),
            'sort' => $sort,
        ));
        foreach ($rawData as $item) {
            $filter_arr[$item->category] = $item->category;
        }
        $filters = array_filter(array_unique($filter_arr));
        array_multisort($filters);
        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array(
            'model' => $model,
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
            'filters' => $filters,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            $users = CUsers::model()->findAllByAttributes(array('role' => $model->value));
            if (!$users) {
                $model->delete();
            } else {
                throw new CHttpException(400, Yii::t('main-ui', 'You cannot delete a role that is used by users. Change user to remove a role!'));
            }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Roles']))
            $model->attributes = $_GET['Roles'];

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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'roles-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
