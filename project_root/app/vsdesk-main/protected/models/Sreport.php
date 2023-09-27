<?php

/**
 * This is the model class for table "sreport".
 *
 * The followings are the available columns in table 'sreport':
 * @property integer $id
 * @property string $date
 * @property string $servicename
 * @property integer $stnew
 * @property integer $stopen
 * @property integer $stclosed
 * @property integer $reactissue
 * @property integer $solveissue
 */
class Sreport extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sreport the static model class
     */
    public static
    function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public
    function tableName()
    {
        return 'sreport';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public
    function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('stnew, stopen, stclosed, reactissue, solveissue', 'numerical', 'integerOnly' => true),
            array('date, servicename', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, date, servicename, stnew, stopen, stclosed, reactissue, solveissue', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public
    function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public
    function attributeLabels()
    {
        $stnew = Status::model()->findByAttributes(array('close' => 1));
        $stopen = Status::model()->findByAttributes(array('close' => 2));
        $stclosed = Status::model()->findByAttributes(array('close' => 3));
        $reactissue = Status::model()->findByAttributes(array('close' => 4));
        $solveissue = Status::model()->findByAttributes(array('close' => 5));
        $canceled = Status::model()->findByAttributes(array('close' => 6));
        return array(
            'id' => 'ID',
            'servicename' => Yii::t('main-ui', 'Service'),
            'date' => 'Date',
            'stnew' => $stnew->label,
            'stopen' => $stopen->label,
            'stclosed' => $stclosed->label,
            'reactissue' => $reactissue->label,
            'solveissue' => $solveissue->label,
            'canceled' => $canceled->label,
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public
    function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('servicename', $this->servicename, true);
        $criteria->compare('stnew', $this->stnew);
        $criteria->compare('stopen', $this->stopen);
        $criteria->compare('stclosed', $this->stclosed);
        $criteria->compare('reactissue', $this->reactissue);
        $criteria->compare('solveissue', $this->solveissue);
        $criteria->compare('canceled', $this->canceled);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 15),
        ));
    }
}