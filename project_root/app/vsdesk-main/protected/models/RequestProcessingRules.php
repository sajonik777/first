<?php

/**
 * This is the model class for table "request_processing_rules".
 *
 * The followings are the available columns in table 'request_processing_rules':
 * @property integer $id
 * @property string $name
 * @property integer $is_all_match
 * @property integer $is_apply_to_bots
 * @property integer $creator_id
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Cusers $creator
 * @property RequestProcessingRuleConditions[] $conditions
 * @property RequestProcessingRuleActions[] $actions
 */
class RequestProcessingRules extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_processing_rules';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'required'],
            ['is_all_match, is_apply_to_bots, creator_id', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 500],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, is_all_match, is_apply_to_bots, creator_id, created_at', 'safe', 'on' => 'search'],
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
            'creator' => [self::BELONGS_TO, CUsers::class, 'creator_id'],
            'conditions' => [self::HAS_MANY, RequestProcessingRuleConditions::class, 'request_processing_rule_id'],
            'actions' => [self::HAS_MANY, RequestProcessingRuleActions::class, 'request_processing_rule_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'is_all_match' => Yii::t('main-ui', 'Coincidence All/One'),
            'is_apply_to_bots' => Yii::t('main-ui', 'Apply to bots'),
            'creator_id' => Yii::t('main-ui', 'Creator'),
            'created_at' => Yii::t('main-ui', 'Created At'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('is_all_match', $this->is_all_match);
        $criteria->compare('is_apply_to_bots', $this->is_apply_to_bots);
        $criteria->compare('creator_id', $this->creator_id);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['RequestProcessingRulesPageCount'] ? Yii::app()->session['RequestProcessingRulesPageCount'] : 30,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->creator_id = Yii::app()->user->id;
        }

        return parent::beforeSave();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestProcessingRules the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
