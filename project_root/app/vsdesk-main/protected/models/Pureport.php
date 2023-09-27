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
 */
class Pureport extends CActiveRecord
{
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
        return 'pureport';
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
            array('date, assetname, assettype', 'length', 'max' => 50),
            array('status, slabel', 'length', 'max' => 70),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, date, assetname, assettype, status, stnew, stworkaround, stsolved', 'safe', 'on' => 'search'),
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
            'assetname' => Yii::t('main-ui', 'Name'),
            'assettype' => Yii::t('main-ui', 'Type'),
            'status' => Yii::t('main-ui', 'Status'),
            'slabel' => Yii::t('main-ui', 'Status'),
            'stnew' => '<span class="label label-success">Зарегистрирована</span>',
            'stworkaround' => '<span class="label label-info">Обходное решение</span>',
            'stsolved' => '<span class="label label-default">Решена</span>',
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
        $criteria->compare('assetname', $this->assetname, true);
        $criteria->compare('assettype', $this->assettype, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('slabel', $this->slabel, true);
        $criteria->compare('stnew', $this->stnew);
        $criteria->compare('stworkaround', $this->stworkaround);
        $criteria->compare('stsolved', $this->stsolved);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
