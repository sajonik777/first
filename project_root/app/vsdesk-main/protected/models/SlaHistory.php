<?php

/**
 * This is the model class for table "sla_history".
 *
 * The followings are the available columns in table 'sla_history':
 * @property integer $id
 * @property integer $sid
 * @property string $date
 * @property integer $user
 * @property string $username
 * @property string $action
 */
class SlaHistory extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ServiceHistory the static model class
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
        return 'sla_history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('date, user_name', 'length', 'max' => 50),
            array('sid, user', 'numerical', 'integerOnly' => true),
            array('action', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sid, date, user, user_name, action', 'safe', 'on' => 'search'),
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
            'sla_history_user_fk' => array(self::BELONGS_TO, 'CUsers', 'user'),
            'sla_history_sla_fk' => array(self::BELONGS_TO, 'Sla', 'sid'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'date' => Yii::t('main-ui', 'Created'),
            'user' => Yii::t('main-ui', 'User'),
            'user_name' => Yii::t('main-ui', 'Username'),
            'action' => Yii::t('main-ui', 'Action'),
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
        $criteria->compare('sid', $this->sid);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('user', $this->user, true);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('action', $this->action, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
