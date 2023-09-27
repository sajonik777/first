<?php

/**
 * This is the model class for table "problems".
 *
 * The followings are the available columns in table 'problems':
 * @property integer $id
 * @property string $date
 * @property string $status
 * @property string $manager
 * @property string $category
 * @property string $incidents
 * @property string $workaround
 * @property string $decision
 * @property integer $knowledge
 * @property integer $knowledge_trigger
 * @property string $description
 * @property string $service
 * @property string $priority
 * @property string $downtimeh
 * @property string $influence
 * @property string $downtimem
 * @property string $assets
 * @property string $users
 *
 * @property array $files
 * @property Files[] $probFiles
 * @property ProblemFiles[] $problemFiles
 */
class Problems extends CActiveRecord
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
        if (!empty($this->probFiles)) {
            foreach ($this->probFiles as $file) {
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'problems';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, knowledge, knowledge_trigger', 'numerical', 'integerOnly' => true),
            array(
                'date, enddate, manager, category, service, priority, influence, downtime, assets',
                'length',
                'max' => 50
            ),
            array('status', 'length', 'max' => 70),
            array('slabel', 'length', 'max' => 500),
            array(
                'image',
                'file',
                'types' => 'doc,docx,xls,xlsx,odt,ods,pdf,jpg, jpeg, png, gif',
                'allowEmpty' => true
            ),
            array('status, category, service, priority, description, downtime', 'required'),
            array('creator', 'length', 'max' => 100),
            array('incidents, description, users, image, assets_names', 'length', 'max' => 200),
            array('workaround, decision, description, timestamp, image, files', 'safe'),
            array(
                'id, date, manager, category,status,image,timestamp,slabel, creator, incidents, workaround, decision, knowledge, knowledge_trigger, description, service, priority, influence, downtime, assets, users',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'phistory' => array(self::HAS_MANY, 'Phistory', 'pid'),
            'problemFiles' => array(self::HAS_MANY, 'ProblemFiles', 'problem_id'),
            'probFiles' => array(self::MANY_MANY, 'Files', 'problem_files(problem_id, file_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('main-ui', '#'),
            'date' => Yii::t('main-ui', 'Created'),
            'enddate' => Yii::t('main-ui', 'End date'),
            'manager' => Yii::t('main-ui', 'Problem manager'),
            'status' => Yii::t('main-ui', 'Status'),
            'slabel' => Yii::t('main-ui', 'Status'),
            'category' => Yii::t('main-ui', 'Category'),
            'creator' => Yii::t('main-ui', 'Creator'),
            'incidents' => Yii::t('main-ui', 'Incidents'),
            'workaround' => Yii::t('main-ui', 'Workaround'),
            'decision' => Yii::t('main-ui', 'Decision'),
            'knowledge' => Yii::t('main-ui', 'Knowledge'),
            'knowledge_trigger' => Yii::t('main-ui', 'Save in knowledgebase'),
            'description' => Yii::t('main-ui', 'Description'),
            'service' => Yii::t('main-ui', 'Service'),
            'priority' => Yii::t('main-ui', 'Priority'),
            'downtime' => Yii::t('main-ui', 'Downtime (hh:mm)'),
            'influence' => Yii::t('main-ui', 'Influence'),
            'assets' => Yii::t('main-ui', 'Configuration units'),
            'assets_names' => Yii::t('main-ui', 'Configuration units'),
            'users' => Yii::t('main-ui', 'Users'),
            'image' => Yii::t('main-ui', 'Attachments'),
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('enddate', $this->enddate, true);
        $criteria->compare('manager', $this->manager, true);
        $criteria->compare('category', $this->category, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('slabel', $this->slabel, true);
        $criteria->compare('incidents', $this->incidents, true);
        $criteria->compare('workaround', $this->workaround, true);
        $criteria->compare('decision', $this->decision, true);
        $criteria->compare('knowledge', $this->knowledge);
        $criteria->compare('knowledge_trigger', $this->knowledge_trigger);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('service', $this->service, true);
        $criteria->compare('priority', $this->priority, true);
        $criteria->compare('downtime', $this->downtime, true);
        $criteria->compare('influence', $this->influence, true);
        $criteria->compare('assets', $this->assets, true);
        $criteria->compare('assets_names', $this->assets_names, true);
        $criteria->compare('users', $this->users, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('creator', $this->creator, true);

//        if ($_SERVER['REQUEST_URI'] !== '/api/problems/') {
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/problems') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
                'sort' => [
                    'defaultOrder' => 'id DESC',
                ],
                'pagination' => [
                    'pageSize' => (int)Yii::app()->session['problemsPageCount'] ? Yii::app()->session['problemsPageCount'] : 30,
                ],
            ]);
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 'id DESC',
            ],
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);

    }

    public function searchmain()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->limit = 5;
        $criteria->compare('id', $this->id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('enddate', $this->enddate, true);
        $criteria->compare('manager', $this->manager, true);
        $criteria->compare('category', $this->category, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('slabel', $this->slabel, true);
        $criteria->compare('incidents', $this->incidents, true);
        $criteria->compare('workaround', $this->workaround, true);
        $criteria->compare('decision', $this->decision, true);
        $criteria->compare('knowledge', $this->knowledge);
        $criteria->compare('knowledge_trigger', $this->knowledge_trigger);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('service', $this->service, true);
        $criteria->compare('priority', $this->priority, true);
        $criteria->compare('downtime', $this->downtime, true);
        $criteria->compare('influence', $this->influence, true);
        $criteria->compare('assets', $this->assets, true);
        $criteria->compare('assets_names', $this->assets_names, true);
        $criteria->compare('users', $this->users, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('creator', $this->creator, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false, //чтобы сработал limit в $criteria нужно установить 'pagination' => false
            'sort' => array(
                'defaultOrder' => 'id DESC'
            ),
        ));
    }

    public function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if ($this->isNewRecord) {
            $this->date = date("d.m.Y H:i");
            $this->timestamp = date('Y-m-d H:i:s');

        }
        $service = Service::model()->findByAttributes(array('name' => $this->service));
        $label = Pstatus::model()->findByAttributes(array('name' => $this->status));
        $this->slabel = $label->label;
        $manager = CUsers::model()->findByAttributes(array('Username' => $service->manager));
        $creator = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
        $this->creator = $creator->fullname;
        $this->manager = $manager->fullname;
        if (isset ($_POST['Problems']['status'])) {
            if ($_POST['Problems']['status'] == 'Решена') {
                if (!$this->enddate) {
                    $this->enddate = date("d.m.Y H:i");
                }
            }
        }
        if (isset ($_POST['incidents'])) {
            $this->incidents = implode(",", $_POST['incidents']);
            $incidents = $_POST['incidents'];
            foreach ($incidents as $incident) {
                $zayavki = Request::model()->findByPk($incident);
                $users[] = $zayavki->fullname;
                $assets[] = $zayavki->cunits;
                $assets_n[] = $zayavki->cunits;
            }
            $user = array_unique($users);
            $asset = array_unique($assets);
            $asset_nn = array_unique($assets_n);
            $this->users = implode(",", $user);
            $this->assets = implode(",", $asset);
            $this->assets_names = implode(",", $asset_nn);
        }
        if (isset ($_POST['assets'])) {
            $ass = $_POST['assets'];
            foreach ($ass as $item) {
                $user = Cunits::model()->findByPk($item);
                $fullname = CUsers::model()->findByAttributes(array('Username' => $user->user));
                $users[] = $fullname->fullname;
                $assets_names[] = $user->name;
            }
            $user = array_unique($users);
            $this->users = implode(",", $user);
            $this->assets = implode(",", $assets_names);
            $this->assets_names = implode(",", $assets_names);
        }
        if ($this->knowledge_trigger == 1) {
            $brecord = new Knowledge;
            $brecord->parent_id = 1;
            $brecord->name = 'Описание решения проблемы №' . $this->id;
            $brecord->content = '
            <h3>Проблема №' . $this->id . '</h3>
            <p><strong>Описание проблемы:</strong></p>
            <span>' . $this->description . '</span>
            <p><strong>Обходное решение:</strong></p>
            <span>' . $this->workaround . '</span>
            <p><strong>Основное решение:</strong></p>
            <span>' . $this->decision . '</span>';
            if ($brecord->save(false)) {
                $this->knowledge = $brecord->id;
                $this->knowledge_trigger = 2;
            }
        }
        if (!$this->isNewRecord) {
            //проверка данных на изменение и запись в историю
            $olddata = Problems::model()->findByPk($this->id);
            if ($olddata->status !== $_POST['Problems']['status']) {
                $this->addHistory('Изменен статус проблемы: ' . $this->slabel, $this->creator);
            }
            if ($olddata->priority !== $_POST['Problems']['priority']) {
                $this->addHistory('Изменен приоритет проблемы: ' . $this->priority, $this->creator);
            }
            if ($olddata->category !== $_POST['Problems']['category']) {
                $this->addHistory('Изменена категория проблемы: ' . $this->category, $this->creator);
            }
            if ($olddata->downtime !== $_POST['Problems']['downtime']) {
                $this->addHistory('Изменено время простоя сервиса: ' . $this->downtime, $this->creator);
            }
            if ($olddata->service !== $_POST['Problems']['service']) {
                $this->addHistory('Изменение сервиса: ' . $this->service, $this->creator);
            }
            if ($olddata->influence !== $_POST['Problems']['influence']) {
                $this->addHistory('Изменено влияние проблемы: ' . $this->influence, $this->creator);
            }
            if ($olddata->description !== $_POST['Problems']['description']) {
                $this->addHistory('Изменено описание проблемы: ' . $this->description, $this->creator);
            }
            if (trim(strip_tags($olddata->workaround)) !== trim(strip_tags($_POST['Problems']['workaround']))) {
                $this->addHistory('Найдено обходное решение: ' . trim(strip_tags($this->workaround)), $this->creator);
            }
            if (trim(strip_tags($olddata->decision)) !== trim(strip_tags($_POST['Problems']['decision']))) {
                $this->addHistory('Найдено основное решение: ' . trim(strip_tags($this->decision)), $this->creator);
            }

        }
        return parent::beforeSave();
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Problems the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function addHistory($action, $user)
    {
        $phistory = new Phistory;
        $phistory->pid = $this->id;
        $phistory->date = date("d.m.Y H:i");
        $phistory->user = $user;
        $phistory->action = $action;
        $phistory->save(false);
    }

    /**
     *
     * @throws \CDbException
     */
    public function beforeDelete()
    {
        // Удаляем связи с файлами
        foreach ($this->problemFiles as $problemFile) {
            /** @var Files $file */
            $file = $problemFile->file;
            /** @var ProblemFiles $file */
            $problemFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }

    // Сканирование папки с вложениями
    public function afterSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        //$os_type = DetectOS::getOS();
        //$files = '';

        if (isset($_FILES['image'])) {
            foreach ($_FILES['image']['name'] as $key => $value) {
                $model_f = new Files;
                $model_f->uploadFile = CUploadedFile::getInstanceByName('image[' . $key . ']');
                $result = $model_f->upload();
                if ($result['error'] !== true) {
                    $this->_files[] = $result['id'];
                }
            }
        }

        $afiles = array();
        if ($this->isNewRecord) {
            $this->addHistory('Проблема зарегистрирована!', $this->creator);
        }

        // Новый блок загрузки файла в форме создания заявки
        /*********************************************/
        $allFiles = [];
        $result1 = [];
        preg_match_all('#src="/uploads/([^"]+)"#i', $this->description, $result1);
        $result2 = [];
        preg_match_all('#href="/uploads/([^"]+)"#i', $this->description, $result2);
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
//                    $fileObj->problemFile->delete();
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
                $problemFile = new ProblemFiles;
                $problemFile->file_id = (int)$file;
                $problemFile->problem_id = $this->id;
                $problemFile->save(false);

                // url для письма
                $fileObj = Files::model()->findByPk($file);
                $afiles[] = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileObj->file_name;
            }
        }
        /*********************************************/

        //Блок отправки уведомлений
        $manager = CUsers::model()->findByAttributes(array('fullname' => $this->manager));
        $subject = '' . strip_tags($this->status) . ' проблема ' . $this->id . '';
        if ($this->isNewRecord) {
            $message = 'Была {status} проблема №{id} в категории {category} сервиса {service}. Проблеме назначен приоритет {priority}. Время недоступности сервиса {downtime}. Ответственный {manager}.';
        } else {
            $message = 'Изменение проблемы со статусом {status} №{id} в категории {category} сервиса {service}. Проблеме назначен приоритет {priority}. Время недоступности сервиса {downtime}. Ответственный {manager}.';
        }
        //Если у пользователя в профиле установлен переключатель Уведомлять по email
        if ($manager->sendmail == 1) {
            $manager_address = $manager->Email;
            $this->Mailsend($manager_address, $subject, $message, $afiles);
        }


        return parent::afterSave();
    }

    // Функция отправки E-mail

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

    // Генерируем сообщение по шаблону

    public function Mailsend($address, $subject, $message, $afiles)
    {
        $umessage = $this->MessageGen($message);
        SendMail::send($address, $subject, $umessage, $afiles);
    }

    public function MessageGen($content)
    {
        $s_message = Yii::t('message', "$content", array(
            '{id}' => $this->id,
            '{category}' => $this->category,
            '{status}' => $this->status,
            '{service}' => $this->service,
            '{priority}' => $this->priority,
            '{downtime}' => $this->downtime,
            '{description}' => $this->description,
            '{manager}' => $this->manager,
            '{url}' => '<a href="' . Yii::app()->params['homeUrl'] . '/problems/view/' . $this->id . '">№ ' . $this->id . '</a>',
        ));
        return $s_message;
    }
}
