<?php

class DefaultController extends Controller
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
                'actions' => array('admin', 'delete', 'clean', 'index', 'view', 'create', 'upload', 'import', 'download', 'restore'),
                'roles' => array('backupSettings'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    public $tables = array();
    public $fp;
    public $file_name;
    public $_path = null;
    public $back_temp_file = 'backup_';

    protected function getPath()
    {
        if (isset ($this->module->path)) $this->_path = $this->module->path;
        else $this->_path = Yii::app()->basePath . '/../_backup/';

        if (!file_exists($this->_path)) {
            mkdir($this->_path);
            chmod($this->_path, '777');
        }
        return $this->_path;
    }

    public function StartBackup($addcheck = true)
    {
        $this->file_name = $this->path . $this->back_temp_file . date('d.m.Y_H.i') . '.sql';
        exec('mysqldump -u' . Yii::app()->db->username . ' -p' . Yii::app()->db->password . ' univefservicedesk > ' . $this->file_name);
        return true;
    }

    public function EndBackup($addcheck = true)
    {
        $zip_file_path = $this->path . $this->back_temp_file . date('d.m.Y_H.i') . '.sql';
        $zip_file_name = 'sql_dump.sql';
        $zip = new ZipArchive(); //Создаём объект для работы с ZIP-архивами
        $zip->open($this->path . $this->back_temp_file . date('d.m.Y_H.i') . '.zip', ZIPARCHIVE::CREATE); //Открываем (создаём) архив
        $zip->addFile($zip_file_path, $zip_file_name);
        $directory = realpath(Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
        $options = array('add_path' => '/', 'remove_path' => $directory);
        $zip->addPattern('/\.(?:inc)$/', $directory, $options);
        //$zip->addFile(Yii::app()->basePath. DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR .'dbconfig.php', 'dbconfig.php');
        //$zip->addFile(Yii::app()->basePath. DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR .'license.php', 'license.php');
        $zip->close(); //Завершаем работу с архивом
        if (file_exists($zip_file_path))
            unlink($zip_file_path);
    }

    public function actionCreate()
    {
        date_default_timezone_set(Yii::app()->params['timezone']);

        if (!$this->StartBackup()) {
            //render error
            $this->render('create');
            return;
        }

        $this->EndBackup();
        $this->redirect(array('index'));
    }

    public function actionDelete($file = null)
    {
        $this->updateMenuItems();
        if (Yii::app()->request->getIsAjaxRequest()) {
            $file = $_GET['file'];
        }
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            if (file_exists($sqlFile))
                unlink($sqlFile);
        } else throw new CHttpException(404, Yii::t('app', 'File not found'));
        $this->actionIndex();
    }

    public function actionDownload($file = null)
    {
        $this->updateMenuItems();
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            if (file_exists($sqlFile)) {
                $request = Yii::app()->getRequest();
                $request->sendFile(basename($sqlFile), file_get_contents($sqlFile));
            }
        }
        throw new CHttpException(404, Yii::t('app', 'File not found'));
    }

    public function actionIndex()
    {
        $this->updateMenuItems();
        $path = $this->path;

        $dataArray = array();

        $list_files = glob($path . '*.zip');
        if ($list_files) {
            $list = array_map('basename', $list_files);
            sort($list);


            foreach ($list as $id => $filename) {
                $columns = array();
                $columns['id'] = $id;
                $columns['name'] = basename($filename);
                $columns['size'] = floor(filesize($path . $filename) / 1024) . ' KB';
                $columns['create_time'] = date('d.m.Y H:i', filectime($path . $filename));
                $dataArray[] = $columns;
            }
        }
        $dataProvider = new CArrayDataProvider($dataArray, array(
            'id' => 'id',
            'sort' => array(
                'defaultOrder' => 'name ASC',
            ),

        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionRestore($file = null)
    {
        $this->updateMenuItems();
        if (Yii::app()->request->getIsAjaxRequest()) {
            $file = $_GET['file'];
        }
        $this->updateMenuItems();
        $message = 'Резервная копия успешно восстановлена!';
        $sqlFile = $this->path . 'install.sql';
        $zip = new ZipArchive;
        if ($zip->open($this->path . basename($file)) === TRUE) {
            $zip->extractTo($this->path . DIRECTORY_SEPARATOR . 'tmp');
        }
        $zip->close();
        $dump_name = $this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . basename('sql_dump.sql');
        if (isset($dump_name)) {
            $sqlFile = $dump_name;
        }
        $restore_path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config';
        $tmp = $this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
        exec('mysql -u' . Yii::app()->db->username . ' -p' . Yii::app()->db->password . ' univefservicedesk < ' . $sqlFile);
        $files = scandir($tmp);
        unset($files[0], $files[1]);
        foreach ($files as $oldname) {
            rename($tmp . $oldname, $restore_path . DIRECTORY_SEPARATOR . $oldname);
        }
        unlink($restore_path . DIRECTORY_SEPARATOR . 'sql_dump.sql');
        if (is_dir($this->path . DIRECTORY_SEPARATOR . 'tmp')) {
            $this->removeDir($this->path . DIRECTORY_SEPARATOR . 'tmp');
        }
    }

    public function removeDir($dir)
    {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                is_dir($obj) ? removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function actionUpload()
    {
        $model = new UploadForm('upload');
        //var_dump($_POST);

        if (isset($_POST['UploadForm'])) {
            $model->attributes = $_POST['UploadForm'];
            $model->upload_file = CUploadedFile::getInstance($model, 'upload_file');
            if ($model->upload_file->saveAs($this->path . $model->upload_file)) {
                // redirect to success page
                $this->redirect(array('index'));
            }
        }

        $this->render('upload', array('model' => $model));
    }

    protected function updateMenuItems($model = null)
    {
        // create static model if model is null
        if ($model == null) $model = new UploadForm('install');

        switch ($this->action->id) {
            case 'restore':
            {
                // $this->menu[] = array('label'=>Yii::t('main-ui', 'List Backup'),'icon' =>'fa-solid fa-plus', 'url'=>array('index'));
            }
            case 'create':
                {
                    $this->menu[] = array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List Backup')));
                }
                break;
            case 'upload':
                {
                    $this->menu[] = array('label' => Yii::t('app', 'Create Backup') . ' ' . $model->label(), 'url' => array('create'));
                }
                break;
            default:
                {
                    $this->menu[] = array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Create Backup')));
                    //$this->menu[] = array('label'=>Yii::t('app', 'Restore Backup'),'icon' =>'icon-share', 'url'=>array('restore'));
                    $this->menu[] = array('icon' => 'fa-solid fa-download fa-xl', 'url' => array('upload'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Upload Backup')));
                    //$this->menu[] = array('label'=>Yii::t('app', 'Clean Database') . ' ' . $model->label(),'icon' =>'fa-solid fa-plus', 'url'=>array('clean'));
                }
                break;
        }
    }
}
