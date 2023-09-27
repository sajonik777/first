<?php

/**
 * This is the model class for table "escalates".
 *
 * The followings are the available columns in table 'escalates':
 * @property integer $id
 * @property integer $service_id
 * @property integer $type_id
 * @property integer $minutes
 * @property integer $manager_id
 * @property integer $group_id
 *
 * The followings are the available model relations:
 * @property Groups $group
 * @property CUsers $manager
 * @property Service $service
 */
class Escalates extends CActiveRecord
{
    const TYPE_REACTION = 1;
    const TYPE_EXECUTION = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'escalates';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['service_id, type_id', 'required'],
            ['service_id, type_id, minutes, manager_id, group_id', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, service_id, type_id, minutes, manager_id, group_id', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'group' => [self::BELONGS_TO, 'Groups', 'group_id'],
            'manager' => [self::BELONGS_TO, 'CUsers', 'manager_id'],
            'service' => [self::BELONGS_TO, 'Service', 'service_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service',
            'type_id' => 'Type',
            'minutes' => 'Minutes',
            'manager_id' => 'Manager',
            'group_id' => 'Group',
        ];
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
        $criteria->compare('service_id', $this->service_id);
        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('minutes', $this->minutes);
        $criteria->compare('manager_id', $this->manager_id);
        $criteria->compare('group_id', $this->group_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['EscalatesPageCount'] ? Yii::app()->session['EscalatesPageCount'] : 30,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Escalates the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
