<?php

class ContractsController extends Controller
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
            array('allow',
                'actions' => array('view'),
                'roles' => array('viewContracts'),
            ),
            array('allow',
                'actions' => array('printform'),
                'roles' => array('printContracts'),
            ),
            array('allow',
                'actions' => array('index'),
                'roles' => array('listContracts'),
            ),
            array('allow',
                'actions' => array('create'),
                'roles' => array('createContracts'),
            ),
            array('allow',
                'actions' => array('update', 'deletefile'),
                'roles' => array('updateContracts'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('deleteContracts'),
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
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $expiration = strtotime($model->tildate);
        $now = strtotime(date('Y-m-d'));
        if($now > $expiration){
            Yii::app()->user->setFlash('danger', Yii::t('main-ui', '<strong>Warning!</strong> Contract has expired!'));
        }
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Contracts;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Contracts'])) {
            $model->attributes = $_POST['Contracts'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * @return string
     */
    public function actionPrintForm($id)
    {
        //calling the vendor class
        require __DIR__ . '/../vendors/phpword/autoload.php';

        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $model = $this->loadModel($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();
        //selects the template and generate text
        $content = UnitTemplates::model()->findByPk($_POST['groups_id']);
        $html = self::printGen($content->content, $model);
        $washer = new washtml(array('allow_remote' => true));
        $emailContent = $washer->wash($html);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $emailContent, false, false);


        // Saving the document as OOXML file...
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="'.$content->name.'.docx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    static function printGen($content, $model)
    {
        $company = Companies::model()->findByPk($model->company_id);
        $customer = Companies::model()->findByPk($model->customer_id);

        $s_print = Yii::t('message', "$content", array(
            '{id}' => $model->id,
            '{contract_number}' => $model->number ? $model->number : Yii::t('main-ui','Not set'),
            '{contract_date}' => $model->date_view ? $model->date_view : Yii::t('main-ui','Not set'),
            '{contract_tildate}' => $model->tildate_view ? $model->tildate_view : Yii::t('main-ui','Not set'),
            '{contract_type}' => $model->type ? $model->type : Yii::t('main-ui','Not set'),
            '{contract_cost}' => $model->cost ? $model->cost : Yii::t('main-ui','Not set'),
            //customer info
            '{contract_cost_write}' => $model->cost ? self::num2str($model->cost) : Yii::t('main-ui','Not set'),
            '{contract_customer}' => $model->customer_name ? $model->customer_name : Yii::t('main-ui','Not set'),
            '{contract_customer_director}' => $customer->director ? $customer->director: Yii::t('main-ui','Not set'),
            '{contract_customer_director_write}' => $customer->head_name_writeable ? $customer->head_name_writeable: Yii::t('main-ui','Not set'),
            '{contract_customer_director_position_write}' => $customer->head_position ? $customer->head_position: Yii::t('main-ui','Not set'),
            '{contract_customer_uraddress}' => $customer->uraddress ? $customer->uraddress: Yii::t('main-ui','Not set'),
            '{contract_customer_faddress}' => $customer->faddress ? $customer->faddress: Yii::t('main-ui','Not set'),
            '{contract_customer_inn}' => $customer->inn ? $customer->inn: Yii::t('main-ui','Not set'),
            '{contract_customer_kpp}' => $customer->kpp ? $customer->kpp: Yii::t('main-ui','Not set'),
            '{contract_customer_ogrn}' => $customer->ogrn ? $customer->ogrn: Yii::t('main-ui','Not set'),
            '{contract_customer_schet}' => $customer->schet ? $customer->schet: Yii::t('main-ui','Not set'),
            '{contract_customer_korschet}' => $customer->korschet ? $customer->korschet: Yii::t('main-ui','Not set'),
            '{contract_customer_bank}' => $customer->bank ? $customer->bank: Yii::t('main-ui','Not set'),
            '{contract_customer_bik}' => $customer->bik ? $customer->bik: Yii::t('main-ui','Not set'),
            '{contract_customer_contact}' => $customer->contact_name ? $customer->contact_name: Yii::t('main-ui','Not set'),
            '{contract_customer_contact_phone}' => $customer->phone ? $customer->phone: Yii::t('main-ui','Not set'),
            '{contract_customer_contact_email}' => $customer->email ? $customer->email: Yii::t('main-ui','Not set'),
            //contractor info
            '{contract_contractor}' => $model->company_name ? $model->company_name : Yii::t('main-ui','Not set'),
            '{contract_contractor_director}' => $company->director ? $company->director: Yii::t('main-ui','Not set'),
            '{contract_contractor_director_write}' => $company->head_name_writable ? $company->head_name_writable: Yii::t('main-ui','Not set'),
            '{contract_contractor_director_position_write}' => $company->head_position ? $company->head_position: Yii::t('main-ui','Not set'),
            '{contract_contractor_uraddress}' => $company->uraddress ? $company->uraddress: Yii::t('main-ui','Not set'),
            '{contract_contractor_faddress}' => $company->faddress ? $company->faddress: Yii::t('main-ui','Not set'),
            '{contract_contractor_inn}' => $company->inn ? $company->inn: Yii::t('main-ui','Not set'),
            '{contract_contractor_kpp}' => $company->kpp ? $company->kpp: Yii::t('main-ui','Not set'),
            '{contract_contractor_ogrn}' => $company->ogrn ? $company->ogrn: Yii::t('main-ui','Not set'),
            '{contract_contractor_schet}' => $company->schet ? $company->schet: Yii::t('main-ui','Not set'),
            '{contract_contractor_korschet}' => $company->korschet ? $company->korschet: Yii::t('main-ui','Not set'),
            '{contract_contractor_bank}' => $company->bank ? $company->bank: Yii::t('main-ui','Not set'),
            '{contract_contractor_bik}' => $company->bik ? $company->bik: Yii::t('main-ui','Not set'),
            '{contract_contractor_contact}' => $company->contact_name ? $company->contact_name: Yii::t('main-ui','Not set'),
            '{contract_contractor_contact_phone}' => $company->phone ? $company->phone: Yii::t('main-ui','Not set'),
            '{contract_contractor_contact_email}' => $company->email ? $company->email: Yii::t('main-ui','Not set'),
        ));
        return $s_print;
    }

    static function num2str($num) {
        $nul='ноль';
        $ten=array(
            array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
            array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
        );
        $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
        $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
        $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
        $unit=array( // Units
            array('копейка' ,'копейки' ,'копеек',	 1),
            array('рубль'   ,'рубля'   ,'рублей'    ,0),
            array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
            array('миллион' ,'миллиона','миллионов' ,0),
            array('миллиард','милиарда','миллиардов',0),
        );
        //
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub)>0) {
            foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1; // unit key
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
                else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            } //foreach
        }
        else $out[] = $nul;
        $out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
        $out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    /**
     * Склоняем словоформу
     */
    static function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) return $f5;
        $n = $n % 10;
        if ($n>1 && $n<5) return $f2;
        if ($n==1) return $f1;
        return $f5;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

        if (isset($_POST['Contracts'])) {
            $model->attributes = $_POST['Contracts'];
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
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
        Contracts::model()->updateByPk($id, array('image' => $value));

    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['ContractsPageCount'] = $_GET['pageCount'];
        }
        $model = new Contracts('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Contracts']))
            $model->attributes = $_GET['Contracts'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Contracts::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'contracts-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
