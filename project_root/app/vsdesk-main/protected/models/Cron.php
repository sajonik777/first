<?php

include Yii::getPathOfAlias('webroot') . '/protected/components/CrontabManager.php';
include Yii::getPathOfAlias('webroot') . '/protected/components/CronEntry.php';
include Yii::getPathOfAlias('webroot') . '/protected/components/CliTool.php';
use php\manager\crontab\CrontabManager;

/**
 * This is the model class for table "cron".
 *
 * The followings are the available columns in table 'cron':
 * @property integer $id
 * @property string $name
 * @property string $job_id
 * @property string $job
 * @property string $time
 */
class Cron extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'cron';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'length', 'max' => 100),
            array('job_id, time', 'length', 'max' => 50),
            array('job', 'length', 'max' => 500),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, job_id, job, time', 'safe', 'on' => 'search'),
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
            'name' => Yii::t('main-ui', 'Name'),
            'job_id' => 'Job',
            'job' => Yii::t('main-ui', 'Run command'),
            'time' => Yii::t('main-ui', 'Time in cron format'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('job_id', $this->job_id, true);
        $criteria->compare('job', $this->job, true);
        $criteria->compare('time', $this->time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function afterSave()
    {
        $jobs = NULL;
        $jobs = Cron::model()->findAll();
        $crontab = new CrontabManager();
        foreach ($jobs as $value) {
            $job = $crontab->newJob();
            $job->on($value->time)->doJob($value->job);
            $crontab->add($job);
        }
        $crontab->save(false);

        return parent::afterSave();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Cron the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function afterDelete()
    {
        $jobs = NULL;
        $jobs = Cron::model()->findAll();
        $crontab = new CrontabManager();
        foreach ($jobs as $value) {
            $job = $crontab->newJob();
            $job->on($value->time)->doJob($value->job);
            $crontab->add($job);
        }
        $crontab->save(false);

        return parent::afterDelete();
    }
}
