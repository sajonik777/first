<?php

class CunitsController extends Controller
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
            array('allow',
                'actions' => array('view'),
                'roles' => array('viewUnit'),
            ),
            array('allow',
                'actions' => array('index'),
                'roles' => array('listUnit'),
            ),
            array('allow',
                'actions' => array('create', 'add_asset', 'selectdepart'),
                'roles' => array('createUnit'),
            ),
            array('allow',
                'actions' => array('update', 'add_asset', 'selectdepart', 'delete_asset', 'deletefile'),
                'roles' => array('updateUnit'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteUnit'),
            ),
            array('allow',
                'actions' => array('batchdelete'),
                'roles' => array('batchDeleteUnit'),
            ),
            array('allow',
                'actions' => array('print', 'printform'),
                'roles' => array('printUnit'),
            ),
            array('allow',
                'actions' => array('export'),
                'roles' => array('exportUnit'),
            ),

            array('deny',// deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'import' => array('class' => 'ext.import.components.ImportModels', 'model' => 'Cunits'),
            'template' => array('class' => 'ext.import.components.ImportTemplate', 'model' => 'Cunits')
        );
    }

    public
    function behaviors()
    {
        return array(
            'eexcelview' => array(
                'class' => 'ext.eexcelview.EExcelBehavior',
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $ass = Asset::model()->findAllByAttributes(array('uid' => $id));
        $history = Uhistory::model()->findAllByAttributes(array('uid' => $id));
        $criteria = new CDbCriteria;
        $criteria->compare('assets', $model->id, true);
        $problems = Problems::model()->findAll($criteria);
        
        $requestsCriteria = new CDbCriteria;
        $requestsCriteria->condition = 'cunits LIKE :name AND Status NOT LIKE :st1 AND Status NOT LIKE :st2';
        $requestsCriteria->params = array(
            'name'=>$model->name,
            'st1'=>'Завершена',
            'st2'=>'Архив',
        );
        $requests = Request::model()->findAll($requestsCriteria);

        $this->render('view', array(
            'model' => $model,
            'assets' => $ass,
            'problems' => $problems,
            'requests' => $requests,
            'history' => $history,
        ));
    }

    public function loadModel($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (!Yii::app()->user->checkAccess('viewMyselfUnit')) {
            $model = Cunits::model()->findByPk($id);
        } else {
            $user = CUsers::model()->findByAttributes(array('Username'=>Yii::app()->user->name));
            $model = Cunits::model()->findByAttributes(array('id' => $id, 'company' => $user->company));
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public
    function actionSelectDepart()
    {
        if ($_POST['Cunits']['dept']) {
            $data = CUsers::model()->findAllByAttributes(
                array(
                    'department' => $_POST['Cunits']['dept'],
                    'active' => 1
                ));
        } else {
            $data = CUsers::model()->findAllByAttributes(array('active' => 1));
        }


        $data = CHtml::listData($data, 'Username', 'fullname');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionExport()
    {
        $connection = Yii::app()->db;
        $columns_query = 'SELECT * FROM `tbl_columns` `t` WHERE `t`.`id`="cunits-grid_'.Yii::app()->user->id.'"';
        $columns = $connection->createCommand($columns_query)->queryAll();
        $columns_array = explode('||',$columns[0]['data']);
        if (!empty($columns)){
            foreach ($columns_array as $item) {
                if ($item !== 'Действия'){
                    if ($item !== 'slabel'){
                        $new_arr[]['name'] = $item;
                    }else{
                        $new_arr[]['name'] = 'status';
                    }
                }
            }
            $this->toExcel($_SESSION['cunit_records'],
                $columns = $new_arr,

                Yii::t('main-ui','Units'),
                array(
                    'creator' => 'Univef',
                    'title' => Yii::t('main-ui','Units'),
                ),
                'Excel2007'
            );
        }else{
            throw new CHttpException(500, Yii::t('main-ui', 'Select columns settings to export data.'));
        }
    }

    public function actionPrint($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Univef");
        $pdf->SetTitle($model->name);
        $pdf->SetSubject($model->id);
        $pdf->SetKeywords($model->name);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('freemono', '', 10);
        $model = $this->loadModel($id);
        $assets = explode(",", $model->assets);
        $header = '<h4><img src="' . Yii::app()->params->smallLogo . '">&nbsp;&nbsp;' . Yii::app()->params->brandName . '</h4><br/><hr>';
        $pdf->writeHTML($header, true, false, false, false, '');
        $qr = '<img src="'.Yii::app()->getBasePath().'/../uploads/unit'.$id.'.png"><br/><hr>';
        $pdf->writeHTML($qr, true, false, false, false, '');
        $tbl = '<h1 align="center">Конфигурационная единица №' . $model->id . ' ' . $model->name . '<h1>';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $type = '<p><b>Тип КЕ:</b> ' . $model->type . '</p><br/>';
        $pdf->writeHTML($type, true, false, false, false, '');
        $status = '<p><b>Статус:</b> ' . $model->status . '</p><br/>';
        $pdf->writeHTML($status, true, false, false, false, '');
        $inventory = '<p><b>Инвентарный №:</b> ' . $model->inventory . '</p><br/>';
        if ($model->inventory) {
            $pdf->writeHTML($inventory, true, false, false, false, '');
        }
        $cost = '<p><b>Стоимость:</b> ' . $model->cost . ' рублей</p><br/>';
        if ($model->cost) {
            $pdf->writeHTML($cost, true, false, false, false, '');
        }
        $location = '<p><b>Пользователь:</b> ' . $model->fullname . '</p><br/>';
        $pdf->writeHTML($location, true, false, false, false, '');
        $sdate = '<p><b>Дата ввода в эксплуатацию:</b> ' . $model->datein . '</p><br/>';
        $pdf->writeHTML($sdate, true, false, false, false, '');
        $edate = '<p><b>Дата вывода из эксплуатации:</b> ' . $model->dateout . '</p><br/>';
        if ($model->dateout) {
            $pdf->writeHTML($edate, true, false, false, false, '');
        }
        if ($assets['0'] !== '') {
            foreach ($assets as $item) {
                $value = Asset::model()->findByPk($item);
                $data = AssetValues::model()->findAllByAttributes(array('asset_id' => $value->id));
                $hdr = '<hr><h3><i color="#666">' . $value->asset_attrib_name . ' ' . $value->name . ':</i></h3><br/><p>Инвентарный номер: ' . $value->inventory . '</p><br/><p>Стоимость: ' . $value->cost . ' руб.</p><br/>';
                $pdf->writeHTML($hdr, true, false, false, false, '');
                foreach ($data as $itm) {
                    $body = '<table style="border: 1px dashed #efefef">
                <tr><th><b>' . $itm->asset_attrib_name . '</b></th>
                <td>' . $itm->value . '</td>
                </tr>
                </table>';
                    $pdf->writeHTML($body, true, false, false, false, '');
                }
            }
        }
        $pdf->Output($model->name, 'I');
    }

    public function actionPrintForm($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->layout = 'printlayout';
        $model = $this->loadModel($id);
        $content = UnitTemplates::model()->findByPk($_POST['groups_id']);
        $print = $this->printGen($content->content, $model);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', $content->format, 'mm', $content->page_width ? array($content->page_width, $content->page_height) : $content->page_format, true, 'UTF-8');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Univef");
        $pdf->SetTitle($model->name);
        $pdf->SetSubject($model->id);
        $pdf->SetKeywords($model->name);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('freemono', '', 10);
        $pdf->writeHTML($print, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output($model->name, 'I');

    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = new Cunits;

        if (isset($_POST['Cunits'])) {
            $model->attributes = $_POST['Cunits'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    static function printGen($content, $model)
    {
        $assets = explode(",", $model->assets);
        $asset_form = array();
        if ($assets['0'] !== '') {
            foreach ($assets as $item) {
                $value = Asset::model()->findByPk($item);
                $data = AssetValues::model()->findAllByAttributes(array('asset_id' => $value->id));
                $asset_form[] = '<hr><h4><i color="#666">' . $value->asset_attrib_name . ' ' . $value->name . ':</i></h4><br><b>Инвентарный номер:</b> ' . $value->inventory . '<br><b>Стоимость:</b> ' . $value->cost . ' руб.<br>';
                foreach ($data as $itm) {
                    $asset_form[] .= '<b>' . $itm->asset_attrib_name . ':</b> '. $itm->value .'<br>';
                }
            }
        }
        $asset_text = NULL;
        foreach ($asset_form as $item){
            $asset_text .= $item;
        }
        if(isset($model->user)){
          $user = CUsers::model()->findByAttributes(array('Username' => $model->user));
        }
        $s_print = Yii::t('message', "$content", array(
            '{id}' => $model->id,
            '{name}' => $model->name ? $model->name : Yii::t('main-ui','Not set'),
            '{status}' => $model->status ? $model->status : Yii::t('main-ui','Not set'),
            '{type}' => $model->type ? $model->type : Yii::t('main-ui','Not set'),
            '{username}' => $model->fullname ? $model->fullname : Yii::t('main-ui','Not set'),
            '{userphone}' => $user->Phone ? $user->Phone : Yii::t('main-ui','Not set'),
            '{useremail}' => $user->Email ? $user->Email : Yii::t('main-ui','Not set'),
            '{userroom}' => $user->room ? $user->room : Yii::t('main-ui','Not set'),
            '{usermanager}' => $user->umanager ? $user->umanager : Yii::t('main-ui','Not set'),
            '{userposition}' => $user->position ? $user->position : Yii::t('main-ui','Not set'),
            '{department}' => $model->dept ? $model->dept : Yii::t('main-ui','Not set'),
            '{startexpdate}' => $model->datein ? $model->datein : Yii::t('main-ui','Not set'),
            '{endexpdate}' => $model->dateout ? $model->dateout : Yii::t('main-ui','Not set'),
            '{inventory}' => $model->inventory ? $model->inventory : Yii::t('main-ui','Not set'),
            '{company}' => $model->company ? $model->company : Yii::t('main-ui','Not set'),
            '{location}' => $model->location ? $model->location : Yii::t('main-ui','Not set'),
            '{description}' => $model->description ? $model->description : Yii::t('main-ui','Not set'),
            '{cost}' => $model->cost ? $model->cost : Yii::t('main-ui','Not set'),
            '{date}' => date('d.m.Y'),
            '{QRCODE}' => '<img src="' . Yii::app()->params['homeUrl'] . '/uploads/unit' . $model->id . '.png" />',
            '{assets}' => $asset_text,
        ));
        return $s_print;
    }

    public function actionUpdate($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $ass = Asset::model()->findAllByAttributes(array('uid'=>$id));
        if (isset($_POST['Cunits'])) {
            $connection = Yii::app()->db;
            $oldunitname = $model->name;
            $model->attributes = $_POST['Cunits'];
            if ($model->save()){
              //обновляем в заявках имя КЕ
              $tiketq= 'SELECT * FROM `request` WHERE `cunits` LIKE \'%'.$oldunitname.'%\'';
              $tickets = $connection->createCommand($tiketq)->queryAll();
              foreach ($tickets as $ticket) {
                if (isset($ticket['cunits'])){
                  $watchers = explode(',', $ticket['cunits']);
                  $newwatcher = array();
                  foreach ($watchers as $watcher) {
                    if ($watcher == $oldunitname){
                      $newwatcher[] = $_POST['Cunits']['name'];
                    } else {
                      $newwatcher[] = $watcher;
                    }
                  }
                  Request::model()->updateByPk($ticket['id'], array('cunits' => implode(',', $newwatcher)));
                }
              }

              //обновляем в проблемах имя КЕ
              $problemq= 'SELECT * FROM `problems` WHERE `assets_names` LIKE \'%'.$oldunitname.'%\'';
              $problems = $connection->createCommand($problemq)->queryAll();
              foreach ($problems as $problem) {
                if (isset($problem['assets_names'])){
                  $watchers = explode(',', $problem['assets_names']);
                  $newwatcher = array();
                  foreach ($watchers as $watcher) {
                    if ($watcher == $oldunitname){
                      $newwatcher[] = $_POST['Cunits']['name'];
                    } else {
                      $newwatcher[] = $watcher;
                    }
                  }
                  Problems::model()->updateByPk($problem['id'], array('assets_names' => implode(',', $newwatcher)));
                }
              }

                  $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'assets' => $ass,
        ));
    }

    public function actionAdd_asset($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);

        if (isset($_GET['checked'])) {
            $model = $this->loadModel($id);
            $asset = Cunits::model()->findByPk($id);

            $oldassets = explode(',', $asset->assets);

            $newassets = $_GET['checked'];

            $sum = 0;

            foreach ($newassets as $new) {
                $cid = Asset::model()->findByPk($new);
                Asset::model()->updateByPk($cid->id, array('uid' => $model->id/*, 'cusers_name' => $model->user, 'cusers_fullname' => $model->fullname, 'cusers_dept' => $model->dept*/));
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $model->id;
                $history->action = 'Добавлен актив: <b>' . $cid->asset_attrib_name . ' ' . $cid->name . '</b>. Инвентарный номер: <b>' . $cid->inventory . '</b>';
                $history->save(false);
            }


                    $valuea = array_merge($newassets, $oldassets);
                    $values = array_diff($valuea, array(''));
                    foreach ($values as $val) {
                        $cid = Asset::model()->findByPk($val);
                        if ($cid) {
                            $sum += $cid['cost'];
                        }
                    }
                    $value = implode(",", $values);
                    Cunits::model()->updateByPk($id, array('assets' => $value, 'cost' => $sum));

            $this->redirect(array('update', 'id' => $id));


        }

        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            if (isset ($_POST['assets'])) {
                $asset = Cunits::model()->findByPk($id);
                $oldassets = explode(',', $asset->assets);
                $newassets = $_POST['assets'];
                $sum = 0;
                foreach ($newassets as $item) {
                    $cid = Asset::model()->findByPk($item);
                    Asset::model()->updateByPk($cid->id, array('uid' => $model->id, 'cusers_name' => $model->user, 'cusers_fullname' => $model->fullname, 'cusers_dept' => $model->dept));
                    $history = new Uhistory();
                    $history->date = date("d.m.Y H:i");
                    $history->user = Yii::app()->user->name;
                    $history->uid = $model->id;
                    $history->action = 'Добавлен актив: <b>' . ' ' . $cid->name . '</b>. Инвентарный номер: <b>' . $cid->inventory . '</b>';
                    $history->save(false);
                }
                $valuea = array_merge($newassets, $oldassets);
                $values = array_diff($valuea, array(''));
                foreach ($values as $val) {
                    $cid = Asset::model()->findByPk($val);
                    if ($cid) {
                        $sum += $cid['cost'];
                    }
                }
                $value = implode(",", $values);
                Cunits::model()->updateByPk($id, array('assets' => $value, 'cost' => $sum));
                $this->redirect(array('update', 'id' => $id));
            }
        }
    }

    public function actionDelete_asset($id, $mid)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
            $mid = $_GET['mid'];
            }
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            $model = $this->loadModel($mid);
            $newvalues[] = '';
            $sum = 0;
            Asset::model()->updateByPk($id, array('uid' => NULL, 'cusers_name' => NULL, 'cusers_dept' => NULL, 'cusers_fullname' => NULL));
            $aid = Asset::model()->findByPk($id);
            $history = new Uhistory();
            $history->date = date("d.m.Y H:i");
            $history->user = Yii::app()->user->name;
            $history->uid = $model->id;
            $history->action = 'Удален актив: <b>' . ' ' . $aid->name . '</b>. Инвентарный номер: <b>' . $aid->inventory . '</b>';
            $history->save(false);
            $unit_assets = Asset::model()->findAllByAttributes(array('uid' => $mid));
            foreach ($unit_assets as $item) {
                $newvalues[] = $item->id;
                $sum += $item['cost'];
            }
            $values = array_diff($newvalues, array(''));
            $value = implode(",", $values);
            Cunits::model()->updateByPk($mid, array('assets' => $value, 'cost' => $sum));
            //$this->redirect(array('update','id'=>$mid));
        }
    }

    public function actionDelete($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
            }
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            $asset = Asset::model()->findAllByAttributes(array('uid' => $id));
            foreach ($asset as $item) {
                Asset::model()->updateByPk($item->id, array('uid' => NULL, 'cusers_name' => NULL, 'cusers_dept' => NULL, 'cusers_fullname' => NULL));
            }
            if (is_file(Yii::getPathOfAlias('webroot') . '/uploads/unit' . $id.'.png')) {
                unlink(Yii::getPathOfAlias('webroot') . '/uploads/unit' . $id.'.png');
            }
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
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
        Cunits::model()->updateByPk($id, array('image' => $value));

    }

    public function actionBatchDelete()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $checkedIDs = $_GET['checked'];
            foreach ($checkedIDs as $id) {
                $asset = Asset::model()->findAllByAttributes(array('uid' => $id));
                foreach ($asset as $item) {
                    Asset::model()->updateByPk($item->id, array('uid' => NULL, 'cusers_name' => NULL, 'cusers_dept' => NULL, 'cusers_fullname' => NULL));
                }
                if (is_file(Yii::getPathOfAlias('webroot') . '/uploads/unit' . $id.'.png')) {
                    unlink(Yii::getPathOfAlias('webroot') . '/uploads/unit' . $id.'.png');
                }
                $this->loadModel($id)->delete();
            }
        }
    }

    public function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['cunitsPageCount'] = $_GET['pageCount'];
        }
        $model = new Cunits('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Cunits']))
            $model->attributes = $_GET['Cunits'];
        //Yii::app()->user->setFlash('info', Yii::t('main-ui', '<strong>Attention!</strong> Configuration Units are the main objects to creare problems and tickets. To create unit, assign any assets.'));
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cunits-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
