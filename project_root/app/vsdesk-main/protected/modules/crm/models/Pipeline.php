<?php

/**
 * This is the model class for table "pipeline".
 *
 * The followings are the available columns in table 'pipeline':
 * @property string $id
 * @property string $name
 * @property string $label
 * @property string $tag
 * @property integer $send_email
 * @property string $email_template
 * @property integer $send_sms
 * @property string $sms_template
 * @property integer $create_task
 * @property string $task_deadline
 * @property string $task_description
 * @property integer $close_deal
 * @property integer $cancel_deal
 */


class Pipeline extends CActiveRecord
{
    public $sort_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pipeline';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('send_email, send_sms, create_task, close_deal, cancel_deal, sort_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('label', 'length', 'max'=>400),
			array('tag', 'length', 'max'=>50),
			array('email_template, sms_template, task_deadline, task_description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, label, tag, send_email, email_template, sort_id, send_sms, sms_template, create_task, task_deadline, task_description, close_deal, cancel_deal', 'safe', 'on'=>'search'),
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
			'name' => 'Название',
			'label' => 'Ярлык',
			'tag' => 'Ярлык',
			'send_email' => 'Отправить Email?',
			'email_template' => 'Шаблон Email сообщения',
			'send_sms' => 'Отправить SMS?',
			'sms_template' => 'Шаблон SMS сообщения',
			'create_task' => 'Создать задачу?',
			'task_deadline' => 'Дата выполнения задачи',
			'task_description' => 'Описание задачи',
			'close_deal' => 'Звершить сделку успешно',
			'cancel_deal' => 'Звершить сделку неуспешно',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('send_email',$this->send_email);
		$criteria->compare('email_template',$this->email_template,true);
		$criteria->compare('send_sms',$this->send_sms);
		$criteria->compare('sms_template',$this->sms_template,true);
		$criteria->compare('create_task',$this->create_task);
		$criteria->compare('task_deadline',$this->task_deadline,true);
		$criteria->compare('task_description',$this->task_description,true);
		$criteria->compare('close_deal',$this->close_deal);
		$criteria->compare('cancel_deal',$this->cancel_deal);
        $criteria->compare('sort_id',$this->sort_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
			'pageSize' => (int)Yii::app()->session['PipelinePageCount'] ? Yii::app()->session['PipelinePageCount'] : 30,
			),
		));
	}
    public function beforeSave()
    {
        if($this->isNewRecord){
            $this->sort_id = $this->id;
        }
        $this->label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: ' . $this->tag . '">' . $this->name . '</span>';

        return parent::beforeSave();
    }

    public function afterFind()
    {
        if(($_SERVER['REQUEST_URI'] == '/crm/pipeline/view/id/'.$this->id) OR ($_SERVER['REQUEST_URI'] == '/crm/pipeline') OR ($_SERVER['REQUEST_URI'] == '/crm/pipeline/index')) {
            $this->send_email = $this->send_email == 1 ? 'Да' : 'Нет';
            $this->send_sms = $this->send_sms == 1 ? 'Да' : 'Нет';
            $this->create_task = $this->create_task == 1 ? 'Да' : 'Нет';
            $this->close_deal = $this->close_deal == 1 ? 'Да' : 'Нет';
            $this->cancel_deal = $this->cancel_deal == 1 ? 'Да' : 'Нет';
        }

        return parent::afterFind();
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pipeline the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
