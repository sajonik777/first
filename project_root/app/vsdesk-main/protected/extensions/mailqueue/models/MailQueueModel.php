<?php

namespace yiicod\mailqueue\models;

use CDbCriteria;
use CActiveDataProvider;
use CActiveRecord;
use CMap;
use Yii;

/**
 * This is the model class for table "MailQueue".
 *
 * The followings are the available columns in table 'MailQueue':
 *
 * @property string $id
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $status
 * @property string $dateCreate
 */
class MailQueueModel extends CActiveRecord
{
    public $getmailconfig;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'MailQueue';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['to, subject, body', 'required'],
            ['to, subject', 'length', 'max' => 255],
            ['status', 'length', 'max' => 1],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, to, subject, body, priority, status, getmailconfig', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('mailqueue', 'Id'),
            'to' => Yii::t('mailqueue', 'To'),
            'subject' => Yii::t('mailqueue', 'Subject'),
            'body' => Yii::t('mailqueue', 'Body'),
            'priority' => Yii::t('mailqueue', 'Priority'),
            'status' => Yii::t('mailqueue', 'Status'),
            'dateCreate' => Yii::t('mailqueue', 'Date Create'),
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id, true);
        $criteria->compare('to', $this->to, true);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('dateCreate', $this->dateCreate, true);
        $criteria->compare('getmailconfig', $this->getmailconfig, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return MailQueueModel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        $behaviors = [];
        $behaviors['AttributesMapBehavior'] = [
            'class' => 'yiicod\mailqueue\models\behaviors\AttributesMapBehavior',
            'attributesMap' => Yii::app()->getComponent('mailqueue')->modelMap['MailQueue'],
        ];
        $behaviors['CTimestampBehavior'] = [
            'class' => 'zii.behaviors.CTimestampBehavior',
            'createAttribute' => in_array(Yii::app()->getComponent('mailqueue')->modelMap['MailQueue']['fieldCreateDate'], $this->attributeNames()) ?
                    Yii::app()->getComponent('mailqueue')->modelMap['MailQueue']['fieldCreateDate'] : null,
            'updateAttribute' => in_array(Yii::app()->getComponent('mailqueue')->modelMap['MailQueue']['fieldUpdateDate'], $this->attributeNames()) ?
                    Yii::app()->getComponent('mailqueue')->modelMap['MailQueue']['fieldUpdateDate'] : null,
            'timestampExpression' => 'date("Y-m-d H:i:s")',
        ];
        if (file_exists(Yii::getPathOfAlias('application.models.behaviors.XssBehavior').'.php')) {
            $behaviors['XssBehavior'] = [
                'class' => 'application.models.behaviors.XssBehavior',
                'attributesExclude' => [Yii::app()->getComponent('mailqueue')->modelMap['MailQueue']['fieldBody']],
            ];
        }

        return CMap::mergeArray(parent::behaviors(), $behaviors);
    }
}
