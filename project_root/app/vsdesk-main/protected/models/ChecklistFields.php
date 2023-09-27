<?php

/**
 * This is the model class for table "checklist_fields".
 *
 * The followings are the available columns in table 'checklist_fields':
 * @property integer $id
 * @property integer $checklist_id
 * @property string $name
 * @property integer $sorting
 *
 * The followings are the available model relations:
 * @property Checklists $checklist
 * @property RequestChecklistFields[] $requestChecklistFields
 */
class ChecklistFields extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'checklist_fields';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['checklist_id, name', 'required'],
            ['checklist_id, sorting', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 64],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, checklist_id, name, sorting', 'safe', 'on' => 'search'],
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
            'checklist' => [self::BELONGS_TO, 'Checklists', 'checklist_id'],
            'requestChecklistFields' => [self::HAS_MANY, 'RequestChecklistFields', 'checklist_field_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'checklist_id' => 'Checklist',
            'name' => Yii::t('main-ui', 'Name'),
            'sorting' => 'Sorting',
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
        $criteria->compare('checklist_id', $this->checklist_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('sorting', $this->sorting);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['ChecklistFieldsPageCount'] ? Yii::app()->session['ChecklistFieldsPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ChecklistFields the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
