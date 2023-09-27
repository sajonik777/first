<?php

/**
 * This is the model class for table "calls".
 *
 * The followings are the available columns in table 'calls':
 * @property string $id
 * @property integer $rid
 * @property string $uniqid
 * @property string $duniqid
 * @property string $date
 * @property string $edate
 * @property string $dialer
 * @property string $dialer_name
 * @property string $dr_number
 * @property string $dialed
 * @property string $dialed_name
 * @property string $dd_number
 * @property string $status
 * @property string $slabel
 * @property integer $shown
 */

class Calls extends CActiveRecord
{
    public $dr_company;
    public $adate;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'calls';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['rid, shown', 'numerical', 'integerOnly' => true],
            ['uniqid, duniqid', 'length', 'max' => 50],
            [
                'dialer, dialer_name, dr_number, dr_company, dialed, dialed_name, dd_number, status, slabel',
                'length',
                'max' => 200
            ],
            ['date, edate, adate', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            [
                'id, rid, uniqid, duniqid, date, edate, adate, dialer, dialer_name, dr_number, dialed, dialed_name, dd_number, status, slabel, shown',
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
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'rid' => 'Rid',
            'uniqid' => 'Uniqid',
            'duniqid' => 'Duniqid',
            'date' => 'Время звонка',
            'adate' => 'Трубка поднята',
            'edate' => 'Окончание звонка',
            'dialer' => 'Dialer',
            'dialer_name' => 'Кто звонил',
            'dr_number' => 'Номер вызывающего',
            'dr_company' => 'Компания вызывающего',
            'dialed' => 'Dialed',
            'dialed_name' => 'Кому звонили',
            'dd_number' => 'Номер получателя',
            'status' => 'Статус',
            'slabel' => 'Статус',
            'shown' => 'Shown',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('rid', $this->rid);
        $criteria->compare('uniqid', $this->uniqid, true);
        $criteria->compare('duniqid', $this->duniqid, true);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('edate', $this->edate, true);
        if (Yii::app()->user->checkAccess('systemManager')) {
            $criteria->compare('dialed', Yii::app()->user->name, true);
        } elseif (Yii::app()->user->checkAccess('systemAdmin')) {
            $criteria->compare('dialed', $this->dialed, true);

        }
        $criteria->compare('dialer_name', $this->dialer_name, true);
        $criteria->compare('dr_number', $this->dr_number, true);
        $criteria->compare('dr_company', $this->dr_company, true);
        $criteria->compare('dialer', $this->dialer, true);
        $criteria->compare('dialed_name', $this->dialed_name, true);
        $criteria->compare('dd_number', $this->dd_number, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('slabel', $this->slabel, true);
        $criteria->compare('shown', $this->shown);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 'date DESC',
            ],
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['CallsPageCount'] ? Yii::app()->session['CallsPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Calls the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
