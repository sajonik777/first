<?php

class BackupCommand extends CConsoleCommand
{
    public $tables = array();
    public $fp;
    public $file_name;
    public $_path = null;
    public $back_temp_file = 'backup_';

    public function run($args)
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (!$this->StartBackup()) {
            //render error
            $this->render('index');
            return;
        }
        $this->EndBackup();
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
        $zip->close(); //Завершаем работу с архивом
        if (file_exists($zip_file_path))
            unlink($zip_file_path);
    }

    protected function getPath()
    {

        $this->_path = dirname(__FILE__) . '/../_backup/';

        if (!file_exists($this->_path)) {
            mkdir($this->_path);
            chmod($this->_path, '777');
        }
        return $this->_path;
    }

}
