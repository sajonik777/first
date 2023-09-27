<?php

/**
 * This is the model class for table "sla".
 *
 * The followings are the available columns in table 'sla':
 * @property integer $id
 * @property string $name
 * @property string $retimeh
 * @property string $retimem
 * @property string $sltimeh
 * @property string $sltimem
 * @property string $rhours
 * @property string $shours
 * @property string $taxes
 * @property string $cost
 * @property string $wstime
 * @property string $wetime
 * @property integer $round_hours
 * @property integer $round_days
 *
 * @property string $ntretimeh
 * @property string $ntretimem
 * @property string $ntsltimeh
 * @property string $ntsltimem
 * @property string $nrhours
 * @property string $nshours
 *
 *
 * The followings are the available model relations:
 * @property CUsers $id0
 */
class Sla extends CActiveRecord
{

    public $autoClose;

    public static
    function all()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    public static
    function all_id()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->id] = $one->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sla the static model class
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
        return 'sla';
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
            array('name, taxes, retimeh, sltimeh,  retimem, sltimem, ntretimeh, ntsltimeh,  ntretimem, ntsltimem, round_days, sla_type', 'required'),
            array('name, wstime, wetime, sla_type', 'length', 'max' => 50),
            array('taxes', 'length', 'max' => 500),
            array('retimem, sltimem, ntretimem, ntsltimem', 'length', 'max' => 2),
            array('retimeh, sltimeh, ntretimeh, ntsltimeh', 'length', 'max' => 3),
            array('retimem, sltimem, retimeh, sltimeh, ntretimeh, ntsltimeh,  ntretimem, ntsltimem', 'numerical', 'integerOnly' => true),
            //array('retimeh, sltimeh,  retimem, sltimem', 'match', 'pattern' => '/^\d{2}$/', 'message' => 'Некорректный формат поля {attribute}'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, retimeh, sltimeh,  retimem, sltimem, ntretimeh, ntsltimeh,  ntretimem, ntsltimem, rhours, shours, nrhours, nshours,taxes, wstime, wetime, round_days, sla_type', 'safe', 'on' => 'search'),
            array('name, retimeh, sltimeh,  retimem, sltimem, ntretimeh, ntsltimeh,  ntretimem, ntsltimem, rhours, shours, nrhours, nshours, taxes, wstime, wetime', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
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
        return [
            'sla_history' => array(self::HAS_MANY, 'SlaHistory', 'sid'),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main-ui', '#'),
            'name' => Yii::t('main-ui', 'Name'),
            'retimeh' => Yii::t('main-ui', 'Reaction time (h:m)'),
            'sltimeh' => Yii::t('main-ui', 'Time of solution (h:m)'),
            'rhours' => Yii::t('main-ui', 'Reaction time (h:m)'),
            'shours' => Yii::t('main-ui', 'Time of solution (h:m)'),
            'ntretimeh' => Yii::t('main-ui', 'Notification of the date of the reaction (h:m)'),
            'ntsltimeh' => Yii::t('main-ui', 'Notification of the date solution (h:m)'),
            'nrhours' => Yii::t('main-ui', 'Notification of the date of the reaction (h:m)'),
            'nshours' => Yii::t('main-ui', 'Notification of the date solution (h:m)'),
            'wstime' => Yii::t('main-ui', 'Workhours from'),
            'wetime' => Yii::t('main-ui', 'Workhours to'),
            'taxes' => Yii::t('main-ui', 'Holidays'),
            'round_days' => Yii::t('main-ui', 'Seven days a week'),
            'sla_type' => Yii::t('main-ui', 'SLA type'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('retimeh', $this->retimeh, true);
        $criteria->compare('sltimeh', $this->sltimeh, true);
        $criteria->compare('retimem', $this->retimem, true);
        $criteria->compare('sltimem', $this->sltimem, true);
        $criteria->compare('rhours', $this->rhours, true);
        $criteria->compare('shours', $this->shours, true);
        $criteria->compare('ntretimeh', $this->ntretimeh, true);
        $criteria->compare('ntsltimeh', $this->sltimeh, true);
        $criteria->compare('ntretimem', $this->ntsltimeh, true);
        $criteria->compare('ntsltimem', $this->ntsltimem, true);
        $criteria->compare('nrhours', $this->nrhours, true);
        $criteria->compare('nshours', $this->nshours, true);
        $criteria->compare('taxes', $this->taxes, true);
        $criteria->compare('sla_type', $this->sla_type, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public
    function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->rhours = $this->retimeh . ':' . $this->retimem;
        $this->shours = $this->sltimeh . ':' . $this->sltimem;
        $this->nrhours = $this->ntretimeh . ':' . $this->ntretimem;
        $this->nshours = $this->ntsltimeh . ':' . $this->ntsltimem;

        if (!$this->isNewRecord) {

            $creator_id = Yii::app()->user->id;
            $olddata = Sla::model()->findByPk($this->id);
            if ($olddata->name !== $_POST['Sla']['name']) {
                $this->addHistory(Yii::t('main-ui', 'Sla name changed:'). ' ' . $this->name, $creator_id);
            }
            if ($olddata->retimeh !== $_POST['Sla']['retimeh'] || $olddata->retimem !== $_POST['Sla']['retimem']) {
                $this->addHistory(Yii::t('main-ui', 'Reaction time changed:'). ' '  . $_POST['Sla']['retimeh'] . ':' .$_POST['Sla']['retimem'], $creator_id);
            }

            if ($olddata->sltimeh !== $_POST['Sla']['sltimeh'] || $olddata->sltimem !== $_POST['Sla']['sltimem']) {
                $this->addHistory(Yii::t('main-ui', 'Desigion time changed:'). ' '  . $_POST['Sla']['sltimeh'] . ':' .$_POST['Sla']['sltimem'], $creator_id);
            }

            if ($olddata->ntretimeh !== $_POST['Sla']['ntretimeh'] || $olddata->ntretimem !== $_POST['Sla']['ntretimem']) {
                $this->addHistory(Yii::t('main-ui', 'Reaction time alert time changed:'). ' '  . $_POST['Sla']['ntretimeh'] . ':' .$_POST['Sla']['ntretimem'], $creator_id);
            }
            if ($olddata->ntsltimeh !== $_POST['Sla']['ntsltimeh'] || $olddata->ntsltimem !== $_POST['Sla']['ntsltimem']) {
                $this->addHistory(Yii::t('main-ui', 'Desigion time alert time changed:'). ' '  . $_POST['Sla']['ntsltimeh'] . ':' .$_POST['Sla']['ntsltimem'], $creator_id);
            }
            if ($olddata->wstime !== $_POST['Sla']['wstime'] || $olddata->wetime !== $_POST['Sla']['wetime']) {
                $this->addHistory(Yii::t('main-ui', 'Working hours changed:'). ' '  . $_POST['Sla']['wstime'] . ':' . $_POST['Sla']['wetime'], $creator_id);
            }
            if ($olddata->taxes !== $_POST['Sla']['taxes']) {
                $this->addHistory(Yii::t('main-ui', 'Holidays changed:'). ' '  . $_POST['Sla']['taxes'], $creator_id);
            }
            if ($olddata->sla_type !== $_POST['Sla']['sla_type']) {
                $this->addHistory(Yii::t('main-ui', 'SLA type changed:'). ' '  . $_POST['Sla']['sla_type'], $creator_id);
            }
            if ($olddata->round_days !== $_POST['Sla']['round_days']) {
                $rd = $_POST['Sla']['round_days'] == 1 ? Yii::t('main-ui', 'Yes'):Yii::t('main-ui', 'No');
                $this->addHistory(Yii::t('main-ui', 'No holidays changed:'). ' '  . $rd, $creator_id);
            }
            // if () {
            //     $this->addHistory(Yii::t('main-ui', 'SLA changed:'). ' '  . $this->sla, $creator_id);
            // }
            // if ($olddata->gtype !== $_POST['Sla']['sltimeh']) {
            //     $gt = $this->gtype == "1" ? Yii::t('main-ui', 'User') : Yii::t('main-ui', 'Group');
            //     $this->addHistory(Yii::t('main-ui', 'Gtype changed:') . ' ' . $gt  , $creator_id); 
            // }
            // if ($olddata->group !== $_POST['Sla']['sltimem']) {
            //     $this->addHistory(Yii::t('main-ui', 'Group changed:'). ' '  . $this->group, $creator_id);
            // }
            // if ($olddata->priority !== $_POST['Sla']['priority']) {
            //     $this->addHistory(Yii::t('main-ui', 'Priority changed:'). ' ' . $this->priority, $creator_id);
            // }
            // if ($olddata->manager !== $_POST['Sla']['manager'] && $this->manager !== null) {
            //     $this->addHistory(Yii::t('main-ui', 'Manager changed:'). ' '  . $this->manager, $creator_id);
            // }
            // if ($olddata->watcher !== implode(',', $_POST['watcher'])) {
            //     $this->addHistory(Yii::t('main-ui', 'Watcher changed:'). ' '  . $this->watcher, $creator_id);
            // }
            // if ($olddata->matchings !== implode(',', $_POST['matchings'])) {
            //     $this->addHistory(Yii::t('main-ui', 'Matchings changed:'). ' '  . $this->matchingNames, $creator_id);
            // }
            // if ($olddata->availability !== $_POST['Sla']['availability']) {
            //     $this->addHistory(Yii::t('main-ui', 'Availability changed:'). ' '  . $this->availability, $creator_id);
            // }
            // if ($olddata->category_id !== $_POST['Sla']['category_id']) {
            //     $this->addHistory(Yii::t('main-ui', 'Category changed:'). ' '  . ServiceCategories::model()->findByPk($this->category_id)->name, $creator_id);
            // }
            // if ($olddata->outsource !== $_POST['Sla']['outsource']) {
            //     $os = $this->outsource == "1" ? Yii::t('main-ui', 'Outsource true') :  Yii::t('main-ui', 'Outsource false');
            //     $this->addHistory(Yii::t('main-ui', 'Outsource changed:'). ' ' . $os , $creator_id);
            // }
    
        }
        return parent::beforeSave();
    }

    public function addHistory($action, $user)
    {
        $sla_history = new SlaHistory();
        $sla_history->sid = $this->id;
        $sla_history->date = date("Y-m-d H:i:s");
        $sla_history->user = $user;
        $sla_history->user_name = CUsers::model()->findByPk($user)->fullname;
        $sla_history->action = $action;
        $sla_history->save(false);
    }

}
