<?php

/**
 * This is the model class for table "contracts".
 *
 * The followings are the available columns in table 'contracts':
 * @property string $id
 * @property string $number
 * @property string $name
 * @property string $type
 * @property string $date
 * @property string $date_view
 * @property integer $customer_id
 * @property string $customer_name
 * @property integer $company_id
 * @property string $company_name
 * @property string $tildate
 * @property string $tildate_view
 * @property integer $cost
 * @property integer $stopservice
 * @property string $image
 */
class Contracts extends CActiveRecord
{
    private $_files = [];
    public $expired;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'contracts';
    }

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

    public static function getTypes()
    {
        $filter_types = self::model()->findAll();
        foreach ($filter_types as $filter_type) {
            $types[$filter_type->type] = $filter_type->type;
        }
        $filter_types = array_unique($types);
        sort($filter_types);
        return $filter_types;
    }

    public static function getTypes2()
    {
        $filter_types = self::model()->findAll();
        foreach ($filter_types as $filter_type) {
            $types[] = $filter_type->type;
        }
        $filter_types = array_unique($types);
        sort($filter_types);
        return $filter_types;
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
        if (!empty($this->contFiles)) {
            foreach ($this->contFiles as $file) {
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    public static function GetExplode($range)
    {
        $date_range = explode(' - ', $range);
        return $date_range;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, company_id, cost, stopservice', 'numerical', 'integerOnly' => true),
            ['name, number, date_view, tildate_view, customer_name, company_name', 'required'],
            array('number, name, type, customer_name, company_name', 'length', 'max' => 100),
            array('date_view, tildate_view', 'length', 'max' => 20),
            array('date, tildate, image', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, number, name, type, date, date_view, customer_id, customer_name, company_id, company_name, tildate, tildate_view, cost, stopservice, image', 'safe', 'on' => 'search'),
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
            'contractsFiles' => array(self::HAS_MANY, 'ContractsFiles', 'contracts_id'),
            'contFiles' => array(self::MANY_MANY, 'Files', 'contracts_files(contracts_id, file_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'number' => Yii::t('main-ui', 'Contract number'),
            'name' => Yii::t('main-ui', 'Name'),
            'type' => Yii::t('main-ui', 'Type'),
            'date' => Yii::t('main-ui', 'Start of contract'),
            'date_view' => Yii::t('main-ui', 'Start of contract'),
            'customer_id' => Yii::t('main-ui', 'Customer'),
            'customer_name' => Yii::t('main-ui', 'Customer'),
            'company_id' => Yii::t('main-ui', 'Contractor'),
            'company_name' => Yii::t('main-ui', 'Contractor'),
            'tildate' => Yii::t('main-ui', 'Contract termination'),
            'tildate_view' => Yii::t('main-ui', 'Contract termination'),
            'cost' => Yii::t('main-ui', 'Cost'),
            'stopservice' => Yii::t('main-ui', 'Stop creating tickets after contract termination'),
            'image' => 'Image',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type, true);
        //$criteria->compare('date_view',$this->date_view,true);
        if (!empty($this->date_view)) {
            $mdata = self::GetExplode($this->date_view);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('date', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('date_view', $this->date_view, true);
        }
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('customer_name', $this->customer_name, true);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('company_name', $this->company_name, true);
        //$criteria->compare('tildate_view',$this->tildate_view,true);
        if (!empty($this->tildate_view)) {
            $mdata = self::GetExplode($this->tildate_view);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('tildate', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('tildate_view', $this->tildate_view, true);
        }
        $criteria->compare('date', $this->date, true);
        $criteria->compare('tildate', $this->tildate, true);
        $criteria->compare('cost', $this->cost);
        $criteria->compare('stopservice', $this->stopservice);
        $criteria->compare('image', $this->image, true);

        $sort = new CSort();
        $sort->modelClass = 'Contracts';
        $sort->defaultOrder = 'id DESC';
        $sort->attributes = [
            'date_view' => [
                'asc' => 'date ASC',
                'desc' => 'date DESC',
            ],
            'tildate_view' => [
                'asc' => 'tildate ASC',
                'desc' => 'tildate DESC',
            ],
            'number',
            'name',
            'type',
            'customer_name',
            'company_name',
            'cost',

        ];
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int) Yii::app()->session['ContractsPageCount'] ? Yii::app()->session['ContractsPageCount'] : 30,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Contracts the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $contractor = Companies::model()->findByAttributes(array('name' => $this->company_name));
        $customer = Companies::model()->findByAttributes(array('name' => $this->customer_name));
        $this->company_id = $contractor->id;
        $this->customer_id = $customer->id;
        $this->date = date('Y-m-d', strtotime($this->date_view));
        $this->tildate = date('Y-m-d', strtotime($this->tildate_view));

        return parent::beforeSave();
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
                $contractsFile = new ContractsFiles;
                $contractsFile->file_id = (int) $file;
                $contractsFile->contracts_id = $this->id;
                $contractsFile->save(false);

            }
        }

        return parent::afterSave();
    }

    public function afterFind()
    {
        $expiration = strtotime($this->tildate);
        $now = strtotime(date('Y-m-d'));
        if ($now > $expiration) {
            $this->expired = 1;
        }

        return parent::afterFind();
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        // Удаляем связи с файлами
        foreach ($this->contractsFiles as $contractFile) {
            /** @var Files $file */
            $file = $contractFile->file;
            /** @var ProblemFiles $file */
            $contractFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }
}
