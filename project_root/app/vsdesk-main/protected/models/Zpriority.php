<?php

/**
 * This is the model class for table "zpriority".
 *
 * The followings are the available columns in table 'zpriority':
 * @property integer $id
 * @property string $name
 * @property string $cost
 * @property string $rcost
 * @property string $scost
 */
class Zpriority extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Zpriority the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function all()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `zpriority`';
        $models = $connection->createCommand($sql)->queryAll();
        $array = [];
        foreach ($models as $one) {
            $array[$one['name']] = $one['name'];
        }

        return $array;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'zpriority';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, rcost, scost', 'required'],
            ['name, cost, rcost, scost', 'length', 'max' => 50],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, cost, rcost, scost', 'safe', 'on' => 'search'],
            ['name, cost, rcost, scost', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main-ui', '#'),
            'name' => Yii::t('main-ui', 'Name'),
            'cost' => Yii::t('main-ui', 'Value'),
            'rcost' => Yii::t('main-ui', 'Reaction value'),
            'scost' => Yii::t('main-ui', 'Salvation value'),
        ];
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('cost', $this->cost, true);
        $criteria->compare('cost', $this->rcost, true);
        $criteria->compare('cost', $this->scost, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
