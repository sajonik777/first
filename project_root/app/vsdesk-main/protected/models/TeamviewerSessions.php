<?php

/**
 * This is the model class for table "teamviewer_sessions".
 *
 * The followings are the available columns in table 'teamviewer_sessions':
 * @property integer $id
 * @property integer $request_id
 * @property string $code
 * @property string $supporter_link
 * @property string $end_customer_link
 * @property string $valid_until
 *
 * The followings are the available model relations:
 * @property Request $request
 */
class TeamviewerSessions extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'teamviewer_sessions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('request_id, code, supporter_link, end_customer_link, valid_until', 'required'),
            array('request_id', 'numerical', 'integerOnly' => true),
            array('code, valid_until', 'length', 'max' => 32),
            array('supporter_link, end_customer_link', 'length', 'max' => 64),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, request_id, code, supporter_link, end_customer_link, valid_until', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'request' => array(self::BELONGS_TO, 'Request', 'request_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'request_id' => 'Request',
            'code' => 'Code',
            'supporter_link' => 'Supporter Link',
            'end_customer_link' => 'End Customer Link',
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
        $criteria->compare('request_id', $this->request_id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('supporter_link', $this->supporter_link, true);
        $criteria->compare('end_customer_link', $this->end_customer_link, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['TeamviewerSessionsPageCount'] ? Yii::app()->session['TeamviewerSessionsPageCount'] : 30,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TeamviewerSessions the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
