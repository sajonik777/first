<?php

/**
 * This is the model class for table "unit_templates".
 *
 * The followings are the available columns in table 'unit_templates':
 * @property integer $id
 * @property string $name
 * @property string $content
 */
class UnitTemplates extends CActiveRecord
{
    public $type_name;
    public $page_format;
    public $page_width;
    public $page_height;
    public $page_size;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'unit_templates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, format, content', 'required'),
			array('name, type_name, page_format, page_size', 'length', 'max'=>100),
			array('type, page_width, page_height', 'numerical', 'integerOnly' => true),
			array('format', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, content, type, type_name', 'safe', 'on'=>'search'),
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
			'name' => Yii::t('main-ui','Name'),
			'content' => Yii::t('main-ui','Content'),
			'type' => Yii::t('main-ui','Type'),
            'type_name' => Yii::t('main-ui','Type'),
			'format' => Yii::t('main-ui','Page orientation'),
            'page_format' => Yii::t('main-ui','Page format'),
            'page_size' => Yii::t('main-ui','Page size'),
            'page_width' => Yii::t('main-ui','Page width (mm)'),
            'page_height' => Yii::t('main-ui','Page height (mm)'),
		);
	}

	public static function all()
	{
		$models = self::model()->findAll();
		$array = array();
		foreach ($models as $one) {
			$array[$one->id] = $one->name;
		}
		return $array;
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
		$criteria->compare('name',$this->name,true);
		//$criteria->compare('content',$this->content,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('type',$this->type,true);
        $criteria->compare('type_name',$this->type_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => (int)Yii::app()->session['cunitsTPageCount'] ? Yii::app()->session['cunitsTPageCount'] : 30,
			),
		));
	}

    public function afterSave(){
        if ($_POST['UnitTemplates']['type'] == '1'){
            self::model()->updateByPk($this->id, array('type_name'=>Yii::t('main-ui', 'Unit')));
        }elseif($_POST['UnitTemplates']['type'] == 2){
            self::model()->updateByPk($this->id, array('type_name'=>Yii::t('main-ui', 'Asset')));
        }elseif($_POST['UnitTemplates']['type'] == 3){
            self::model()->updateByPk($this->id, array('type_name'=>Yii::t('main-ui', 'Request')));
        }elseif($_POST['UnitTemplates']['type'] == 4){
            self::model()->updateByPk($this->id, array('type_name'=>Yii::t('main-ui', 'Contract')));
        }elseif($_POST['UnitTemplates']['type'] == 5){
            self::model()->updateByPk($this->id, array('type_name'=>Yii::t('main-ui', 'Knowledge')));
        }

        return parent::afterSave();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UnitTemplates the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
