<?php

/**
 * This is the model class for table "cunits".
 *
 * The followings are the available columns in table 'cunits':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $status
 * @property string $slabel
 * @property string $cost
 * @property string $user
 * @property string $fullname
 * @property string $inventory
 * @property string $date
 * @property string $datein
 * @property string $dateout
 * @property string $warranty_start
 * @property string $warranty_end
 */
class Cunits extends CActiveRecord
{
    public $manufacturer;
    public $trademark;
    public $description;
    public $image;
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
        if (!empty($this->untFiles)) {
            foreach ($this->untFiles as $file) {
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    public static
    function all()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $aid) {
            $array[$aid->id] = $aid->name;
        }
        return $array;
    }

    public static
    function mall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $aid) {
            $array[$aid->name] = $aid->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Cunits the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static
    function uall()
    {
        $models = self::model()->findAllByAttributes(array('user' => Yii::app()->user->name));
        $array = array();
        foreach ($models as $aid) {
            $array[$aid->name] = $aid->name;
        }
        return $array;
    }

    public static function call()
    {
        $user = CUsers::model()->findByPk(Yii::app()->user->id);
        $models = self::model()->findAllByAttributes(array('company' => $user->company));
        $array = array();
        foreach ($models as $aid) {
            $array[$aid->name] = $aid->name;
        }
        return $array;
    }

    public static
    function auall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $aid) {
            $array[] = $aid->name;
        }
        return $array;
    }

    public static
    function uuall($user)
    {
        $models = self::model()->findAllByAttributes(array('user' => $user));
        $array = array();
        foreach ($models as $aid) {
            $array[] = $aid->name;
        }
        return $array;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cunits';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('numerical', 'integerOnly' => true),
            array('name, type, status, datein, user', 'required'),
            array('type, status, cost, user, inventory, date, datein, dateout, warranty_start, warranty_end', 'length', 'max' => 50),
            array('fullname, company', 'length', 'max' => 70),
            array('slabel', 'length', 'max' => 400),
            array('name, dept, location', 'length', 'max' => 100),
            array('inventory', 'unique', 'message' => 'Такой инвентарный номер занят'),
            array('name', 'match', 'not' => true, 'pattern' => '/\,/i', 'message' => 'Не используйте запятые в имени КЕ'),
            ['image', 'length', 'max' => 250],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('name, cost, inventory, datein, dateout, warranty_start, warranty_end, description', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
            array('id, assets, name, type, status, slabel, cost, user, fullname, inventory, date, dept, datein, dateout, warranty_start, warranty_end, company, location, description', 'safe', 'on' => 'search'),
        );
    }

    public function uniqueIdAndName($attribute, $params = array())
    {
        if (!$this->hasErrors()) {
            $params['criteria'] = array(
                'condition' => 'inventory=:inventory',
                'params' => array(':inventory' => $this->inventory),
            );
            $validator = CValidator::createValidator('unique', $this, $attribute, $params);
            $validator->validate($this, array($attribute));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'asset' => array(self::HAS_MANY, 'Asset', 'uid'),
            //'models_rl' => array(self::BELONGS_TO, 'Models', 'models_id'),
            'cunitsFiles' => array(self::HAS_MANY, 'CunitsFiles', 'cunits_id'),
            'untFiles' => array(self::MANY_MANY, 'Files', 'cunits_files(cunits_id, file_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('main-ui', '#'),
            'name' => Yii::t('main-ui', 'Name'),
            'type' => Yii::t('main-ui', 'Unit type'),
            'status' => Yii::t('main-ui', 'Status'),
            'slabel' => Yii::t('main-ui', 'Status'),
            'cost' => Yii::t('main-ui', 'Cost'),
            'user' => Yii::t('main-ui', 'Username'),
            'fullname' => Yii::t('main-ui', 'Username'),
            'inventory' => Yii::t('main-ui', 'Inventory #'),
            'date' => Yii::t('main-ui', 'Created'),
            'assets' => Yii::t('main-ui', 'Assets'),
            'dept' => Yii::t('main-ui', 'Department'),
            'datein' => Yii::t('main-ui', 'Start expluatation'),
            'dateout' => Yii::t('main-ui', 'End expluatation'),
            'warranty_start' => Yii::t('main-ui', 'Warranty start'),
            'warranty_end' => Yii::t('main-ui', 'Warranty end'),
            'company' => Yii::t('main-ui', 'Company'),
            'location' => Yii::t('main-ui', 'Location'),
            'description' => Yii::t('main-ui', 'Description'),
            'image' => Yii::t('main-ui', 'Attachments'),
            //'manufacturer' => Yii::t('main-ui', 'Manufacturer'),
            //'trademark' => Yii::t('main-ui', 'Trademark'),
            //'models_id' => Yii::t('main-ui', 'Model'),
        );
    }

    public function importLabels()
    {
        return array(

            'name' => Yii::t('main-ui', 'Name'),
            'type' => Yii::t('main-ui', 'Unit type'),
            'status' => Yii::t('main-ui', 'Status'),
            'user' => Yii::t('main-ui', 'User login'),
            'inventory' => Yii::t('main-ui', 'Inventory #'),
            'location' => Yii::t('main-ui', 'Location'),
            'datein' => Yii::t('main-ui', 'Start expluatation'),
            'dateout' => Yii::t('main-ui', 'End expluatation'),
            'warranty_start' => Yii::t('main-ui', 'Warranty start'),
            'warranty_end' => Yii::t('main-ui', 'Warranty end'),
            'description' => Yii::t('main-ui', 'Description'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('slabel', $this->slabel, true);
        $criteria->compare('cost', $this->cost, true);
        $criteria->compare('user', $this->user, true);
        $criteria->compare('fullname', $this->fullname, true);
        $criteria->compare('inventory', $this->inventory, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('datein', $this->datein, true);
        $criteria->compare('dateout', $this->dateout, true);
        $criteria->compare('warranty_start', $this->warranty_start, true);
        $criteria->compare('warranty_end', $this->warranty_end, true);
        $criteria->compare('assets', $this->assets, true);
        $criteria->compare('dept', $this->dept, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('description', $this->description, true);
        if (!Yii::app()->user->checkAccess('viewMyselfUnit')) {
            $criteria->compare('company', $this->company, true);
        }else{
            $user = CUsers::model()->findByAttributes(array('Username'=>Yii::app()->user->name));
            $criteria->compare('company', $user->company, true);
        }

        $cunit_data = new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['cunitsPageCount'] ? Yii::app()->session['cunitsPageCount'] : 30,
            ),
        ));
        $_SESSION['cunit_records'] = $cunit_data;
//        if ($_SERVER['REQUEST_URI'] !== '/api/cunits/') {
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/cunits') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => (int)Yii::app()->session['cunitsPageCount'] ? Yii::app()->session['cunitsPageCount'] : 30,
                ],
            ]);
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);
    }

    public
    function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if ($this->isNewRecord) {
            $this->date = date("d.m.Y H:i");
            if (isset ($_POST['assets'])) {
                $this->assets = implode(",", $_POST['assets']);
            }
        }
        $username = CUsers::model()->findByAttributes(array('Username' => $this->user));
        if ($username){
            // $this->company = $username->company;
            $this->fullname = $username->fullname;
            $this->dept = $username->department;
        }
        if ($_POST['Cunits']['company']) {
            $this->company = $_POST['Cunits']['company'];
        }
        
        $stat = Astatus::model()->findByAttributes(array('name' => $this->status));
        $this->slabel = $stat->label;
        if (!$this->isNewRecord) {
            $unit = Cunits::model()->findByPk($this->id);
            if ($unit->name !== $_POST['Cunits']['name']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменено имя КЕ: <b>' . $this->name . '</b>';
                $history->save(false);
            }
            if ($unit->type !== $_POST['Cunits']['type']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменен тип КЕ: <b>' . $this->type . '</b>';
                $history->save(false);
            }
            if ($unit->status !== $_POST['Cunits']['status']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменен статус КЕ: ' . $this->slabel;
                $history->save(false);
            }
            if ($unit->inventory !== $_POST['Cunits']['inventory']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменено местоположение КЕ: <b>' . $this->inventory . '</b>';
                $history->save(false);
            }
            if ($unit->user !== $_POST['Cunits']['user']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменен пользователь КЕ: <b>' . $this->fullname . '</b>';
                $history->save(false);
            }
            if ($unit->cost !== $this->cost) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменена стоимость КЕ: <b>' . $this->cost . ' рублей</b>';
                $history->save(false);
            }
            if ($unit->datein !== $_POST['Cunits']['datein']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменена дата ввода в эксплуатацию: <b>' . $this->datein . '</b>';
                $history->save(false);
            }
            if ($unit->dateout !== $_POST['Cunits']['dateout']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменена дата вывода из эксплуатации: <b>' . $this->dateout . '</b>';
                $history->save(false);
            }
            if ($unit->warranty_start !== $_POST['Cunits']['warranty_start']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменена дата начала гарантии: <b>' . $this->warranty_start . '</b>';
                $history->save(false);
            }
            if ($unit->warranty_end !== $_POST['Cunits']['warranty_end']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменена дата окончания гарантии: <b>' . $this->warranty_end . '</b>';
                $history->save(false);
            }
            if ($unit->description !== $_POST['Cunits']['description']) {
                $history = new Uhistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->uid = $this->id;
                $history->action = 'Изменено описание: <b>' . $this->description . '</b>';
                $history->save(false);
            }
        }

        return parent::beforeSave();
    }

    /**
     *
     * @throws \CDbException
     */
    public function beforeDelete()
    {
        // Удаляем связи с файлами
        foreach ($this->cunitsFiles as $cunitFile) {
            /** @var Files $file */
            $file = $cunitFile->file;
            /** @var ProblemFiles $file */
            $cunitFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }

    public function afterSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
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


        // Сохраняем вложения
        if (!empty($this->_files)) {
            $attachments = $this->getAttachments();
            foreach ($this->_files as $file) {
                // Если такое вложение существует, пропускаем.
                if (array_key_exists($file, $attachments)) {
                    continue;
                }
                $cunitsFile = new CunitsFiles;
                $cunitsFile->file_id = (int)$file;
                $cunitsFile->cunits_id = $this->id;
                $cunitsFile->save(false);

            }
        }
        if ($this->isNewRecord) {
            if (isset ($_POST['assets'])) {
                $ass = $_POST['assets'];
                $sum = 0;
                foreach ($ass as $item) {
                    $cid = Asset::model()->findByPk($item);
                    Asset::model()->updateByPk($cid->id, array('uid' => $this->id, 'cusers_name' => $this->user, 'cusers_fullname' => $this->fullname, 'cusers_dept' => $this->dept));
                    $sum = $sum + $cid->cost;
                    $history = new Uhistory();
                    $history->date = date("d.m.Y H:i");
                    $history->user = Yii::app()->user->name;
                    $history->uid = $this->id;
                    $history->action = 'Добавлен актив: <b>' .$cid->asset_attrib_name  .' ' . $cid->name . '</b>. Инвентарный номер: <b>' . $cid->inventory . '</b>';
                    $history->save(false);

                }
                Cunits::model()->updateByPk($this->id, array('cost' => $sum));
            }
            $history = new Uhistory();
            $history->date = date("d.m.Y H:i");
            $history->user = Yii::app()->user->name;
            $history->uid = $this->id;
            $history->action = 'Добавлена КЕ: ' . $this->type . ' ' . $this->name . '. Инвентарный номер ' . $this->inventory . '. Дата ввода в эксплуатацию: ' . $this->datein . '. Дата вывода из эксплуатации: ' . $this->dateout;
            $history->save(false);
        } else {
            $ass = explode(",", $this->assets);
            foreach ($ass as $key => $item) {
                $cid = Asset::model()->findByPk($item);
                if (!empty($cid))
                    Asset::model()->updateByPk($cid->id, array('uid' => $this->id, 'cusers_name' => $this->user, 'cusers_fullname' => $this->fullname, 'cusers_dept' => $this->dept));
            }
        }

        return parent::afterSave();
    }

    public static function getDistinctLocations(){
        $connection = Yii::app()->db;
        $sql = 'SELECT DISTINCT `location` FROM `cunits` ORDER BY `location`';
        $result = $connection->createCommand($sql)->queryAll();
        $res = array();
        foreach ($result as $r){
            array_push($res, $r['location']);
        }
        return $res;
    }



    public static function countAndCostByAttributes(array $attrs){
        $where = "";
        foreach($attrs as $k => $v) {
            $where .= '`'.$k.'` = "'.$v.'" AND ';
        }
        $where = substr($where, 0, -4);
        $connection = Yii::app()->db;
        $sql = 'SELECT COUNT(id) as count, SUM(cost) as cost FROM `cunits` WHERE' . $where;

        // var_dump($sql);
        $result = $connection->createCommand($sql)->queryAll();
        return $result;
    }

}
