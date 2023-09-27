<?php


class FilesController extends Controller
{
    /**
     * @return void
     */
    public function actionUpload()
    {
        if (!Yii::app()->user->checkAccess('uploadFilesRequest') and !Yii::app()->user->isGuest) {
            exit;
        }
        $model = new Files();

        if (null !== CUploadedFile::getInstanceByName('file')) {
            $model->uploadFile = CUploadedFile::getInstanceByName('file');
        }

        if (isset($_FILES['image'])) {
            $count = count($_FILES['image']['name']);
            if (null !== CUploadedFile::getInstanceByName('image[' . ($count - 1) . ']')) {
                $model->uploadFile = CUploadedFile::getInstanceByName('image[' . ($count - 1) . ']');
            }
        }

        echo CJSON::encode($model->upload());
    }

    /**
     * @return void
     */
    public function actionUpload2()
    {
        if (!Yii::app()->user->checkAccess('uploadFilesRequest') && !Yii::app()->user->isGuest) {
            exit;
        }

        $model = new Files();
        $uploadFile = CUploadedFile::getInstanceByName('file[0]');
        if (null !== $uploadFile) {
            $model->uploadFile = $uploadFile;
        }

        $result = $model->upload();
        if (array_key_exists('error', $result) && $result['error'] === true) {
            $ret = $result;
        } else {
            $ret['file-0'] = $result;
        }

        echo CJSON::encode($ret);
    }

    /**
     * @param $file
     * @throws CDbException
     */
    public function actionDelete($file)
    {
        if (Yii::app()->user->isGuest) {
            exit;
        }

        if (is_numeric($file)) {
            $model = Files::model()->findByPk($file);
        } else {
            $model = Files::model()->findByAttributes(['file_name' => $file]);
        }

        if ($model->problemFile) {
            $problem = Problems::model()->findByPk($model->problemFile->problem_id);
            $problem->addHistory(Yii::t('main-ui', 'Deleted file: ') . '<b>' . $model->name . '</b>');
            $model->problemFile->delete();
        }
        if ($model->knowledgeFile) {
            $model->knowledgeFile->delete();
        }
        if ($model->commentFile) {
            $model->commentFile->delete();
        }
        if ($model->assetFile) {
            $model->assetFile->delete();
        }
        if ($model->cunitsFile) {
            $model->cunitsFile->delete();
        }
        if ($model->contractsFile) {
            $model->contractsFile->delete();
        }
        if ($model->companiesFile) {
            $model->companiesFile->delete();
        }
        if ($model->requestFile) {
            $request = Request::model()->findByPk($model->requestFile->request_id);
            $request->AddHistory(Yii::t('main-ui', 'Deleted file: ') . '<b>' . $model->name . '</b>');
            $model->requestFile->delete();
        }

        $model->delete();
    }

    /**
     * @param $file
     * @return void
     */
    public function actionDownload($file)
    {
        if (Yii::app()->user->isGuest) {
            exit;
        }

        $file_path = ROOT_PATH . '/uploads/' . $file;
        if (file_exists($file_path)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            $fileObj = Files::model()->findByAttributes(['file_name' => $file]);
            // заставляем браузер показать окно сохранения файла
            if (isset($fileObj) and !empty($fileObj)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $fileObj->name . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                // читаем файл и отправляем его пользователю
                if ($fd = fopen($file_path, 'rb')) {
                    while (!feof($fd)) {
                        print fread($fd, 1024);
                    }
                    fclose($fd);
                }
            }
            exit;
        }
    }
}
