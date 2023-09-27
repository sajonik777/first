<?php

class RestoreCommand extends CConsoleCommand
{
    public $sqlArray = array();
    public $message;
    public $file;
    public $_path = null;

    public function run($args)
    {
        $this->_path = Yii::app()->basePath . '/../_backup/';
        $file = 'backup_08.01.2015_02.45.zip';
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
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'ad.inc', $restore_path . DIRECTORY_SEPARATOR . 'ad.inc');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'appear.inc', $restore_path . DIRECTORY_SEPARATOR . 'appear.inc');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'attach.inc', $restore_path . DIRECTORY_SEPARATOR . 'attach.inc');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'getmail.inc', $restore_path . DIRECTORY_SEPARATOR . 'getmail.inc');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'license.php', $restore_path . DIRECTORY_SEPARATOR . 'license.php');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'params.inc', $restore_path . DIRECTORY_SEPARATOR . 'params.inc');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'request.inc', $restore_path . DIRECTORY_SEPARATOR . 'request.inc');
        rename($this->path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'sms.inc', $restore_path . DIRECTORY_SEPARATOR . 'sms.inc');
        $this->execSqlFile($sqlFile);
        if (is_dir($this->path . DIRECTORY_SEPARATOR . 'tmp')) {
            $this->removeDir($this->path . DIRECTORY_SEPARATOR . 'tmp');
        }
    }

    public function execSqlFile($sqlFile)
    {
        if (file_exists($sqlFile)) {
            $sqlArray = file_get_contents($sqlFile);

            $cmd = Yii::app()->db->createCommand($sqlArray);
            try {
                $cmd->execute();
            } catch (CDbException $e) {
                $message = $e->getMessage();
            }
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

    protected function getPath()
    {

        $this->_path = dirname(__FILE__) . '/../_backup/';
        return $this->_path;
    }

}