<?php

class CommentController extends Controller
{
    public $layout = false;

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('deletefile', 'read', 'inline'),
                'roles' => array('updateRequest'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /*protected function beforeAction()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        return true;
    }*/

    public function actionRead($id)
    {
        $model = $this->loadModel($id);
        $model->read = true;
        $model->save();
    }

    public function actionDeleteFile($id, $file)
    {
        $model = $this->loadModel($id);
        $os_type = DetectOS::getOS();
        $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251' , $file) : $file;
        $filePath = Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id . '/' . $file;
        if (is_file($filePath)) {
            unlink($filePath);
        }
        $path = Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id;
        $files = $this->myscandir($path);
        $value = implode(",", $files);
        $value = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $value) : $value;
        Comments::model()->updateByPk($model->id, array('files' => $value));
    }

    public function actionInline()
    {
        if (isset($_POST))
        Comments::model()->updateByPk($_POST['id'], array('comment' => $_POST['text']));
    }

    public function myscandir($dir, $sort = 0)
    {
        $list = scandir($dir, $sort);
        if (!$list) return false;
        if ($sort == 0) unset($list[0], $list[1]);
        else unset($list[count($list) - 1], $list[count($list) - 1]);
        return $list;
    }

    public function loadModel($id)
    {
        if (($model = Comments::model()->findByPk($id)) === null) {
            throw new CHttpException(404, 'The requested comment does not exist.');
        } else {
            return $model;
        }
    }
}
