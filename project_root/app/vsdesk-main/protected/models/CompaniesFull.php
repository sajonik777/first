<?php

/**
 * This is the model class for table "companies".
 *
 * The followings are the available columns in table 'companies':
 * @property integer $id
 * @property string $name
 * @property string $director
 * @property string $uraddress
 * @property string $faddress
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $bik
 * @property string $korschet
 * @property string $schet
 * @property string $tarif
 * @property string $payday
 * @property integer $user_id
 * @property string $manager
 *
 * The followings are the available model relations:
 * @property CUsers $user
 * @property CompanyServices[] $companyServices
 * @property Service[] $services
 */
class CompaniesFull extends CActiveRecord
{
    public $domains;

    /**
     * @var array
     */
    private $_attributes = [];

     /**
     * @var array
     */
    public $fields = [];

     /**
     * @var array
     */
    public $field_keys = [];

    /**
     * @var array
     */
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
        if (!empty($this->compFiles)) {
            foreach ($this->compFiles as $file) {
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (isset($this->$name)) {
            parent::__set($name, $value);
        } else {
            $this->_attributes[$name] = $value;
        }
    }

    /**
     * @return array
     */
    public function getFullAttributes()
    {
        return $this->_fullAttributes;
    }

    public function setFullAttributes($value)
    {
        return $this->_fullAttributes = $value;
    }

    public static function all()
    {
        $criteria = new CDbCriteria(array('order' => 'name ASC'));
        $models = self::model()->findAll($criteria);
        $array = array();
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    public static function eall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[] = $one->name;
        }
        return $array;
    }

    /**
     * Возвращает закреплённые сервисы.
     * @return array
     */
    public function getServicesArray()
    {
        return CHtml::listData($this->services, 'id', 'name');
    }


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'companies';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $field_keys = ' ';
        if (!empty($this->field_keys)) {
            foreach ($this->field_keys as $value) {
                $field_keys .= ', ' . $value;
            }
        }
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, director, uraddress, faddress', 'required'),
            array('name', 'filter', 'filter'=>'trim'),
            array('uraddress, faddress', 'length', 'max' => 250),
            array('name, director, contact_name', 'length', 'max' => 100),
            array('phone, email, manager', 'length', 'max' => 50),
            array('inn, kpp, ogrn, bik, korschet, schet', 'numerical', 'integerOnly' => true),
            array('inn', 'length', 'max' => 12),
            array('inn', 'length', 'min' => 10),
            array('kpp', 'length', 'max' => 9),
            array('kpp', 'length', 'min' => 9),
            array('ogrn', 'length', 'max' => 15),
            array('ogrn', 'length', 'min' => 13),
            array('bik', 'length', 'max' => 9),
            array('bik', 'length', 'min' => 9),
            array('korschet', 'length', 'max' => 20),
            array('korschet', 'length', 'min' => 20),
            array('schet', 'length', 'max' => 20),
            array('schet', 'length', 'min' => 20),
            array('name', 'unique', 'message' => 'Такая компания уже существует'),
            ['image', 'length', 'max' => 250],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array(
                'id, name, director, uraddress, faddress, inn, kpp, ogrn, bik, korschet, schet, domains' . $field_keys,
                'safe',
                'on' => 'search'
            ),
            array(
                'name, director, uraddress, faddress, inn, kpp, ogrn, bik, korschet, schet,add1, add2, contact_name, phone, email, domains',
                'filter',
                'filter' => array($obj = new CHtmlPurifier(), 'purify')
            ),
        );
    }

    public function uniqueIdAndName($attribute, $params = array())
    {
        if (!$this->hasErrors()) {
            $params['criteria'] = array(
                'condition' => 'id=:id',
                'params' => array(':id' => $this->id),
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
        return [
            'companyServices' => array(self::HAS_MANY, 'CompanyServices', 'company_id'),
            'services' => array(self::MANY_MANY, 'Service', 'company_services(company_id, service_id)'),
            'flds' => array(self::HAS_MANY, 'CompanyFields', 'rid'),
            'companiesFiles' => array(self::HAS_MANY, 'CompaniesFiles', 'companies_id'),
            'compFiles' => array(self::MANY_MANY, 'Files', 'companies_files(companies_id, file_id)'),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'director' => Yii::t('main-ui', 'CIOOIT'),
            'uraddress' => Yii::t('main-ui', 'Legal address'),
            'faddress' => Yii::t('main-ui', 'Actual address'),
            'domains' => Yii::t('main-ui', 'Mail address of local system Servicedesk'),
            // 'domains' => Yii::t('main-ui', 'Mail domains (<strong>enter comma-separated without @</strong>)'),
            'inn' => Yii::t('main-ui', 'INN (Russia only)'),
            'kpp' => Yii::t('main-ui', 'KPP (Russia only)'),
            'ogrn' => Yii::t('main-ui', 'ORGN (Russia only)'),
            'bik' => Yii::t('main-ui', 'BIK (Russia only)'),
            'korschet' => Yii::t('main-ui', 'Cor. account (Russia only)'),
            'schet' => Yii::t('main-ui', 'Account (Russia only)'),
            'phone' => Yii::t('main-ui', 'Phone'),
            'contact_name' => Yii::t('main-ui', 'Contact name'),
            'manager' => Yii::t('main-ui', 'Manager of company'),
            'add1' => Yii::t('main-ui', 'Additional field'),
            'add2' => Yii::t('main-ui', 'Additional field2'),

            'services' => Yii::t('main-ui', 'Services'),
        );
    }

    public function importLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'director' => Yii::t('main-ui', 'CIOOIT'),
            'uraddress' => Yii::t('main-ui', 'Legal address'),
            'faddress' => Yii::t('main-ui', 'Actual address'),
            'inn' => Yii::t('main-ui', 'INN (Russia only)'),
            'kpp' => Yii::t('main-ui', 'KPP (Russia only)'),
            'ogrn' => Yii::t('main-ui', 'ORGN (Russia only)'),
            'bik' => Yii::t('main-ui', 'BIK (Russia only)'),
            'korschet' => Yii::t('main-ui', 'Cor. account (Russia only)'),
            'schet' => Yii::t('main-ui', 'Account (Russia only)'),
            'phone' => Yii::t('main-ui', 'Phone'),
            'email' => Yii::t('main-ui', 'Email'),
            'contact_name' => Yii::t('main-ui', 'Contact name'),
            'manager' => Yii::t('main-ui', 'Manager of company'),
            'add1' => Yii::t('main-ui', 'Additional field'),
            'add2' => Yii::t('main-ui', 'Additional field2'),
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
        $rids = [];
        $rid_key_for_clean_result = false;
        foreach ($this->field_keys as $id => $key) {
            if (isset($_GET['CompaniesFull'][$key]) and !empty($_GET['CompaniesFull'][$key])) {
                $rid = Yii::app()->db->createCommand([
                    'select' => ['rid'],
                    'from' => 'company_fields',
                    'where' => "fid = {$id} AND value LIKE '%{$_GET['CompaniesFull'][$key]}%'",
                ])->queryAll();
                $rids = array_merge($rids, $rid);
                $rid_key_for_clean_result = true;
            }
        }
        $requests_ids = [];
        if (!empty($rids)) {
            foreach ($rids as $rid) {
                $requests_ids[] = $rid['rid'];
            }
        }

        $criteria = new CDbCriteria;

        if (!empty($requests_ids) && $rid_key_for_clean_result) {
            $criteria->addInCondition('id', $requests_ids);
        } elseif (empty($requests_ids) && $rid_key_for_clean_result) {
            $criteria->addInCondition('id', $requests_ids);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('director', $this->director, true);
        $criteria->compare('uraddress', $this->uraddress, true);
        $criteria->compare('faddress', $this->faddress, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('inn', $this->inn, true);
        $criteria->compare('kpp', $this->kpp, true);
        $criteria->compare('ogrn', $this->ogrn, true);
        $criteria->compare('bik', $this->bik, true);
        $criteria->compare('korschet', $this->korschet, true);
        $criteria->compare('schet', $this->schet, true);
        $criteria->compare('add1', $this->add1, true);
        $criteria->compare('add2', $this->add2, true);
        $criteria->compare('contact_name', $this->contact_name, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
            ),
            'pagination' => array('pageSize' => (int)Yii::app()->session['compPageCount'] ? Yii::app()->session['compPageCount'] : 30,),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Companies the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->preLoadFields();
    }

    /**
     *
     */
    public function preLoadFields()
    {
        $fieldsets_fields = Yii::app()->db->createCommand('SELECT id, name FROM company_fieldset')->queryAll();
        foreach ($fieldsets_fields as $field) {
            $this->__set('ff_id_' . $field['id'], null);
            $this->field_keys[$field['id']] = 'ff_id_' . $field['id'];
        }
    }

    /**
     * @return bool
     */
/*    public function beforeSave()
    {
        if (!$this->isNewRecord) {
            $oldname = self::model()->findByPk($this->id);
            $users = CUsers::model()->findAllByAttributes(array('company' => $oldname->name));
            //$units = Cunits::model()->findAllByAttributes(array('company' => $oldname->name));
            foreach ($users as $item) {
                CUsers::model()->updateByPk($item->id, array('company' => $this->name));
            }
            foreach ($units as $item) {
                CUnits::model()->updateByPk($item->id, array('company' => $this->name));
            }
        }

        return parent::beforeSave();
    }*/

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
                $companiesFile = new companiesFiles;
                $companiesFile->file_id = (int)$file;
                $companiesFile->companies_id = $this->id;
                $companiesFile->save(false);

            }
        }

        //Дополнительные поля
        if ($this->isNewRecord or empty($this->flds)) {
            $criteria = new CDbCriteria(array('order'=>'sid ASC'));
            $fields = CompanyFieldset::model()->findAll($criteria);
            foreach ($fields as $field) {
                if (isset($_POST['Companies'][$field->id])) {
                    $fieldset = new CompanyFields();
                    $fieldset->rid = $this->id;
                    $fieldset->fid = $field->id;
                    $fieldset->name = $field->name;
                    $fieldset->type = $field->type;
                    $fieldset->value = $_POST['Companies'][$field->id];
                    $fieldset->save(false);
                }
            }
        } else {
            if (!empty($this->flds)) {
                $fields = CompanyFields::model()->findAllByAttributes(array('rid' => $this->id));
                foreach ($fields as $field) {
                    if (isset($_POST['Companies'][$field->id])) {
                        CompanyFields::model()->updateByPk($field->id, array('value' => $_POST['Companies'][$field->id]));
                    }
                }
            }
        }

        return parent::afterSave();
    }


    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!empty($this->companyServices)) {
            foreach ($this->companyServices as $companyService) {
                /** @var CompanyServices $companyService */
                $companyService->delete();
            }
        }
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

    public function afterFind()
    {
        $is_console = PHP_SAPI == 'cli'; //if is console app return bool
        if (!$is_console) {
            $manager = CUsers::model()->findByAttributes(['Username'=>$this->manager]);
            if(isset($manager) AND $_SERVER['REQUEST_URI'] == '/companies'){
                $this->manager = $manager->fullname;
            }
        }
        if (isset($this->flds) and !empty($this->flds)) {
            foreach ($this->flds as $fld) {
                /** @var RequestFields $fld */
                if($fld->type == 'toggle'){
                    $value = $fld->value == 1 ? 'Да' : 'Нет';
                } else {
                    $value = $fld->value;
                }
                $this->fields[$fld->name] = $value;
                $this->__set('ff_id_' . $fld->fid, $value);
                $this->field_keys[$fld->fid] = 'ff_id_' . $fld->fid;
            }
        }

        return parent::afterFind();
    }
}