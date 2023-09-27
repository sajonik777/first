<?php

/**
 * This is the model class for table "pureport".
 *
 * The followings are the available columns in table 'pureport':
 * @property integer $id
 * @property string $date
 * @property string $assetname
 * @property string $assettype
 * @property string $status
 * @property integer $stnew
 * @property integer $stworkaround
 * @property integer $stsolved
 * @property string $company
 */
class Psreport extends CActiveRecord
{
    public $company;
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Pureport the static model class
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
        return 'psreport';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('stnew, stworkaround, stsolved', 'numerical', 'integerOnly' => true),
            array('date, servicename, downtime, availability, pavailability, year, sdate, edate', 'length', 'max' => 50),
            array('status, company', 'length', 'max' => 70),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, date, servicename, stnew, stworkaround, stsolved', 'safe', 'on' => 'search'),
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
            'date' => 'Date',
            'servicename' => Yii::t('main-ui', 'Service'),
            'stnew' => '<span class="label label-success">Зарегистрирована</span>',
            'stworkaround' => '<span class="label label-info">Обходное решение</span>',
            'stsolved' => '<span class="label label-default">Решена</span>',
            'downtime' => Yii::t('main-ui', 'Downtime (hh:mm)'),
            'availability' => Yii::t('main-ui', 'Availability %'),
            'pavailability' => Yii::t('main-ui', 'Availability % (SLA)'),
            'company' => Yii::t('main-ui', 'Company'),
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
        $criteria->compare('date', $this->date, true);
        $criteria->compare('servicename', $this->servicename, true);
        $criteria->compare('stnew', $this->stnew);
        $criteria->compare('stworkaround', $this->stworkaround);
        $criteria->compare('stsolved', $this->stsolved);
        $criteria->compare('downtime', $this->downtime);
        $criteria->compare('availability', $this->availability);
        $criteria->compare('pavailability', $this->pavailability);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 15),
        ));
    }
}
