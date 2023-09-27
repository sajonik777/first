<?php

/**
 * This is the model class for table "cron_req".
 *
 * The followings are the available columns in table 'cron_req':
 * @property integer $id
 * @property integer $service_id
 * @property string $CUsers_id
 * @property string $Status
 * @property string $ZayavCategory_id
 * @property string $Priority
 * @property string $Name
 * @property string $Content
 * @property string $watchers
 * @property string $cunits
 * @property string $Date
 * @property string $Date_end
 * @property integer $repeats
 * @property integer $enabled
 * @property integer $sla
 */
class CronReq extends CActiveRecord
{

    public static $allRepeats = [0 => 'Не повторять', 1 => 'Каждый день', 5 => 'Раз в 2 дня', 6 => 'Раз в 3 дня', 7 => 'Раз в 4 дня', 8 => 'Раз в 5 дней', 9 => 'Раз в 6 дней', 2 => 'Раз в неделю', 10 => 'Раз в 2 недели', 11 => 'Раз в 3 недели', 3 => 'Раз в месяц', 12 => 'Раз в 2 месяца', 13 => 'Раз в 3 месяца', 14 => 'Раз в 4 месяца', 15 => 'Раз в 5 месяцев', 16 => 'Раз в 6 месяцев', 4 => 'Раз в год'];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cron_req';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('service_id, CUsers_id, Status, ZayavCategory_id, Priority, Name, Content, Date', 'required'),
            array('service_id, repeats, enabled, sla', 'numerical', 'integerOnly' => true),
            array('CUsers_id, Status, ZayavCategory_id', 'length', 'max' => 32),
            array('Priority, color, Date_end', 'length', 'max' => 50),
            array('Name', 'length', 'max' => 100),
            array('Content, enabled, watchers, cunits, color, fields, sla, service_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, service_id, CUsers_id, Status, ZayavCategory_id, Priority, Name, Content, watchers, cunits, Date, Date_end, repeats, enabled, fields, sla', 'safe', 'on' => 'search'),
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
            /*'history' => array(self::HAS_MANY, 'History', 'zid'),
            'comms' => array(self::HAS_MANY, 'Comments', 'rid'),
            'flds' => array(self::HAS_MANY, 'RequestFields', 'rid'),
            'groups_rl' => array(self::BELONGS_TO, 'Groups', 'groups_id'),*/
            'service' => array(self::BELONGS_TO, 'Service', 'service_id'),
        );

    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('main-ui', '#'),
            'service_id' => Yii::t('main-ui', 'Service name'),
            'CUsers_id' => Yii::t('main-ui', 'User'),
            'Status' => Yii::t('main-ui', 'Status'),
            'ZayavCategory_id' => Yii::t('main-ui', 'Category'),
            'Priority' => Yii::t('main-ui', 'Priority'),
            'Name' => Yii::t('main-ui', 'Name'),
            'Sla' => Yii::t('main-ui', 'Sla'),
            'Content' => Yii::t('main-ui', 'Content'),
            'watchers' => Yii::t('main-ui', 'Observers'),
            'cunits' => Yii::t('main-ui', 'Configuration units'),
            'Date' => Yii::t('main-ui', 'Date start'),
            'Date_end' => Yii::t('main-ui', 'Date end'),
            'repeats' => Yii::t('main-ui', 'Repeat'),
            'enabled' => Yii::t('main-ui', 'Active'),
            'color' => Yii::t('main-ui', 'Label color'),
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
        $criteria->compare('service_id', $this->service_id);
        $criteria->compare('CUsers_id', $this->CUsers_id, true);
        $criteria->compare('Status', $this->Status, true);
        $criteria->compare('ZayavCategory_id', $this->ZayavCategory_id, true);
        $criteria->compare('Priority', $this->Priority, true);
        $criteria->compare('Name', $this->Name, true);
        $criteria->compare('sla', $this->sla, true);
        $criteria->compare('Content', $this->Content, true);
        $criteria->compare('watchers', $this->watchers, true);
        $criteria->compare('cunits', $this->cunits, true);
        $criteria->compare('Date', $this->Date, true);
        $criteria->compare('Date_end', $this->Date_end, true);
        $criteria->compare('repeats', $this->repeats);
        $criteria->compare('enabled', $this->enabled);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CronReq the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function beforeSave()
    {
        if (isset($_POST['CronReq']['watchers'])) {
            $this->watchers = implode(",", $_POST['CronReq']['watchers']);
        }

        if (isset($_POST['CronReq']['cunits'])) {
            $this->cunits = implode(",", $_POST['CronReq']['cunits']);
        }

        if (isset($_POST['CronReq']['sla'])) {
            $this->sla = intval($_POST['CronReq']['sla']);
        }
        if (isset($_POST['CronReq']['service_id'])) {
            $this->service_id = intval($_POST['CronReq']['service_id']);
        }
        // var_dump($this->service_id);
        // die();
        return parent::beforeSave();
    }

}
