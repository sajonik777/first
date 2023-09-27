<?php

/**
 * This is the model class for table "streets".
 *
 * The followings are the available columns in table 'streets':
 * @property integer $id
 * @property string $name
 * @property integer $cid
 */
class Streets extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Streets the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static
    function all()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `streets` ORDER BY name';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['name']] = $one['name'];
        }
        return $array;
    }

    public static
    function all_id()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `streets` ORDER BY name';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['id']] = $one['name'];
        }
        return $array;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'streets';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'length', 'max' => 70),
            ['cid', 'numerical', 'integerOnly' => true],
            [
                'id, cid, name',
                'safe'
            ],

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, cid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'city' => array(self::BELONGS_TO, 'Cities', 'cid'),
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
            'cid' => Yii::t('main-ui', 'City'),
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
        $criteria->compare('cid', $this->cid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
