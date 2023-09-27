<?php

/**
 * Class CallWidget
 */
class CallWidget extends CWidget
{
    /**
     * @var CDbConnection
     */
//    private $connect;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    public $uniqid;

    /**
     * @return bool
     * @throws CException
     */
    private function checkSettings()
    {
        $file = __DIR__ . '../../config/ami.inc';
        $content = file_get_contents($file);
        $arr = unserialize(base64_decode($content));
        $model = new AsteriskForm();
        $model->setAttributes($arr);

        if (!empty($model->amiRecordPath) && !empty($this->uniqid)) {
            $this->path = $model->amiRecordPath;
            return true;
//            $connection = new CDbConnection($model->amiDBServer, $model->amiDBUser, $model->amiDBPassword);
//            $connection->setActive(true);
//            if ($connection->getActive()) {
//                $this->path = $model->amiRecordPath;
//                $this->connect = $connection;
//                return true;
//            }
        }

        return false;
    }

    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run()
    {
        try {
            if ($this->checkSettings()) {
//                $record = $this->connect->createCommand("SELECT DATE_FORMAT(calldate, '%Y-%m-%d') as dt, recordingfile as record FROM cdr WHERE uniqueid='{$this->uniqid}' limit 1")->queryRow();
                echo '<audio preload="auto" controls><source src="' . $this->path . '?GETFILE=' . $this->uniqid . '"></audio>';
            }
        } catch (CException $e) {

        }
    }
}
