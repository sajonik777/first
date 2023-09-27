<?php

/**
 * Class RequestProcessingRulesController
 */
class RequestprocessingrulesController extends Controller
{
    /**
     * @inheritDoc
     */
    public $layout = '//layouts/design3';

    /**
     * @inheritDoc
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    /**
     * @inheritDoc
     */
    public function accessRules()
    {
        return [
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index'],
                'roles' => ['listRequestProcessingRules'],
            ],
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['view', 'reorder'],
                'roles' => ['viewRequestProcessingRules'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create'],
                'roles' => ['createRequestProcessingRules'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['update', 'conditionDel', 'conditionSave', 'actionDel', 'actionSave'],
                'roles' => ['updateRequestProcessingRules'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['delete'],
                'roles' => ['deleteRequestProcessingRules'],
            ],
            [
                'deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new RequestProcessingRules('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RequestProcessingRules'])) {
            $model->attributes = $_GET['RequestProcessingRules'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new RequestProcessingRules();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RequestProcessingRules'])) {
            $model->attributes = $_POST['RequestProcessingRules'];
            if ($model->save()) {
                $this->redirect(['update', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->render('update', [
            'model' => $model,
            'readOnly' => true,
        ]);
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['RequestProcessingRules'])) {
            $model->attributes = $_POST['RequestProcessingRules'];
            if ($model->save()) {
                $this->redirect(['update', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
            'readOnly' => !Yii::app()->user->checkAccess('updateRequestProcessingRules'),
        ]);
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
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     *
     */
    public function actionConditionSave()
    {
        if (isset($_POST['RequestProcessingRuleConditions']['id'])) {
            $model = RequestProcessingRuleConditions::model()->findByPk($_POST['RequestProcessingRuleConditions']['id']);
        } else {
            $model = new RequestProcessingRuleConditions();
        }

        if (isset($_POST['RequestProcessingRuleConditions'])) {
            $model->attributes = $_POST['RequestProcessingRuleConditions'];
            $model->save();
        }

        echo \CJSON::encode($model->getErrors());
    }

    /**
     * @throws CDbException
     */
    public function actionConditionDel()
    {
        $model = RequestProcessingRuleConditions::model()->findByPk($_POST['id']);

        if ($model->delete()) {
            echo \CJSON::encode(['message' => 'ok']);
        }

        echo \CJSON::encode($model->getErrors());
    }

    /**
     *
     */
    public function actionActionSave()
    {
        if (isset($_POST['RequestProcessingRuleActions']['id'])) {
            $model = RequestProcessingRuleActions::model()->findByPk($_POST['RequestProcessingRuleActions']['id']);
        } else {
            $model = new RequestProcessingRuleActions();
        }

        if (isset($_POST['RequestProcessingRuleActions'])) {
            $model->attributes = $_POST['RequestProcessingRuleActions'];
            $model->save();
        }

        echo \CJSON::encode($model->getErrors());
    }

    /**
     * @throws CDbException
     */
    public function actionActionDel()
    {
        $model = RequestProcessingRuleActions::model()->findByPk($_POST['id']);

        if ($model->delete()) {
            echo \CJSON::encode(['message' => 'ok']);
        }

        echo \CJSON::encode($model->getErrors());
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id the ID of the model to be loaded
     *
     * @return RequestProcessingRules the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = RequestProcessingRules::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}
