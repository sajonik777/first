<?php

class AssetController extends Controller
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
                'actions' => array('view'),
                'roles' => array('viewAsset'),
            ),
            array('allow',
                'actions' => array('index'),
                'roles' => array('listAsset'),
            ),
            array('allow',
                'actions' => array('grid', 'agrid'),
                'roles' => array('listAsset'),
            ),
            array('allow',
                'actions' => array('create', 'updateajax'),
                'roles' => array('createAsset'),
            ),
            array('allow',
                'actions' => array('update', 'deletefile', 'updateajax', 'updateajax2', 'updWarranty'),
                'roles' => array('updateAsset'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteAsset'),
            ),
            array('allow',
                'actions' => array('batchdelete'),
                'roles' => array('batchDeleteAsset'),
            ),
            array('allow',
                'actions' => array('print', 'printform'),
                'roles' => array('printAsset'),
            ),
            array('allow',
                'actions' => array('export'),
                'roles' => array('exportAsset'),
            ),

            array('deny',// deny all users
                'users' => array('*'),
            ),
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
        $data = AssetValues::model()->findAll('asset_id=:asset_id', array(':asset_id' => $model->id));
        $header = '<h4><img src="' . Yii::app()->params->smallLogo . '">&nbsp;&nbsp;' . Yii::app()->params->brandName . '</h4><br/><hr>';
        $pdf->writeHTML($header, true, false, false, false, '');
        $qr = '<img src="'.Yii::app()->getBasePath().'/../uploads/asset'.$id.'.png"><br/><hr>';
        $pdf->writeHTML($qr, true, false, false, false, '');
        $tbl = '<h1>Карточка актива №' . $model->id . ' ' . $model->name . '<h1>';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $type = '<p><b>Тип актива:</b> ' . $model->asset_attrib_name . '</p><br/>';
        $pdf->writeHTML($type, true, false, false, false, '');
        $status = '<p><b>Статус:</b> ' . $model->status . '</p><br/>';
        $pdf->writeHTML($status, true, false, false, false, '');
        $inventory = '<p><b>Инвентарный №:</b> ' . $model->inventory . '</p><br/>';
        if ($model->inventory) {
            $pdf->writeHTML($inventory, true, false, false, false, '');
        }
        $cost = '<p><b>Стоимость:</b> ' . $model->cost . ' рублей</p><br/>';
        $pdf->writeHTML($cost, true, false, false, false, '');
        if ($model->location) {
            $location = '<p><b>Местоположение:</b> ' . $model->location . '</p><br/>';
        }
        $pdf->writeHTML($location, true, false, false, false, '');
        foreach ($data as $data_item) {
            $body = '<p><b>' . $data_item->asset_attrib_name . ':</b> ' . $data_item->value . '</p><br/>';
            if ($data_item->value) {
                $pdf->writeHTML($body, true, false, false, false, '');
            }
        }

        //$pdf->writeHTML($tbl, true, false, false, false, '');

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

    static function printGen($content, $model)
    {
        $asset_form = NULL;
        $data = AssetValues::model()->findAllByAttributes(array('asset_id' => $model->id));
        foreach ($data as $itm) {
            $asset_form .= '<p><b>' . $itm->asset_attrib_name . ':</b> '. $itm->value .'</p>';
        }
        if(isset($model->cusers_name)){
          $user = CUsers::model()->findByAttributes(array('Username' => $model->cusers_name));
        }
        $s_print = Yii::t('message', "$content", array(
            '{id}' => $model->id,
            '{name}' => $model->name ? $model->name : Yii::t('main-ui','Not set'),
            '{status}' => $model->status ? $model->status : Yii::t('main-ui','Not set'),
            '{type}' => $model->asset_attrib_name ? $model->asset_attrib_name : Yii::t('main-ui','Not set'),
            '{username}' => $model->cusers_fullname ? $model->cusers_fullname : Yii::t('main-ui','Not set'),
            '{userphone}' => $user->Phone ? $user->Phone : Yii::t('main-ui','Not set'),
            '{useremail}' => $user->Email ? $user->Email : Yii::t('main-ui','Not set'),
            '{userroom}' => $user->room ? $user->room : Yii::t('main-ui','Not set'),
            '{usermanager}' => $user->umanager ? $user->umanager : Yii::t('main-ui','Not set'),
            '{userposition}' => $user->position ? $user->position : Yii::t('main-ui','Not set'),
            '{department}' => $model->cusers_dept ? $model->cusers_dept : Yii::t('main-ui','Not set'),
            '{inventory}' => $model->inventory ? $model->inventory : Yii::t('main-ui','Not set'),
            '{location}' => $model->location ? $model->location : Yii::t('main-ui','Not set'),
            '{cost}' => $model->cost ? $model->cost : Yii::t('main-ui','Not set'),
            '{description}' => $model->description ? $model->description : Yii::t('main-ui','Not set'),
            '{date}' => date('d.m.Y'),
            '{QRCODE}' => '<img src="' . Yii::app()->params['homeUrl'] . '/uploads/asset' . $model->id . '.png" />',
            '{assets}' => $asset_form,
        ));
        return $s_print;
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
        $model = Asset::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public
    function actionView($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $items = array();
        $unit = Cunits::model()->findByAttributes(array('id' => $model->uid));
        $criteria = new CDbCriteria();
        $criteria->order = 'id DESC';
        $history = Ahistory::model()->findAllByAttributes(array('aid' => $id), $criteria);
        $data = AssetValues::model()->findAll('asset_id=:asset_id', array(':asset_id' => $model->id));
        foreach ($data as $data_item) {
            $items[] = array(
                'label' => $data_item->asset_attrib_name,
                'value' => $data_item->value,
            );
        }
        $this->render('view', array(
            'model' => $model,
            'unit' => $unit,
            'data' => $items,
            'history' => $history,
        ));
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
        $model = new Asset;


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Asset'])) {
            $model->attributes = $_POST['Asset'];
            $data = AssetAttribValue::model()->findAllByAttributes(array('asset_attrib_id' => $_POST['Asset']['asset_attrib_id']));
            $i = 0;
            $data2 = AssetAttrib::model()->findAllByAttributes(array('id' => $_POST['Asset']['asset_attrib_id']));
            foreach ($data2 as $data_item) {
                $model->asset_attrib_name = $data_item->name;
            }

            if ($model->save()){
              $history = new Ahistory();
              $history->date = date("d.m.Y H:i");
              $history->user = Yii::app()->user->name;
              $history->aid = $model->id;
              $history->action = Yii::t('main-ui', 'Added asset').' "'.$model->asset_attrib_name.'": <b> ' . $model->name . '</b>. '.Yii::t('main-ui','Inventory #').': <b>' . $model->inventory . '</b>. '.Yii::t('main-ui','Status').': <b>' . $model->slabel . '</b> '.Yii::t('main-ui','Cost').': <b>' . $model->cost . ' рублей</b>';
              $history->save(false);

              foreach ($data as $value) {
                  $i = $i + 1;
                  $model_s = new AssetValues;
                  $model_s->asset_id = $model->id;
                  $model_s->asset_attrib_id = $value->asset_attrib_id;
                  $model_s->asset_attrib_name = $value->name;
                  $model_s->value = $_POST['Asset'][$i . 'name'];
                  $model_s->save(false);
              }
              $this->redirect(array('index'));
            }
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
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $sum = 0;
        $model = $this->loadModel($id);
        $data = AssetAttribValue::model()->findAllByAttributes(array('asset_attrib_id' => $model->asset_attrib_id));
        $model_s = AssetValues::model()->findAllByAttributes(array('asset_id' => $model->id));


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Asset'])) {
            $model->attributes = $_POST['Asset'];
            if ($model->save()){
              foreach ($model_s as $value) {
                  if ($value->value !== $_POST['Asset'][$value->id]){
                      if (isset($value->value) AND $value->value !== ''){
                          $val1 = $value->value;
                      }else{
                          $val1 = Yii::t('main-ui', 'empty');
                      }
                      if ($_POST['Asset'][$value->id] !== ''){
                          $val2 = $_POST['Asset'][$value->id];
                      }else{
                          $val2 = Yii::t('main-ui', 'empty');
                      }
                      $history = new Ahistory();
                      $history->date = date("d.m.Y H:i");
                      $history->user = Yii::app()->user->name;
                      $history->aid = $id;
                      $history->action = Yii::t('main-ui', 'Changed value').' "'.$value->asset_attrib_name.'" '.Yii::t('main-ui', 'from').': <b> ' . $val1 . '</b> '.Yii::t('main-ui', 'to').': <b>' . $val2 . '</b>.';
                      $history->save(false);
                      AssetValues::model()->updateByPk($value->id, array('value' => $_POST['Asset'][$value->id]));
                  }
              }
          $criteria = new CDbCriteria;
          $criteria->compare('assets', $model->id, true);
          $unit = Cunits::model()->findByAttributes(array(), $criteria);
          $assets = explode(",", $unit->assets);
          foreach ($assets as $item) {
              $cid = Asset::model()->findByPk($item);
              if ($cid) {
                  $sum += $cid['cost'];
              }
          }
          Cunits::model()->updateByPk($unit->id, array('cost' => $sum));
          $this->redirect(array('index'));
            }
        }

        $model->warranty_start = date_format(new DateTime($model->warranty_start),'d.m.Y');
        $model->warranty_end = date_format(new DateTime($model->warranty_end),'d.m.Y');

        $this->render('update', array(
            'model' => $model,
            'model_s' => $model_s,
            'data' => $data,
        ));
    }

    public function actionGrid()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['assetPageCount'] = $_GET['pageCount'];
        }

        $model = new Asset('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Asset']))
            $model->attributes = $_GET['Asset'];
        //$model->company = new CDbExpression('NULL');


        //Yii::app()->user->setFlash('info', Yii::t('main-ui', '<strong>Attention!</strong> Assets is part of the units. You can change the additional fields in the "Types of assets".'));
        $this->renderPartial('_grid', array(
            'model' => $model,
        ));
    }

    public function actionAgrid()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['assetPageCount'] = $_GET['pageCount'];
        }

        $model = new Asset('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Asset']))
            $model->attributes = $_GET['Asset'];
        //$model->company = new CDbExpression('NULL');


        //Yii::app()->user->setFlash('info', Yii::t('main-ui', '<strong>Attention!</strong> Assets is part of the units. You can change the additional fields in the "Types of assets".'));
        $this->renderPartial('_agrid', array(
            'model' => $model,
        ));
    }


    public
    function actionUpdateAjax()
    {
        $items = AssetAttribValue::model()->findAllByAttributes(array('asset_attrib_id' => $_POST['Asset']['asset_attrib_id']));
        $this->renderPartial('_ajaxform', array('item' => $items));
    }

    public
    function actionUpdWarranty($id)
    {
        Asset::model()->updateByPk($id,[$_POST['name'] => date_format(new DateTime($_POST['value']),'Y-m-d'), 'lastactivity' => date("Y-m-d H:i:s")]);
    }


    public
    function actionUpdateAjax2()
    {
      if (isset($_POST['id'])) {
          $id = (int)$_POST['id'];
      }
        $items = AssetAttribValue::model()->findAllByAttributes(array('asset_attrib_id' => $_POST['id']));
        $pst = json_decode($_POST['items'], JSON_FORCE_OBJECT);
        $this->renderPartial('_ajaxform', array('item' => $items, 'pst' => $pst));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */

    public function actionExport()
    {
        $connection = Yii::app()->db;
        $columns_query = 'SELECT * FROM `tbl_columns` `t` WHERE `t`.`id`="assets-grid_'.Yii::app()->user->id.'"';
        $columns = $connection->createCommand($columns_query)->queryAll();
        $columns_array = explode('||',$columns[0]['data']);
        if (!empty($columns)) {
            foreach ($columns_array as $item) {
                if ($item !== 'Действия') {
                    if ($item !== 'slabel') {
                        $new_arr[]['name'] = $item;
                    } else {
                        $new_arr[]['name'] = 'status';
                    }
                }
            }
            $this->toExcel($_SESSION['asset_records'],
                $columns = $new_arr,

                Yii::t('main-ui', 'Assets'),
                array(
                    'creator' => 'Univef',
                    'title' => Yii::t('main-ui', 'Assets'),
                ),
                'Excel2007'
            );
        }else{
            throw new CHttpException(500, Yii::t('main-ui', 'Select columns settings to export data.'));
        }

    }

    /**
     * Lists all models.
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
        if (Yii::app()->request->isPostRequest OR Yii::app()->request->getIsAjaxRequest()) {
            // we only allow deletion via POST request
            if ($model->uid !== NULL) {
                throw new CHttpException(400, 'Вы не можете удалить актив привязанный к КЕ, исключите актив из состава КЕ для удаления!');
            } else {
                if (is_file(Yii::getPathOfAlias('webroot') . '/uploads/asset' . $model->id.'.png')) {
                    unlink(Yii::getPathOfAlias('webroot') . '/uploads/asset' . $model->id.'.png');
                }
                $this->loadModel($id)->delete();
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionBatchDelete()
    {
      if (Yii::app()->request->getIsAjaxRequest()) {
        $checkedIDs = $_GET['checked'];
        foreach ($checkedIDs as $id) {
          $model = $this->loadModel($id);
          if ($model->uid !== NULL) {
            throw new CHttpException(400, 'Вы не можете удалить актив привязанный к КЕ, исключите актив из состава КЕ для удаления!');
          } else {
            if (is_file(Yii::getPathOfAlias('webroot') . '/uploads/asset' . $model->id.'.png')) {
              unlink(Yii::getPathOfAlias('webroot') . '/uploads/asset' . $model->id.'.png');
            }
            $this->loadModel($id)->delete();
          }
        }
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
        Asset::model()->updateByPk($id, array('image' => $value));

    }

    /**
     * Manages all models.
     */
    public
    function actionIndex()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['assetPageCount'] = $_GET['pageCount'];
        }

        $model = new Asset('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Asset']))
            $model->attributes = $_GET['Asset'];
        //Yii::app()->user->setFlash('info', Yii::t('main-ui', '<strong>Attention!</strong> Assets is part of the units. You can change the additional fields in the "Types of assets".'));
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'asset-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
