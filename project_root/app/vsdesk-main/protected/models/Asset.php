<?php

/**
 * This is the model class for table "asset".
 *
 * The followings are the available columns in table 'asset':
 * @property integer $id
 * @property string $name
 * @property string $inventory
 * @property integer $asset_attrib_id
 * @property integer $attribute_id
 * @property string $attribute_name
 * @property string $value
 * @property string $warranty_start
 * @property string $warranty_end

 *
 * The followings are the available model relations:
 * @property AssetAttrib $assetAttrib
 */

class Asset extends CActiveRecord
{
    /** @var array */
    public $date;
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
        if (!empty($this->assFiles)) {
            foreach ($this->assFiles as $file) {
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
            $array[$aid->name] = $aid->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Asset the static model class
     */
    public static
    function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static
    function aall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $aid) {
            if ($aid->uid == NULL) {
                $array[$aid->id] = $aid->asset_attrib_name . ' | ' . $aid->name;
            }
        }
        return $array;
    }

    /**
     * @return string the associated database table name
     */
    public
    function tableName()
    {
        return 'asset';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public
    function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, cost, asset_attrib_id', 'required'),
            array('asset_attrib_id, cusers_id,uid', 'numerical', 'integerOnly' => true),
            array('name, date, inventory, cost, asset_attrib_name, warranty_end, warranty_start, location,status, cusers_name, cusers_fullname', 'length', 'max' => 50),
            array('slabel', 'length', 'max' => 400),
            array('name, date, inventory, cost, description', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
            ['image', 'length', 'max' => 250],
            //array('id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, date, inventory, cost, asset_attrib_id, asset_attrib_name, status,slabel, cusers_id, cusers_name, cusers_fullname, cusers_dept, description', 'safe', 'on' => 'search'),
            array('inventory', 'unique', 'message' => 'Такой инвентарный номер занят'),
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
    public
    function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'assetAttrib' => array(self::BELONGS_TO, 'AssetAttrib', 'asset_attrib_id'),
            'assetValues' => array(self::HAS_MANY, 'AssetValues', 'asset_id'),
            'asset' => array(self::BELONGS_TO, 'Cunits', 'uid'),
            'assetFiles' => array(self::HAS_MANY, 'AssetFiles', 'asset_id'),
            'assFiles' => array(self::MANY_MANY, 'Files', 'asset_files(asset_id, file_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public
    function attributeLabels()
    {
        return array(
            'id' => Yii::t('main-ui', '#'),
            'name' => Yii::t('main-ui', 'Name'),
            'date' => Yii::t('main-ui', 'Date'),
            'inventory' => Yii::t('main-ui', 'Inventory number'),
            'cost' => Yii::t('main-ui', 'Cost'),
            'location' => Yii::t('main-ui', 'Location'),
            'status' => Yii::t('main-ui', 'Status'),
            'slabel' => Yii::t('main-ui', 'Status'),
            'asset_attrib_id' => Yii::t('main-ui', 'Asset type'),
            'asset_attrib_name' => Yii::t('main-ui', 'Asset type'),
            'cusers_name' => Yii::t('main-ui', 'User login'),
            'cusers_fullname' => Yii::t('main-ui', 'Fullname'),
            'cusers_id' => Yii::t('main-ui', 'Username'),
            'cusers_dept' => Yii::t('main-ui', 'Department'),
            'description' => Yii::t('main-ui', 'Description'),
            'image' => Yii::t('main-ui', 'Attachments'),
            'warranty_start' => Yii::t('main-ui', 'Warranty Start'),
            'warranty_end' => Yii::t('main-ui', 'Warranty End'),

        );
    }

    public
    function importLabels()
    {
        return array(
            'name' => Yii::t('main-ui', 'Name'),
            'inventory' => Yii::t('main-ui', 'Inventory number'),
            'cost' => Yii::t('main-ui', 'Cost'),
            'location' => Yii::t('main-ui', 'Location'),
            'status' => Yii::t('main-ui', 'Status'),
            'description' => Yii::t('main-ui', 'Description'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public
    function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('inventory', $this->inventory, true);
        $criteria->compare('cost', $this->cost, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('slabel', $this->slabel, true);
        $criteria->compare('asset_attrib_id', $this->asset_attrib_id);
        $criteria->compare('asset_attrib_name', $this->asset_attrib_name);
        $criteria->compare('cusers_id', $this->cusers_id);
        $criteria->compare('cusers_name', $this->cusers_name);
        $criteria->compare('cusers_fullname', $this->cusers_fullname);
        $criteria->compare('cusers_dept', $this->cusers_dept);
        $criteria->compare('description', $this->description, true);

        $asset_data = new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['assetPageCount'] ? Yii::app()->session['assetPageCount'] : 30,
            ),
        ));
        $_SESSION['asset_records'] = $asset_data;
//        if ($_SERVER['REQUEST_URI'] !== '/api/assets/') {
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/assets') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
                'pagination' => [
                    'pageSize' => (int)Yii::app()->session['assetPageCount'] ? Yii::app()->session['assetPageCount'] : 30,
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
        $this->warranty_start = date_format(new DateTime($_POST['Asset']['warranty_start']),'Y-m-d');
        $this->warranty_end = date_format(new DateTime($_POST['Asset']['warranty_end']),'Y-m-d');

        if ($this->isNewRecord) {
            $this->date = date("d.m.Y H:i");
        }
        if (isset($_POST['Asset']['status'])) {
            $label = Astatus::model()->findByAttributes(array('name' => $_POST['Asset']['status']));
            $this->slabel = $label->label;
        } else {
            $label = Astatus::model()->findByAttributes(array('name' => $this->status));
            $this->slabel = $label->label;
        }
        if (!$this->isNewRecord) {
            $olddata = Asset::model()->findByPk($this->id);
            if (isset($_POST['Asset']['status']) && $olddata->status !== $_POST['Asset']['status']) {
                $history = new Ahistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->aid = $this->id;
                $history->action = Yii::t('main-ui', 'Changed value').' "'.Yii::t('main-ui', 'Status').'" '.Yii::t('main-ui', 'from').': <b> ' . $olddata->slabel . '</b> '.Yii::t('main-ui', 'to').': <b>' . $this->slabel . '</b>.';
                $history->save(false);
            }
            if (isset($_POST['Asset']['name']) && $olddata->name !== $_POST['Asset']['name']) {
                $history = new Ahistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->aid = $this->id;
                $history->action = Yii::t('main-ui', 'Changed value').' "'.Yii::t('main-ui', 'Name').'" '.Yii::t('main-ui', 'from').': <b> ' . $olddata->name . '</b> '.Yii::t('main-ui', 'to').': <b>' . $_POST['Asset']['name'] . '</b>.';
                $history->save(false);
            }
            if (isset($_POST['Asset']['inventory']) && $olddata->inventory !== $_POST['Asset']['inventory']) {
                $history = new Ahistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->aid = $this->id;
                $history->action = Yii::t('main-ui', 'Changed value').' "'.Yii::t('main-ui', 'Inventory #').'" '.Yii::t('main-ui', 'from').': <b> ' . $olddata->inventory . '</b> '.Yii::t('main-ui', 'to').': <b>' . $this->inventory . '</b>.';
                $history->save(false);
            }
            if (isset($_POST['Asset']['description']) && $olddata->description !== $_POST['Asset']['description']) {
                $history = new Ahistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->aid = $this->id;
                $history->action = Yii::t('main-ui', 'Changed value').' "'.Yii::t('main-ui', 'Description').'" '.Yii::t('main-ui', 'from').': <b> ' . $olddata->description . '</b> '.Yii::t('main-ui', 'to').': <b>' . $this->description . '</b>.';
                $history->save(false);
            }
            if (isset($_POST['Asset']['cost']) && $olddata->cost !== $_POST['Asset']['cost']) {
                $history = new Ahistory();
                $history->date = date("d.m.Y H:i");
                $history->user = Yii::app()->user->name;
                $history->aid = $this->id;
                $history->action = Yii::t('main-ui', 'Changed value').' "'.Yii::t('main-ui', 'Cost').'" '.Yii::t('main-ui', 'from').': <b> ' . $olddata->cost . ' '.Yii::t('main-ui','USD'). '</b> '.Yii::t('main-ui', 'to').': <b>' . $this->cost .' '.Yii::t('main-ui','USD'). '</b>.';
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
        foreach ($this->assetFiles as $assetFile) {
            /** @var Files $file */
            $file = $assetFile->file;
            /** @var ProblemFiles $file */
            $assetFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }

    public function afterSave()
    {
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
                $assetFile = new AssetFiles;
                $assetFile->file_id = (int)$file;
                $assetFile->asset_id = $this->id;
                $assetFile->save(false);

            }
        }

        return parent::afterSave();
    }


    public static function getDistinctLocations(){
        $connection = Yii::app()->db;
        $sql = 'SELECT DISTINCT `location` FROM `asset` ORDER BY `location`';
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
        $sql = 'SELECT COUNT(id) as count, SUM(cost) as cost FROM `asset` WHERE' . $where;

        // var_dump($sql);
        $result = $connection->createCommand($sql)->queryAll();
        return $result;
    }

}
