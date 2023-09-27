<?php

/**
 * This is the model class for table "sureport".
 *
 * The followings are the available columns in table 'sureport':
 * @property integer $id
 * @property string $dept
 * @property string $type
 * @property integer $count
 * @property string $summary
 */
class Sactivesreport extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Sureport the static model class
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
        return 'sactivesreport';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('dept, type, count, summary', 'required'),
            array('count', 'numerical', 'integerOnly' => true),
            array('dept, type', 'length', 'max' => 100),
            array('summary', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, dept, type, count, summary', 'safe', 'on' => 'search'),
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
            'dept' => Yii::t('main-ui', 'Location'),
            'type' => Yii::t('main-ui', 'Asset types'),
            'count' => Yii::t('main-ui', 'Count'),
            'summary' => Yii::t('main-ui', 'Summary'),
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
        $criteria->compare('dept', $this->dept, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('count', $this->count);
        $criteria->compare('summary', $this->summary, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 100),
        ));
    }
}
