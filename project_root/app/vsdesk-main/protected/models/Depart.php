<?php

/**
 * This is the model class for table "depart".
 *
 * The followings are the available columns in table 'depart':
 * @property integer $id
 * @property string $name
 * @property string $company
 *
 * @property DepartServices[] $departServices
 * @property Service[] $services
 */
class Depart extends CActiveRecord
{
    public $manager;
    public $manager_id;

    public static function all()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `depart` ORDER BY name';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['name']] = $one['name'];
        }
        return $array;
    }

    public static function call($company)
    {
        $connection = Yii::app()->db;
        $sql = "SELECT * FROM `depart` WHERE `company` = '" . $company . "' ORDER BY name";
        $models = $connection->createCommand($sql)->queryAll();
        $array = array(''=>'--- Выберите подразделение ---');
        foreach ($models as $one) {
            $array[$one['name']] = $one['name'];
        }
        return $array;
    }

    /**
     * Возвращает закреплённые сервисы.
     *
     * @param null $category_id
     * @return array
     */
    public function getServicesArray($category_id = null)
    {
        if ($category_id) {
            $ret = [];
            foreach ($this->services as $service) {
                if ($service->category_id == $category_id) {
                    $ret[$service->id] = $service->name;
                }
            }
            return $ret;
        } else {
            return CHtml::listData($this->services, 'id', 'name');
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Depart the static model class
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
        return 'depart';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, company, manager', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, company, manager, manager_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'departServices' => array(self::HAS_MANY, 'DepartServices', 'depart_id'),
            'services' => array(self::MANY_MANY, 'Service', 'depart_services(depart_id, service_id)'),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'company' => Yii::t('main-ui', 'Company'),
            'manager' => Yii::t('main-ui', 'CIO'),
            'services' => Yii::t('main-ui', 'Services'),
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
        $criteria->compare('company', $this->company, true);
        $criteria->compare('manager', $this->manager, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['deptPageCount'] ? Yii::app()->session['deptPageCount'] : 30,
            ),
            'sort' => array(
                'defaultOrder' => 'name ASC',
            ),
        ));
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (!empty($this->departServices)) {
            foreach ($this->departServices as $departServices) {
                /** @var DepartServices $departServices */
                $departServices->delete();
            }
        }
        return parent::beforeDelete();
    }

    public function beforeSave()
    {
        if (!empty($this->manager)) {
            $manager = CUsers::model()->findByAttributes(['Username' => $this->manager]);
            $this->manager_id = $manager->id;

        }
        return parent::beforeDelete();
    }

    public function afterFind()
    {
        $is_console = 'cli' === PHP_SAPI; //if is console app return bool
        if (!$is_console) {
            if (!empty($this->manager AND Yii::app()->getRequest()->getPathInfo() == 'depart/index')) {
                $manager = CUsers::model()->findByAttributes(['Username' => $this->manager]);
                $this->manager = $manager->fullname;
            }
        }
        return parent::beforeDelete();
    }
}
