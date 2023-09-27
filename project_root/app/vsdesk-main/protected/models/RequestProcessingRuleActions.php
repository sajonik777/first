<?php

/**
 * This is the model class for table "request_processing_rule_actions".
 *
 * The followings are the available columns in table 'request_processing_rule_actions':
 * @property integer $id
 * @property integer $request_processing_rule_id
 * @property integer $target
 * @property string $val
 *
 * The followings are the available model relations:
 * @property RequestProcessingRules $requestProcessingRule
 */
class RequestProcessingRuleActions extends CActiveRecord
{
    const TARGET_STATUS = 1;
    const TARGET_PRIORITY = 2;
    const TARGET_CATEGORY = 3;
    const TARGET_SERVICE = 4;
    const TARGET_COMPANY = 5;
    const TARGET_DEPARTS = 6;
    const TARGET_MANAGER = 7;
    const TARGET_GROUP = 8;

    const TARGETS = [
        self::TARGET_STATUS => 'Статус',
        self::TARGET_PRIORITY => 'Приоритет',
        self::TARGET_CATEGORY => 'Категория',
        self::TARGET_SERVICE => 'Сервис',
        self::TARGET_COMPANY => 'Компания',
        self::TARGET_DEPARTS => 'Подразделение',
        self::TARGET_MANAGER => 'Исполнитель',
        self::TARGET_GROUP => 'Группа исполнителей',
    ];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_processing_rule_actions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['request_processing_rule_id, target, val', 'required'],
            ['request_processing_rule_id, target', 'numerical', 'integerOnly' => true],
            ['val', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, request_processing_rule_id, target, val', 'safe', 'on' => 'search'],
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
            'target' => Yii::t('main-ui', 'Target'),
            'val' => Yii::t('main-ui', 'Val'),
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
        $criteria->compare('target', $this->target);
        $criteria->compare('val', $this->val, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['RequestProcessingRuleActionsPageCount'] ? Yii::app()->session['RequestProcessingRuleActionsPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestProcessingRuleActions the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
