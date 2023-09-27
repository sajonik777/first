<?php

/**
 * This is the model class for table "request_checklist_fields".
 *
 * The followings are the available columns in table 'request_checklist_fields':
 * @property integer $id
 * @property integer $request_id
 * @property integer $checklist_field_id
 * @property integer $checked
 * @property integer $sorting
 * @property integer $checked_user_id
 * @property string $checked_time
 *
 * The followings are the available model relations:
 * @property Cusers $checkedUser
 * @property ChecklistFields $checklistField
 * @property Request $request
 */
class RequestChecklistFields extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request_checklist_fields';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['request_id, checklist_field_id', 'required'],
            ['request_id, checklist_field_id, checked, sorting, checked_user_id', 'numerical', 'integerOnly' => true],
            ['checked_time', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            [
                'id, request_id, checklist_field_id, checked, sorting, checked_user_id, checked_time',
                'safe',
                'on' => 'search'
            ],
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
            'checkedUser' => [self::BELONGS_TO, 'Cusers', 'checked_user_id'],
            'checklistField' => [self::BELONGS_TO, 'ChecklistFields', 'checklist_field_id'],
            'request' => [self::BELONGS_TO, 'Request', 'request_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request',
            'checklist_field_id' => 'Checklist Field',
            'checked' => 'Checked',
            'sorting' => 'Sorting',
            'checked_user_id' => 'Checked User',
            'checked_time' => 'Checked Time',
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
        $criteria->compare('request_id', $this->request_id);
        $criteria->compare('checklist_field_id', $this->checklist_field_id);
        $criteria->compare('checked', $this->checked);
        $criteria->compare('sorting', $this->sorting);
        $criteria->compare('checked_user_id', $this->checked_user_id);
        $criteria->compare('checked_time', $this->checked_time, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['RequestChecklistFieldsPageCount'] ? Yii::app()->session['RequestChecklistFieldsPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestChecklistFields the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
