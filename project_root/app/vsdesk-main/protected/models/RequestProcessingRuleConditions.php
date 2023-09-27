<?php

/**
 * This is the model class for table "request_processing_rule_conditions".
 *
 * The followings are the available columns in table 'request_processing_rule_conditions':
 * @property integer $id
 * @property integer $request_processing_rule_id
 * @property string $val
 * @property integer $target
 * @property integer $condition
 *
 * The followings are the available model relations:
 * @property RequestProcessingRules $rule
 */
class RequestProcessingRuleConditions extends CActiveRecord
{
    const TARGET_SENDER = 1;
    const TARGET_SUBJECT = 2;
    const TARGET_CONTENT = 3;

    const TARGETS = [
        self::TARGET_SENDER => 'Отправитель',
        self::TARGET_SUBJECT => 'Тема',
        self::TARGET_CONTENT => 'Содержание',
    ];

    const CONDITION_EQUALS = 1;
    const CONDITION_NOT_EQUALS = 2;
    const CONDITION_CONTAINS = 3;
    const CONDITION_NOT_CONTAINS = 4;

    const CONDITIONS = [
        self::CONDITION_EQUALS => 'Равно',
        self::CONDITION_NOT_EQUALS => 'Не равно',
        self::CONDITION_CONTAINS => 'Содержит',
        self::CONDITION_NOT_CONTAINS => 'Не содержит',
    ];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_processing_rule_conditions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['request_processing_rule_id, val, target, condition', 'required'],
            ['request_processing_rule_id, target, condition', 'numerical', 'integerOnly' => true],
            ['val', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, request_processing_rule_id, val, target, condition', 'safe', 'on' => 'search'],
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
            'rule' => [self::BELONGS_TO, RequestProcessingRules::class, 'request_processing_rule_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_processing_rule_id' => Yii::t('main-ui', 'Request Processing Rule'),
            'val' => Yii::t('main-ui', 'Val'),
            'target' => Yii::t('main-ui', 'Target'),
            'condition' => Yii::t('main-ui', 'Condition'),
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
        $criteria->compare('request_processing_rule_id', $this->request_processing_rule_id);
        $criteria->compare('val', $this->val, true);
        $criteria->compare('target', $this->target);
        $criteria->compare('condition', $this->condition);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['RequestProcessingRuleConditionsPageCount'] ? Yii::app()->session['RequestProcessingRuleConditionsPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestProcessingRuleConditions the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
