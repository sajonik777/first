<?php

/**
 * This is the model class for table "request_escalates".
 *
 * The followings are the available columns in table 'request_escalates':
 * @property integer $request_id
 * @property integer $escalate_id
 *
 * @property Request $request
 * @property Escalates $escalate
 */
class RequestEscalates extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_escalates';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['request_id, escalate_id', 'required'],
            ['request_id, escalate_id', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['request_id, escalate_id', 'safe', 'on' => 'search'],
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
            'request' => [self::BELONGS_TO, 'Request', 'request_id'],
            'escalate' => [self::BELONGS_TO, 'Escalates', 'escalate_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request',
            'escalate_id' => 'Escalate',
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

        $criteria->compare('request_id', $this->request_id);
        $criteria->compare('escalate_id', $this->escalate_id);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['RequestEscalatesPageCount'] ? Yii::app()->session['RequestEscalatesPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestEscalates the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
