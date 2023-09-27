<?php

/**
 * This is the model class for table "brecords".
 *
 * The followings are the available columns in table 'brecords':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $content
 * @property string $author
 * @property string $created
 *
 * @property array $files
 * @property Files[] $knowFiles
 * @property KnowledgeFiles[] $knowledgeFiles
 *
 * The followings are the available model relations:
 * @property Bcats $parent
 */
class Knowledge extends CActiveRecord
{
    /** @var array */
    private $_files = [];

    /**
     * @return array
     */
    public function getFiles()
    {
        if ($this->isNewRecord) {
            return $this->_files;
        } else {
            return $this->getAttachments();
        }
    }

    /**
     * @param array $value
     */
    public function setFiles(array $value)
    {
        if (!empty($value)) {
            $this->_files = $value;
        }
    }

    /**
     * @return array
     */
    private function getAttachments()
    {
        $attachments = [];
        if (!empty($this->knowFiles)) {
            foreach ($this->knowFiles as $file) {
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Brecords the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'brecords';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public
    function rules()
    {
        return array(
            array('parent_id, name, content', 'required'),
            array('parent_id, responsible_id', 'numerical', 'integerOnly' => true),
            array('name ', 'length', 'max' => 100),
            array('author, created, bcat_name ', 'length', 'max' => 50),
            array('image', 'length', 'max' => 500),
            array('access', 'length', 'max' => 700),

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, parent_id, name, content, author, created, bcat_name, files', 'safe'),
            array('id, parent_id, name, content, author, created, bcat_name', 'safe', 'on' => 'search'),
            array('name', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'parent' => array(self::BELONGS_TO, 'Category', 'parent_id'),
            'knowledgeFiles' => array(self::HAS_MANY, 'KnowledgeFiles', 'knowledge_id'),
            'knowFiles' => array(self::MANY_MANY, 'Files', 'knowledge_files(knowledge_id, file_id)'),
            'knowledge_history' => array(self::HAS_MANY, 'KnowledgeHistory', 'kid'),
            'responsible' => array(self::BELONGS_TO, 'CUsers', 'responsible_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parent_id' => Yii::t('main-ui', 'Category'),
            'responsible_id' => Yii::t('main-ui', 'Responsible'),
            'name' => Yii::t('main-ui', 'Name'),
            'content' => Yii::t('main-ui', 'Description'),
            'author' => Yii::t('main-ui', 'Author'),
            'created' => Yii::t('main-ui', 'Created'),
            'bcat_name' => Yii::t('main-ui', 'Category'),
            'image' => '',
            'access' => Yii::t('main-ui', 'Roles access'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $role_name = Roles::model()->findByAttributes(array('value' => Yii::app()->user->role));
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('responsible_id', $this->responsible_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('author', $this->author, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('bcat_name', $this->bcat_name, true);
        $criteria->compare('image', $this->image, true);
        if (Yii::app()->user->checkaccess('systemAdmin')) {
            $criteria->compare('access', $this->access, true);
        } else {
            $criteria->compare('access', $role_name->name, true);
        }


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'bcat_name ASC, name ASC'),
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['knowPageCount'] ? Yii::app()->session['knowPageCount'] : 30,
            ),
        ));
    }

    public function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if (isset($_POST['Knowledge']['parent_id'])) {
            $cat_id = Categories::model()->findByPk($_POST['Knowledge']['parent_id']);
        } else {
            if(!isset($this->bcat_name)){
                $cat_id = Categories::model()->findByPk(1);
            }
        }
        $this->created = date("d.m.Y H:i");
        $author = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
        $this->author = $author->fullname;
        $this->bcat_name = $cat_id->name?$cat_id->name:$this->bcat_name;
        $this->access = $cat_id->access?$cat_id->access:$this->access;

        $creator_id = Yii::app()->user->id;
        $olddata = Knowledge::model()->findByPk($this->id);
        if ($olddata->name !== $_POST['Knowledge']['name']) {
            $this->addHistory(Yii::t('main-ui', 'Knowledge name changed:'). ' ' . $this->name, $creator_id);
        }
        if ($olddata->content !== $_POST['Knowledge']['content']) {
            $this->addHistory(Yii::t('main-ui', 'Knowledge content changed:'). ' ' . $this->content, $creator_id);
        }
        if ($olddata->responsible_id !== $_POST['Knowledge']['responsible_id']) {
            $responsible = CUsers::model()->findByPk($_POST['Knowledge']['responsible_id']);
            $this->addHistory(Yii::t('main-ui', 'Knowledge responsible changed:'). ' ' . $responsible->fullname, $creator_id);
        }
        if ($olddata->parent_id !== $_POST['Knowledge']['parent_id']) {
            $cat = Categories::model()->findByPk($_POST['Knowledge']['parent_id']);
            $this->addHistory(Yii::t('main-ui', 'Knowledge category changed:'). ' ' . $cat->name, $creator_id);
        }

        $managers = CUsers::model()->findAllBySql('select users.* from CUsers as users left join roles as rs on users.role = rs.value left join roles_rights as rr on rr.rid = rs.id  where rr.name = "setResponsibleKB" and rr.value = 1;');
        $manager_emails = [];
        foreach($managers as $manager){
            array_push($manager_emails, $manager['Email']);
        }
        $this->sendNotification($manager_emails);

        return parent::beforeSave();
    }

    public function afterSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        //$os_type = DetectOS::getOS();
        //$files = '';

        if (isset($_FILES['image'])) {
            foreach ($_FILES['image']['name'] as $key => $value){
                $model_f = new Files;
                $model_f->uploadFile = CUploadedFile::getInstanceByName('image[' . $key . ']');
                $result = $model_f->upload();
                if($result['error'] !== true){
                    $this->_files[] = $result['id'];
                }
            }
        }

        // Новый блок загрузки файла в форме создания заявки
        /*********************************************/
        $allFiles = [];
        $result1 = [];
        preg_match_all('#src="/uploads/([^"]+)"#i', $this->content, $result1);
        $result2 = [];
        preg_match_all('#href="/uploads/([^"]+)"#i', $this->content, $result2);
        if (!empty($result1[0][0])) {
            $allFiles = array_merge($allFiles, $result1[1]);
        }
        if (!empty($result2[0][0])) {
            $allFiles = array_merge($allFiles, $result2[1]);
        }

        // Удаляем все которые были удалены из редактора
//        $attachments = $this->getAttachments();
//        if (!empty($attachments)) {
//            foreach ($attachments as $id => $attachment) {
//                if (!in_array($attachment, $allFiles, false)) {
//                    /** @var Files $fileObj */
//                    $fileObj = Files::model()->findByPk($id);
//                    $fileObj->knowledgeFile->delete();
//                    $fileObj->delete();
//                }
//            }
//        }

        // Сохраняем вложения
        if (!empty($this->_files)) {
            $attachments = $this->getAttachments();
            foreach ($this->_files as $file) {
                // Если такое вложение существует, пропускаем.
                if (array_key_exists($file, $attachments)) {
                    continue;
                }
                $knowledgeFile = new KnowledgeFiles;
                $knowledgeFile->file_id = (int)$file;
                $knowledgeFile->knowledge_id = $this->id;
                $knowledgeFile->save(false);
            }
        }

        /*********************************************/
        /* if (isset ($_POST['Knowledge'])) {
            $images = CUploadedFile::getInstancesByName('image');
            if (isset($images) && count($images) > 0) {
                if (!is_dir(Yii::getPathOfAlias('webroot') . '/media/kb/' . $this->id)) {
                    mkdir(Yii::getPathOfAlias('webroot') . '/media/kb/' . $this->id);
                    chmod(Yii::getPathOfAlias('webroot') . '/media/kb/' . $this->id, 0755);
                }
                foreach ($images as $image) {
                    $image_name = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $image->name) : $image->name;
                    if ($image->saveAs(Yii::getPathOfAlias('webroot') . '/media/kb/' . $this->id . '/' . $image_name)) {
                        $files[] = $image->name;
                        $afiles[] = Yii::getPathOfAlias('webroot') . '/media/kb/' . $this->id . '/' . $image->name;
                    }
                }
            }
            if ($files) {
                $filelist = array();
                $path = Yii::getPathOfAlias('webroot') . '/media/kb/' . $this->id;
                $filelist = $this->myscandir($path);
                $value = implode(",", $filelist);
                $value = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $value) : $value;
                Knowledge::model()->updateByPk($this->id, array('image' => $value));

            }
        } */

        

        return parent::afterSave();
    }


    private function sendNotification($manager_emails) {
        // $usermail = CUsers::model()->findByAttributes(['Username' => $request->CUsers_id]);
        // if (isset($request->Managers_id)) {
        $base_url = Yii::app()->getBaseUrl(true);
        $subject = 'Обновлена запись базы знаний';
        $message = "ID: {$this->id}<br/>Название: {$this->name}<br/>Запись: {$this->content}<br/><a href=\"{$base_url}/knowledge/module/view/id/{$this->id}\">Посмотреть</a>";
        if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
            Yii::app()->mailQueue->push($manager_emails, $subject, $message, $priority = 0, $from = '', null, null);
        } else {
            SendMail::send($manager_emails, $subject, $message, $afiles = null, null);
        }
        // }
    }

    /**
     *
     * @throws \CDbException
     */
    public function beforeDelete()
    {
        
        // Удаляем связи с файлами
        foreach ($this->knowledgeFiles as $knowledgeFile) {
            /** @var Files $file */
            $file = $knowledgeFile->file;
            /** @var KnowledgeFiles $file */
            $knowledgeFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }

    // Сканирование папки с вложениями
    public function myscandir($dir, $sort = 0)
    {
        $list = scandir($dir, $sort);

        // если директории не существует
        if (!$list) {
            return false;
        }

        // удаляем . и .. (я думаю редко кто использует)
        if ($sort == 0) {
            unset($list[0], $list[1]);
        } else {
            unset($list[count($list) - 1], $list[count($list) - 1]);
        }
        return $list;
    }

    public function searchSame(string $match = "") {
        if ($match == "" ) {
            return NULL;
        }
        $match = str_replace("@", '', $match);
        $match = trim(preg_replace('/\s+/', ' ', $match));
        $match = strip_tags($match);
        $criteria = new CDbCriteria( array(
            'condition' => " MATCH ( name, content ) AGAINST (:match  IN BOOLEAN MODE)",
            'params'    => array(
                ':match' => "$match*",
            )
        ) );
        $dp = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false, //чтобы сработал limit в $criteria нужно установить 'pagination' => false
            'sort' => array(
                'defaultOrder' => 'id DESC'
            ),
        ));
        return $dp;
    }

    public function addHistory($action, $user)
    {
        $knowledge_history = new KnowledgeHistory();
        $knowledge_history->kid = $this->id;
        $knowledge_history->date = date("Y-m-d H:i:s");
        $knowledge_history->user = $user;
        $knowledge_history->user_name = CUsers::model()->findByPk($user)->fullname;
        $knowledge_history->action = $action;
        $knowledge_history->save(false);
    }

}
