<?php

/**
 * This is the model class for table "company_services".
 *
 * The followings are the available columns in table 'company_services':
 * @property integer $company_id
 * @property integer $service_id
 *
 * @property Service $service
 */
class CompanyServices extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'company_services';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_id, service_id', 'required'),
            array('company_id, service_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('company_id, service_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'service' => array(self::HAS_ONE, 'Service', ['id' => 'service_id']),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'company_id' => 'Company',
            'service_id' => 'Service',
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

        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('service_id', $this->service_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['CompanyServicesPageCount'] ? Yii::app()->session['CompanyServicesPageCount'] : 30,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CompanyServices the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}