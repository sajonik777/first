<?php

/**
 * This is the model class for table "leads".
 *
 * The followings are the available columns in table 'leads':
 * @property string $id
 * @property string $name
 * @property integer $company_id
 * @property string $company
 * @property integer $contact_id
 * @property string $contact
 * @property string $contact_phone
 * @property string $contact_email
 * @property string $contact_position
 * @property string $created
 * @property string $changed
 * @property string $closed
 * @property string $creator
 * @property string $changer
 * @property integer $manager_id
 * @property string $manager
 * @property integer $status_id
 * @property string $status
 * @property string $cost
 * @property string $tag
 * @property string $description
 */
class Leads extends CActiveRecord
{
    public $sort_id;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'leads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, contact_id, manager_id, status_id, sort_id', 'numerical', 'integerOnly'=>true),
            array('name, contact, manager, status_id', 'required'),
			array('name, company, contact, contact_phone, contact_email, contact_position, creator, changer, manager, tag', 'length', 'max'=>200),
			array('status', 'length', 'max'=>400),
			array('cost', 'length', 'max'=>100),
			array('created, changed, closed, description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, company_id, company, contact_id, contact, contact_phone, sort_id, contact_email, contact_position, created, changed, closed, creator, changer, manager_id, manager, status_id, status, cost, tag, description', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'company' => 'Компания',
			'contact_id' => 'Contact',
			'contact' => 'Контакт',
			'contact_phone' => 'Телефон',
			'contact_email' => 'E-mail',
			'contact_position' => 'Должность',
			'created' => 'Дата создания',
			'changed' => 'Дата изменения',
			'closed' => 'Дата завершения',
			'creator' => 'Кем создана',
			'changer' => 'Кем изменена',
			'manager_id' => 'Manager',
			'manager' => 'Ответственный',
			'status_id' => 'Этап сделки',
			'status' => 'Этап сделки',
			'cost' => 'Бюджет',
			'tag' => 'Тег',
			'description' => 'Описание',
		);
	}

    public static function GetExplode($range)
    {
        $date_range = explode(' - ', $range);
        return $date_range;
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('contact_id',$this->contact_id);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('contact_phone',$this->contact_phone,true);
		$criteria->compare('contact_email',$this->contact_email,true);
		$criteria->compare('contact_position',$this->contact_position,true);
		if(!empty($this->created)){
            $mdata = self::GetExplode($this->created);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('created', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('created',$this->created,true);
        }
		$criteria->compare('changed',$this->changed,true);
		$criteria->compare('closed',$this->closed,true);
		$criteria->compare('creator',$this->creator,true);
		$criteria->compare('changer',$this->changer,true);
		$criteria->compare('manager_id',$this->manager_id);
		$criteria->compare('manager',$this->manager,true);
		$criteria->compare('status_id',$this->status_id);
        if (isset($_GET['Leads']['status']) and is_array($_GET['Leads']['status'])) {
            $criteria->addInCondition('status', $_GET['Leads']['status'], 'OR');
        }
        $criteria->compare('cost',$this->cost,true);
		$criteria->compare('tag',$this->tag,true);
		$criteria->compare('description',$this->description,true);
        $criteria->compare('sort_id',$this->sort_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
			'pageSize' => (int)Yii::app()->session['LeadsPageCount'] ? Yii::app()->session['LeadsPageCount'] : 30,
			),
		));
	}

    public function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        if($this->isNewRecord){
            $creator = CUsers::model()->findByPk(Yii::app()->user->id);
            $this->created = date('Y-m-d H:m:s');
            $this->creator = $creator->fullname;
            $this->sort_id = $this->id;
        } else {
            $creator = CUsers::model()->findByPk(Yii::app()->user->id);
            $this->changed = date('Y-m-d H:m:s');
            $this->changer = $creator->fullname;
        }



            if(isset($_POST['Leads']['contact'])){
                $contact = CUsers::model()->findByAttributes(array('fullname'=>$this->contact));
                $this->contact_id = $contact->id;
            }
            if(isset($_POST['Leads']['manager'])){
                $manager = CUsers::model()->findByAttributes(array('fullname'=>$this->manager));
                $this->manager_id = $manager->id;
            }
            if(isset($_POST['Leads']['company'])){
                $company = Companies::model()->findByAttributes(array('name' => $this->company));
                $this->company_id = $company->id;
            }
            if(isset($_POST['Leads']['status_id'])){
                $status = Pipeline::model()->findByPk($this->status_id);
                $this->status = $status->label;
            }

        return parent::beforeSave();
    }

    public function afterSave(){
        if(isset($_POST['Leads']['status_id'])){
            $status = Pipeline::model()->findByPk($_POST['Leads']['status_id']);
            if ($status->send_email == 1){
                $email = $_POST['Leads']['contact_email'];
                if (isset($email) AND !empty($email)){
                    $message  = self::MessageGen($status->email_template);
                    Email::send($email,'CRM TEST SUBJECT', $message, NULL);
                }

            }

        }
        return parent::afterSave();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Leads the static model class
	 */
    public function MessageGen($content)
    {

        $s_message = Yii::t('message', "$content", array(
            '{name}' => $this->name,
            '{status}' => $this->status,
            '{contact}' => $this->contact,
            '{manager}' => $this->manager,

        ));
        return $s_message;
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
