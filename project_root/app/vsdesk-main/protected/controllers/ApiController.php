<?php

/**
 * Class ApiController
 */
class ApiController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    /**
     * @param $action
     * @return bool
     */
    protected function beforeAction($action)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->_checkAuth();

        return true;
    }

    /**
     * Список заявок
     */
    public function getRequestList()
    {
        $model = new Request('search');
        $model->unsetAttributes();
        $model->attributes = $_GET['Request'] = $_GET;
        $models = $model->search()->getData();
        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }

        $this->_sendResponse(200, CJSON::encode($models));
    }

    /**
     * Список истории заявки
     */
    public function getHistoryList()
    {
        $model = new History('search');
        $model->unsetAttributes();
        $model->attributes = $_GET['History'] = $_GET;
        $models = $model->search();
        $models->setPagination(false);

        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }
        foreach ($models->getData() as $one) {
            $new[] = $one;
        }
        $this->_sendResponse(200, CJSON::encode($new));
    }

    /**
     * Список проблем
     */
    public function getProblemList()
    {
        $model = new Problems('search');
        $model->unsetAttributes();
        $model->setAttributes($_GET, false);
        $models = $model->search()->getData();
        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }

        $this->_sendResponse(200, CJSON::encode($models));
    }

    /**
     * Список пользователей
     */
    public function getUserList()
    {
        $model = new CUsers('search');
        $model->unsetAttributes();
        $model->attributes = $_GET['CUsers'] = $_GET;
        $models = $model->search()->getData();

        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }

        $this->_sendResponse(200, CJSON::encode($models));
    }

    /**
     * Список компаний
     */
    public function getCompanyList()
    {
        $model = new Companies('search');
        $model->unsetAttributes();
        $model->attributes = $_GET['Companies'] = $_GET;
        $models = $model->search()->getData();

        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }

        $this->_sendResponse(200, CJSON::encode($models));
    }

    /**
     * Список активов
     */
    public function getAssetList()
    {
        $model = new Asset('search');
        $model->unsetAttributes();
        $model->setAttributes($_GET, false);
        $models = $model->search()->getData();
        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }

        $this->_sendResponse(200, CJSON::encode($models));
    }

    /**
     * Список КЕ
     */
    public function getCunitList()
    {
        $model = new Cunits('search');
        $model->unsetAttributes();
        $model->setAttributes($_GET, false);
        $models = $model->search()->getData();
        if (empty($models)) {
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        }

        $this->_sendResponse(200, CJSON::encode($models));
    }

    /**
     * List action
     */
    public function actionList()
    {
        // Get the respective model instance
        switch ($_GET['model']) {
            case 'requests':
                if (Yii::app()->user->checkAccess('listRequest')) {
                    $this->getRequestList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'history':
                if (Yii::app()->user->checkAccess('listRequest')) {
                    $this->getHistoryList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'problems':
                if (Yii::app()->user->checkAccess('listProblem')) {
                    $this->getProblemList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'users':
                if (Yii::app()->user->checkAccess('listUser')) {
                    $this->getUserList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'companies':
                if (Yii::app()->user->checkAccess('listCompany')) {
                    $this->getCompanyList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'assets':
                if (Yii::app()->user->checkAccess('listAsset')) {
                    $this->getAssetList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'cunits':
                if (Yii::app()->user->checkAccess('listUnit')) {
                    $this->getCunitList();
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            default:
                $this->_sendResponse(501, sprintf(
                    'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                    $_GET['model']));
                Yii::app()->end();
        }
    }

    /**
     * Просмотр заявки
     * @param $id
     */
    public function getRequest($id)
    {
        list($controller) = Yii::app()->createController('request');
        try {
            $model = $controller->loadModel($id);
        } catch (Throwable $e) {
            $this->_sendResponse(403, 'Access is denied');
        }
        if (Yii::app()->user->checkAccess('systemUser')) {
            $subs = Comments::model()->with('commFiles')->findAllByAttributes(['rid' => $id, 'show' => 0]);
        } else {
            $subs = $model->comms;
        }
        $comments = [];
        foreach ($subs as $comment) {
            if ($comment->commFiles) {
                $cf = [];
                foreach ($comment->commFiles as $fl) {
                    $cf[] = [
                        'id' => $fl->id,
                        'name' => $fl->name,
                        'file_name' => Yii::app()->params['homeUrl'] . '/uploads/' . $fl->file_name,
                    ];
                }
                $comments[] = array_merge($comment->attributes, ['files' => $cf]);
            } else {
                $comments[] = array_merge($comment->attributes, ['files' => []]);
            }
        }

        $files = [];
        if (!empty($model->reqFiles)) {
            foreach ($model->reqFiles as $file) {
                /** @var Files $file */
                $files[] = [
                    'id' => $file->id,
                    'name' => $file->name,
                    'file_name' => Yii::app()->params['homeUrl'] . '/uploads/' . $file->file_name,
                ];
            }
        }
        $model_arr = $model->attributes;
        $model_arr['subs'] = $comments;
        $model_arr['files'] = $files;
        if (empty($model)) {
            $this->_sendResponse(404, 'No Item found with id ' . $id);
        }

        $this->_sendResponse(200, CJSON::encode($model_arr));
    }

    /**
     * Просмотр проблемы
     * @param $id
     */
    public function getProblem($id)
    {
        $model = Problems::model()->findByPk($id);
        $model_arr = $model->attributes;
        if (empty($model)) {
            $this->_sendResponse(404, 'No Item found with id ' . $id);
        }

        $this->_sendResponse(200, CJSON::encode($model_arr));
    }

    /**
     * Просмотр пользователя
     * @param $id
     */
    public function getUser($id)
    {
        if (Yii::app()->user->checkAccess('systemUser')) {
            $model = CUsers::model()->findByPk(Yii::app()->user->id);
        } else {
            $model = CUsers::model()->findByPk($id);
        }
        $model_arr = $model->attributes;
        if (empty($model)) {
            $this->_sendResponse(404, 'No Item found with id ' . $id);
        }

        $this->_sendResponse(200, CJSON::encode($model_arr));
    }

    /**
     * Просмотр компании
     * @param $id
     */
    public function getCompany($id)
    {
        $model = Companies::model()->findByPk($id);
        $model_arr = $model->attributes;
        if (empty($model)) {
            $this->_sendResponse(404, 'No Item found with id ' . $id);
        }

        $this->_sendResponse(200, CJSON::encode($model_arr));
    }

    /**
     * Просмотр актива
     * @param $id
     */
    public function getAsset($id)
    {
        $model = Asset::model()->findByPk($id);
        $model_arr = $model->attributes;
        $items = [];
        $data = AssetValues::model()->findAll('asset_id=:asset_id', [':asset_id' => $model->id]);
        foreach ($data as $data_item) {
            $items[] = [
                'label' => $data_item->asset_attrib_name,
                'value' => $data_item->value,
            ];
        }

        $model_arr['items'] = $items;

        if (empty($model)) {
            $this->_sendResponse(404, 'No Item found with id ' . $id);
        }

        $this->_sendResponse(200, CJSON::encode($model_arr));
    }

    /**
     * Просмотр КЕ
     * @param $id
     */
    public function getCunit($id)
    {
        $model = Cunits::model()->findByPk($id);
        $model_arr = $model->attributes;
        if (empty($model)) {
            $this->_sendResponse(404, 'No Item found with id ' . $id);
        }

        $this->_sendResponse(200, CJSON::encode($model_arr));
    }

    /**
     * View action
     */
    public function actionView()
    {
        if (!isset($_GET['id'])) {
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');
        }

        switch ($_GET['model']) {
            case 'requests':
                if (Yii::app()->user->checkAccess('viewRequest')) {
                    $this->getRequest($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'problems':
                if (Yii::app()->user->checkAccess('viewProblem')) {
                    $this->getProblem($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'users':
                if (Yii::app()->user->checkAccess('viewUser')) {
                    $this->getUser($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'companies':
                if (Yii::app()->user->checkAccess('viewCompany')) {
                    $this->getCompany($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'assets':
                if (Yii::app()->user->checkAccess('viewAsset')) {
                    $this->getAsset($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'cunits':
                if (Yii::app()->user->checkAccess('viewUnit')) {
                    $this->getCunit($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            default:
                $this->_sendResponse(501,
                    sprintf('Mode <b>view</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
    }

    /**
     * Загрузка файла
     * @param $data
     */
    public function fileCreate($data)
    {
        $model = new Files;

        if (null !== CUploadedFile::getInstanceByName('file')) {
            $model->uploadFile = CUploadedFile::getInstanceByName('file');
        }

        $this->_sendResponse(200, CJSON::encode($model->upload()));
    }

    /**
     * Создание заявки
     * @param $data
     */
    public function requestCreate($data)
    {
        $model = new Request();
        $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
//        foreach ($data as $var => $value) {
        //            $_POST['Request'][$var] = $value;
        //            if ($model->hasAttribute($var)) {
        //                $model->$var = $value;
        //            } else {
        //                $this->_sendResponse(500,
        //                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
        //                        $_GET['model']));
        //            }
        //
        //        }
        $model->attributes = $_POST['Request'] = $data;
        // Наблюдатель
        if ($model->service_rl) {
            if (isset($model->service_rl->watcher) AND !empty($model->service_rl->watcher)) {
                $model->watchers = $model->service_rl->watcher;
            }
        }
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Создание проблемы
     * @param $data
     */
    public function problemCreate($data)
    {
        $model = new Problems;
        foreach ($data as $var => $value) {
            $_POST['Problems'][$var] = $value;
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
            }
        }
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Создание пользователя
     * @param $data
     */
    public function userCreate($data)
    {
        $model = new CUsers;
        foreach ($data as $var => $value) {
            $_POST['CUsers'][$var] = $value;
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
            }
        }
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Создание компании
     * @param $data
     */
    public function companyCreate($data)
    {
        $model = new Companies;
        foreach ($data as $var => $value) {
            $_POST['Companies'][$var] = $value;
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
            }
        }
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Создание актива
     * @param $dataA
     */
    public function assetCreate($dataA)
    {
        $model = new Asset;

        foreach ($dataA as $var => $value) {
            $_POST['Asset'][$var] = $value;
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
            }
        }

        $model->attributes = $_POST['Asset'];
        $data = AssetAttribValue::model()->findAllByAttributes(['asset_attrib_id' => $_POST['Asset']['asset_attrib_id']]);
        $i = 0;
        $data2 = AssetAttrib::model()->findAllByAttributes(['id' => $_POST['Asset']['asset_attrib_id']]);
        foreach ($data2 as $data_item) {
            $model->asset_attrib_name = $data_item->name;
        }

        if ($model->save()) {
            foreach ($data as $value) {
                $i = $i + 1;
                $model_s = new AssetValues;
                $model_s->asset_id = $model->id;
                $model_s->asset_attrib_id = $value->asset_attrib_id;
                $model_s->asset_attrib_name = $value->name;
                $model_s->value = $_POST['Asset'][$i . 'name'];
                $model_s->save(false);
            }
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Создание КЕ
     * @param $data
     */
    public function cunitCreate($data)
    {
        $model = new Cunits;
        foreach ($data as $var => $value) {
            $_POST['Cunits'][$var] = $value;
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
            }
        }
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Создание комментария
     * @param $data
     */
    public function commentCreate($data)
    {
        list($controller) = Yii::app()->createController('request');
        try {
            $request = $controller->loadModel($data['rid']);
        } catch (Throwable $e) {
            $this->_sendResponse(403, 'Access is denied');
        }
        if (empty($request)) {
            $this->_sendResponse(403, 'Access is denied');
        }
        $model = new Comments;
        $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);
        foreach ($data as $var => $value) {
            $_POST['Comments'][$var] = $value;
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                $this->_sendResponse(500,
                    sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $_GET['model']));
            }
        }
        $model->timestamp = date('d.m.Y H:i:s');
        $fullname = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
        $model->author = $fullname->fullname;
        if (Yii::app()->user->checkAccess('systemUser')) {
            $model->show = 0;
        } else {
            $model->show = $_POST['Comments']['show'];
        }
        if ($model->save()) {
            $request->updateByPk($model->rid, ['lastactivity' => date('Y-m-d H:i:s')]);
            $this->addHistory(Yii::t('main-ui',
                'Added new comment: ') . '<b>' . $_POST['Comments']['comment'] . '</b>',
                $model->rid);
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error) {
                    $msg .= "<li>$attr_error</li>";
                }
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    /**
     * Create action
     */
    public function actionCreate()
    {
        if (empty($_POST)) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }
        unset($_POST);
        switch ($_GET['model']) {
            case 'files':
                if (Yii::app()->user->checkAccess('uploadFilesRequest')) {
                    $this->fileCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'requests':
                if (Yii::app()->user->checkAccess('createRequest')) {
                    $this->requestCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'problems':
                if (Yii::app()->user->checkAccess('createProblem')) {
                    $this->problemCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'users':
                if (Yii::app()->user->checkAccess('createUser')) {
                    $this->userCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'companies':
                if (Yii::app()->user->checkAccess('createCompany')) {
                    $this->companyCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'assets':
                if (Yii::app()->user->checkAccess('createAsset')) {
                    $this->assetCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'cunits':
                if (Yii::app()->user->checkAccess('createUnit')) {
                    $this->cunitCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'comments':
                if (Yii::app()->user->checkAccess('updateRequest')) {
                    $this->commentCreate($data);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            default:
                $this->_sendResponse(501,
                    sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
    }

    /**
     * @param $id
     * @param $data
     */
    public function requestUpdate($id, $data)
    {
        $model = RequestAPI::model()->findByPk($id);
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        unset($_POST);
        //$_POST['Request'] = $model->attributes;
        foreach ($data as $var => $value) {
            if ($model->hasAttribute($var) and !empty($value)) {
                $model->$var = $value;
            }
        }
        if (Yii::app()->user->checkAccess('systemManager') and Yii::app()->params['monopoly'] == 1) {
            if (($model->Managers_id == null) and ($model->update_by == null)) {
                Request::model()->updateByPk($id, ['update_by' => Yii::app()->user->name]);
            }
        }

        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = 'error';
            $this->_sendResponse(500, $msg);
        }
    }

    public function problemUpdate($id, $data)
    {
        $model = Problems::model()->findByPk($id);
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        unset($_POST);
        $_POST['Problems'] = $model->attributes;
        foreach ($data as $var => $value) {
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                /*$this->_sendResponse(500,
            sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
            $var, $_GET['model']));*/
            }
        }

        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = 'error';
            $this->_sendResponse(500, $msg);
        }

    }

    public function userUpdate($id, $data)
    {
        $model = CUsers::model()->findByPk($id);
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        unset($_POST);

        foreach ($data as $var => $value) {
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                /*$this->_sendResponse(500,
            sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
            $var, $_GET['model']));*/
            }
        }

        $_POST['CUsers'] = $model->attributes;
        if (isset($_POST['CUsers']['department'])) {
            $units = Cunits::model()->findAllByAttributes(['user' => $model->Username]);
            foreach ($units as $unit) {
                Cunits::model()->updateByPk($unit->id, ['dept' => $_POST['CUsers']['department']]);
            }
            $assets = Asset::model()->findAllByAttributes(['cusers_name' => $model->Username]);
            foreach ($assets as $asset) {
                Asset::model()->updateByPk($asset->id, ['cusers_dept' => $_POST['CUsers']['department']]);
            }
        }

        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = 'error';
            $this->_sendResponse(500, $msg);
        }
    }

    public function companyUpdate($id, $data)
    {
        $model = Companies::model()->findByPk($id);
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        unset($_POST);
        $_POST['Companies'] = $model->attributes;
        foreach ($data as $var => $value) {
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                /*$this->_sendResponse(500,
            sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
            $var, $_GET['model']));*/
            }
        }

        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = 'error';
            $this->_sendResponse(500, $msg);
        }

    }

    public function assetUpdate($id, $data)
    {
        $model = Asset::model()->findByPk($id);
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        unset($_POST);

        foreach ($data as $var => $value) {
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                /*$this->_sendResponse(500,
            sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
            $var, $_GET['model']));*/
            }
        }

        $_POST['Asset'] = $model->attributes;
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = 'error';
            $this->_sendResponse(500, $msg);
        }
    }

    public function cunitUpdate($id, $data)
    {
        $model = Cunits::model()->findByPk($id);
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        unset($_POST);

        foreach ($data as $var => $value) {
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                /*$this->_sendResponse(500,
            sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
            $var, $_GET['model']));*/
            }
        }

        $_POST['Cunits'] = $model->attributes;
        if ($model->save()) {
            $this->_sendResponse(200, CJSON::encode($model));
        } else {
            $msg = 'error';
            $this->_sendResponse(500, $msg);
        }
    }

    public function actionUpdate()
    {
        $json = file_get_contents('php://input');
        $put_vars = CJSON::decode($json, true);
        switch ($_GET['model']) {
            case 'requests':
                if (Yii::app()->user->checkAccess('updateRequest')) {
                    $this->requestUpdate($_GET['id'], $put_vars);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'problems':
                if (Yii::app()->user->checkAccess('updateProblem')) {
                    $this->problemUpdate($_GET['id'], $put_vars);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'users':
                if (Yii::app()->user->checkAccess('updateUser')) {
                    $this->userUpdate($_GET['id'], $put_vars);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'companies':
                if (Yii::app()->user->checkAccess('updateCompany')) {
                    $this->companyUpdate($_GET['id'], $put_vars);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'assets':
                if (Yii::app()->user->checkAccess('updateAsset')) {
                    $this->assetUpdate($_GET['id'], $put_vars);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'cunits':
                if (Yii::app()->user->checkAccess('updateUnit')) {
                    $this->cunitUpdate($_GET['id'], $put_vars);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            default:
                $this->_sendResponse(501,
                    sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
    }

    public function requestDelete($id)
    {
        $model = Request::model()->findByPk($id);
        // Was a model found? If not, raise an error
        if ($model === null) {
            $this->_sendResponse(400,
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }

        $submodel = Request::model()->findAllByAttributes(['pid' => $id]);
        if (isset($submodel)) {
            foreach ($submodel as $delitem) {
                $files = explode(",", $delitem->image);
                foreach ($files as $file) {
                    $os_type = DetectOS::getOS();
                    $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
                    $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->id . '/' . $file;
                    if (is_file($documentPath)) {
                        unlink($documentPath);
                    }
                }
                if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id)) {
                    rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id);
                }
                $model2 = Request::model()->findByPk($delitem->id);
                $model2->delete();
                $message = 'User ' . Yii::app()->user->name . ' delete ticket #' . $delitem->id . ' named "' . $delitem->Name . '"';
                Yii::log($message, 'deleted', 'DELETED');
            }
        }
        $files = explode(",", $model->image);
        foreach ($files as $file) {
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->id . '/' . $file;
            if (is_file($documentPath)) {
                unlink($documentPath);
            }
        }
        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id)) {
            rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id);
        }
        $num = $model->delete();
        $message = 'User ' . Yii::app()->user->name . ' delete ticket #' . $model->id . ' named "' . $model->Name . '"';
        Yii::log($message, 'deleted', 'DELETED');
        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } //this is the only way to work with backbone
        else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function problemDelete($id)
    {
        $model = Problems::model()->findByPk($id);
        $files = explode(",", $model->image);
        foreach ($files as $file) {
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id . '/' . $file;
            if (is_file($documentPath)) {
                unlink($documentPath);
            }
        }
        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id)) {
            rmdir(Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id);
        }

        $num = $model->delete();
        $message = 'User ' . Yii::app()->user->name . ' delete problems #' . $model->id . '  ';
        Yii::log($message, 'deleted', 'DELETED');
        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } //this is the only way to work with backbone
        else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function userDelete($id)
    {
        $model = CUsers::model()->findByPk($id);
        $services = Service::model()->findByAttributes(['manager' => $model->Username]);
        $criteria = new CDbCriteria;
        $criteria->compare('users', $id, true);
        $groups = Groups::model()->findAll($criteria);
        $num = 0;
        if (!empty($model)) {
            if ($services or $id == 1) {
                throw new CHttpException(400,
                    'Вы не можете удалять пользователя, прикрепленного к сервису. Для удаления замените исполнителя сервисов!');
            } else {
                if (Yii::app()->params['zdmanager'] == $model->Username) {
                    throw new CHttpException(400,
                        'Вы не можете удалять исполнителя, назначенного по-умолчанию! Перейдите в настройки заявок и замените исполнителя по-умолчанию');
                } else {
                    foreach ($groups as $group) {
                        $new_users = [];
                        $old_users = explode(",", $group->users);
                        foreach ($old_users as $item) {
                            if ($item !== $id) {
                                $new_users[] = $item;
                            }
                        }
                        Groups::model()->updateByPk($group->id, ['users' => implode(",", $new_users)]);
                    }
                    $num = $model->delete();
                }
            }
        }
        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function companyDelete($id)
    {
        $model = Companies::model()->findByPk($id);
        $num = 0;
        if (!empty($model)) {
            $num = $model->delete();
        }

        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function assetsDelete($id)
    {
        $model = Asset::model()->findByPk($id);
        $num = 0;
        if ($model->uid !== null) {
            throw new CHttpException(400,
                'Вы не можете удалить актив привязанный к КЕ, исключите актив из состава КЕ для удаления!');
        } else {
            if (is_file(Yii::getPathOfAlias('webroot') . '/uploads/asset' . $model->id . '.png')) {
                unlink(Yii::getPathOfAlias('webroot') . '/uploads/asset' . $model->id . '.png');
            }
            $num = $model->delete();
        }

        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function cunitDelete($id)
    {
        $model = Cunits::model()->findByPk($id);
        $num = 0;
        $asset = Asset::model()->findAllByAttributes(['uid' => $id]);
        foreach ($asset as $item) {
            Asset::model()->updateByPk($item->id,
                ['uid' => null, 'cusers_name' => null, 'cusers_dept' => null, 'cusers_fullname' => null]);
        }
        if (is_file(Yii::getPathOfAlias('webroot') . '/uploads/unit' . $id . '.png')) {
            unlink(Yii::getPathOfAlias('webroot') . '/uploads/unit' . $id . '.png');
        }
        $num = $model->delete();
        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function commentDelete($id)
    {
        $model = Comments::model()->findByPk($id);
        $num = 0;

        $files = explode(",", $model->files);
        foreach ((array) $files as $file) {
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id . '/' . $file;
            if (is_file($documentPath)) {
                unlink($documentPath);
            }
        }

        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id)) {
            rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id);
        }

        $num = $model->delete();
        if ($num > 0) {
            $this->_sendResponse(200, $num);
        } else {
            $this->_sendResponse(500,
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                    $_GET['model'], $id));
        }
    }

    public function actionDelete()
    {
        switch ($_GET['model']) {

            case 'requests':
                if (Yii::app()->user->checkAccess('deleteRequest')) {
                    $this->requestDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'problems':
                if (Yii::app()->user->checkAccess('deleteProblem')) {
                    $this->problemDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'users':
                if (Yii::app()->user->checkAccess('deleteUser')) {
                    $this->userDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'companies':
                if (Yii::app()->user->checkAccess('deleteCompany')) {
                    $this->companyDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'assets':
                if (Yii::app()->user->checkAccess('deleteAsset')) {
                    $this->assetsDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'cunits':
                if (Yii::app()->user->checkAccess('deleteUnit')) {
                    $this->cunitDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            case 'comments':
                if (Yii::app()->user->checkAccess('deleteRequest')) {
                    $this->commentDelete($_GET['id']);
                } else {
                    $this->_sendResponse(403, 'Access is denied');
                }
                break;

            default:
                $this->_sendResponse(501,
                    sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                        $_GET['model']));
                Yii::app()->end();
        }
    }

    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
        } // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = [
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        ];
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    /**
     * Authorized
     */
    private function _checkAuth()
    {
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if (!(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']))) {
            // Error: Unauthorized
            $this->_sendResponse(401);
        }
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        $model = new LoginForm;
        $model->username = $username;
        $model->password = $password;
        // validate user input and redirect to the previous page if valid
        if ($model->validate() && $model->login()) {
            return;
        }

        $this->_sendResponse(401, 'Error: User Name is invalid');
    }

    /**
     * Записывает в историю заявки.
     * @param $action
     * @param $id
     * @param null $user
     */
    public function addHistory($action, $id, $user = null)
    {
        if (null === $user) {
            $cusers_id = CUsers::model()->findByPk(Yii::app()->user->id);
        } else {
            $cusers_id = CUsers::model()->findByAttributes(['Username' => $user]);
        }

        $history = new History();
        $history->datetime = date('d.m.Y H:i');
        $history->cusers_id = $cusers_id->fullname ? $cusers_id->fullname : 'system';
        $history->zid = $id;
        $history->action = $action;
        $history->save(false);
    }
}
