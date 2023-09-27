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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'upload', 'getfields'),
                'roles' => array('importSettings'),
                ),

            array('deny', // deny all users
                'users' => array('*'),
                ),
            );
    }
    public function actionUpload() {
        $model = new UploadForm;
        if(isset($_FILES['files']) AND !empty($_POST['model']) AND !empty($_FILES['files']['name'][0])){
            $model->files=CUploadedFile::getInstance($model,'files');
            $model2 = $_POST['model'];
            $filenams = 'media/csv/';
            foreach($_FILES['files']['name'] as $key=>$filename)
                move_uploaded_file($_FILES['files']['tmp_name'][$key],$filenams.$filename);
            $handle = fopen($filenams.$filename, 'rb');
            if ($handle) {
                while( ($line = fgetcsv($handle, 10000000, ";")) != FALSE){
                    if(!ctype_digit($_POST['model'])){
                        $model = new $_POST['model'];
                        $attrib = $model->importLabels();
                        $i = 0;
                        foreach ($attrib as $key => $value){
                            if($key !== 'id'){
                                $new = Charsets::charset_x_win($line[$i]);
                                $val = mb_convert_encoding($new, 'UTF-8', 'CP-1251');
                                $model->$key = $val;
                                $i = $i+1;
                            }
                        }
                        if (!$model->validate())
                            $errors = $model->getErrors();
                        if (isset($errors)){
                            foreach ($errors as $key => $value){
                                throw new Exception($key .': '.$value[0]);
                            }
                        }
                        $model->save();
                    }else{
                        $model3 = new Asset;
                        $attrib = $model3->importLabels();
                        $i = 0;
                        foreach ($attrib as $key => $value){
                            if($key !== 'id'){
                                $new = Charsets::charset_x_win($line[$i]);
                                $val = mb_convert_encoding($new, 'UTF-8', 'CP-1251');
                                $model3->$key = $val;
                                $i = $i+1;
                            }
                        }

                        if($model3->save(false)){
                            $y = count($attrib);
                            $attr_lablels = AssetAttribValue::model()->findAllByAttributes(array('asset_attrib_id'=>$_POST['model']));
                            $type = AssetAttrib::model()->findByPk($_POST['model']);
                            Asset::model()->updateByPk($model3->id,array('asset_attrib_id'=>$_POST['model'], 'asset_attrib_name'=>$type->name));
                            foreach ($attr_lablels as $value) {
                                $new = Charsets::charset_x_win($line[$y]);
                                $val = mb_convert_encoding($new, 'UTF-8', 'CP-1251');
                                $attr = new AssetValues;
                                $attr->asset_id =$model3->id;
                                $attr->asset_attrib_id=$_POST['model'];
                                $attr->asset_attrib_name=$value->name;
                                $attr->value=$val;
                                $attr->save(false);
                                $y=$y+1;
                            }
                        }
                    }

                }
            }
            fclose($handle);
            // перенаправляем на страницу, где выводим сообщение об
            // успешной загрузке
            unlink($filenams.$filename);
            if(ctype_digit($_POST['model'])){
                $url = 'asset';
            }else{
                $url = strtolower($_POST['model']);
            }
            Yii::app()->user->setFlash('info', Yii::t('main-ui', '<strong>Congratulations!</strong><br/> You have successfully imported the files into the database!.'));
            $this->redirect(array('/'.$url.'/index'));
        }else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

    }

public function actionIndex(){
    $this->render('index');
}


public function actionGetfields(){
    if (isset($_POST['model'])){
        $model = $_POST['model'];
        $values = array();
        $attrib = array();
        if(!empty($model) AND !ctype_digit($model)){
            $model2 = $model::model()->importLabels();
            foreach ($model2 as $key=>$value) {
                if($key !== 'id')
                    $values[] = $value;
            }
            echo '<code>'.implode(';',$values).'</code>';
        }elseif(ctype_digit($model)){
            $model2 = Asset::model()->importLabels();
            $attribs = AssetAttribValue::model()->findAllByAttributes(array('asset_attrib_id'=>$model));
            foreach ($model2 as $key=>$value) {
                if($key !== 'id')
                    $values[] = $value;
            }
            foreach ($attribs as $value) {
                $attrib[] = $value->name;
            }
            echo '<code>'.implode(';',$values).';'.implode(';',$attrib).'</code>';
        }
    }
}

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'news-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
