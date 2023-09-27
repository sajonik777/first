<?php

/**
 * This is the model class for table "MailQueue".
 *
 * The followings are the available columns in table 'MailQueue':
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property string $attachs
 * @property integer $priority
 * @property integer $status
 * @property string $createDate
 * @property string $updateDate
 * @property string $getmailconfig
 */
class MailQueueList extends CActiveRecord
{
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
		return array(
			array('from, to, subject', 'required'),
			array('priority, status', 'numerical', 'integerOnly'=>true),
			array('from, to, subject', 'length', 'max'=>100),
			array('getmailconfig', 'length', 'max'=>50),
			array('body, attachs, createDate, updateDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, from, to, subject, body, attachs, priority, status, createDate, updateDate, getmailconfig', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from' => Yii::t('main-ui', 'From'),
			'to' => Yii::t('main-ui', 'Recipients'),
			'subject' => Yii::t('main-ui', 'Subject'),
			'body' => Yii::t('main-ui', 'Content'),
			'attachs' => Yii::t('main-ui', 'Attachments'),
			'priority' => Yii::t('main-ui', 'Priority'),
			'status' => Yii::t('main-ui', 'Status'),
			'createDate' => Yii::t('main-ui', 'Created'),
			'updateDate' => Yii::t('main-ui', 'Update'),
			'getmailconfig' => Yii::t('main-ui', 'Configuration'),
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('attachs',$this->attachs,true);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('status',$this->status);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('updateDate',$this->updateDate,true);
		$criteria->compare('getmailconfig',$this->getmailconfig,true);
        $sort = new CSort();
        $sort->modelClass = 'MailQueueList';
        $sort->multiSort = true;
        $sort->defaultOrder = 'id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => $sort,
			'pagination' => array(
			'pageSize' => (int)Yii::app()->session['MailQueuePageCount'] ? Yii::app()->session['MailQueuePageCount'] : 30,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MailQueue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
