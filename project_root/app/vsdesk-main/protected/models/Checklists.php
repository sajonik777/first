<?php

/**
 * This is the model class for table "checklists".
 *
 * The followings are the available columns in table 'checklists':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property ChecklistFields[] $checklistFields
 * @property Service[] $services
 */
class Checklists extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'checklists';
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
            ['name', 'length', 'max' => 250],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name', 'safe', 'on' => 'search'],
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
            'checklistFields' => [self::HAS_MANY, 'ChecklistFields', 'checklist_id'],
            'services' => [self::MANY_MANY, 'Service', 'service_checklists(checklist_id, service_id)'],
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

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['ChecklistsPageCount'] ? Yii::app()->session['ChecklistsPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Checklists the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
