<?php

/**
 * This is the model class for table "alerts".
 *
 * The followings are the available columns in table 'alerts':
 * @property integer $id
 * @property string $user
 * @property string $message
 */
class Alerts extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Alerts the static model class
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
        return 'alerts';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shown', 'numerical', 'integerOnly' => true),
            array('user, name', 'length', 'max' => 70),
            array('message', 'length', 'max' => 700),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user, message', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user' => 'User',
            'message' => 'Message',
            'shown' => 'shown',
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
        $criteria->compare('user', $this->user, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('shown', $this->shown, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
