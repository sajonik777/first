<?php

/**
 * This is the model class for table "history".
 *
 * The followings are the available columns in table 'history':
 * @property integer $id
 * @property integer $zid
 * @property string $datetime
 * @property string $action_name
 * @property string $action_result
 *
 * The followings are the available model relations:
 * @property Zayavki $z
 */
class History extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return History the static model class
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
        return 'history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('zid', 'numerical', 'integerOnly' => true),
            array('datetime, cusers_id', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, zid, datetime, cusers_id, action', 'safe', 'on' => 'search'),
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
            'z' => array(self::BELONGS_TO, 'Request', 'zid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'zid' => Yii::t('main-ui', '#'),
            'cusers_id' => Yii::t('main-ui', 'Username'),
            'datetime' => Yii::t('main-ui', 'Changed'),
            'action' => Yii::t('main-ui', 'Action'),

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
        $criteria->compare('zid', $this->zid);
        $criteria->compare('cusers_id', $this->cusers_id);
        $criteria->compare('datetime', $this->datetime, true);
        $criteria->compare('action', $this->action, true);

//        if ($_SERVER['REQUEST_URI'] !== '/api/history/') {
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/requests') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
            ]);
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);
    }
}