<?php

/**
* This is the model class for table "bcats".
*
* The followings are the available columns in table 'bcats':
* @property integer $id
* @property string $name
*
* The followings are the available model relations:
* @property Brecords[] $brecords
*/
class Categories extends CActiveRecord
{
	/**
	* Returns the static model of the specified AR class.
	* @param string $className active record class name.
	* @return Bcats the static model class
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
		return 'bcats';
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
			array('name','length','max'=>50),
			array('access','length','max'=>700),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, access','safe','on'=>'search'),
			array('name'  ,'filter','filter'=>array($obj = new CHtmlPurifier(),'purify')),
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
		return array(
			'brecords' => array(self::HAS_MANY,'Brecords','parent_id'),
		);
	}

	/**
	* @return array customized attribute labels (name=>label)
	*/
	public
	function attributeLabels()
	{
		return array(
			'id'  => 'ID',
			'name'=> Yii::t('main-ui', 'Name'),
			'access'=> Yii::t('main-ui', 'Role access'),
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('access',$this->access,true);

		return new CActiveDataProvider($this, array(
				'criteria'  =>$criteria,
				'pagination' => array('pageSize'=> 20,           ),
			));
	}

	public static
	function all()
	{
		$models = self::model()->findAll();
		$array  = array();
		foreach($models as $one)
		{
			$array[$one->id] = $one->name;
		}
		return $array;
	}

	public static
	function ball()
	{
		$criteria = new CDbCriteria;
		$role = Roles::model()->findByAttributes(array('value' => Yii::app()->user->role));
		$bcats = Categories::model()->findAll();
		foreach ($bcats as $cat){
			$roles = explode(",", $cat->access);
			foreach($roles as $role_item){
				if($role_item == $role->name){
					$criteria->addSearchCondition('name', $cat->name, true, 'OR', 'LIKE');
				}
			}
		}
	
	$models = self::model()->findAll($criteria);
	$array  = array();
	foreach($models as $one)
	{
		$array[$one->id] = $one->name;
	}
	return $array;
}

	public static
	function call()
	{
		$models = self::model()->findAll();
		$array  = array();
		foreach($models as $one)
		{
			$array[$one->name] = $one->name;
		}
		return $array;
	}

	public function findAllByPkToArray($pk){
		$models = $this->findAllByPk($pk);
		$array = array();
		foreach($models as $one)
		{
			$array[$one->id] = $one->name;
		}
		return $array;
	}
}