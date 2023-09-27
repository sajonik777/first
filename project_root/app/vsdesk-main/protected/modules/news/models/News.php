<?php

/**
 * This is the model class for table "news".
 *
 * The followings are the available columns in table 'news':
 * @property integer $id
 * @property string $author
 * @property string $name
 * @property string $content
 * @property string $date
 */
class News extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'news';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author, name, date', 'length', 'max'=>50),
			array('content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, author, name, content, date', 'safe', 'on'=>'search'),
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
			'author' => Yii::t('main-ui', 'Author'),
			'name' => Yii::t('main-ui', 'Name'),
			'content' => Yii::t('main-ui', 'Content'),
			'date' => Yii::t('main-ui', 'Date'),
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
		$criteria->compare('author',$this->author,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=>(int)Yii::app()->session['newsPageCount'] ? Yii::app()->session['newsPageCount'] : 30,
                ),
		));
	}

    public function searchmain()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        $criteria->limit = 5;
        $criteria->compare('id',$this->id);
        $criteria->compare('author',$this->author,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('date',$this->date,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => false, //чтобы сработал limit в $criteria нужно установить 'pagination' => false
            'sort' => array(
                'defaultOrder' => 'id DESC'
            )
        ));
    }
    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return News the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    public
    function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->date = date("d.m.Y H:i");
        $author = CUsers::model()->findByAttributes(array('Username'=>Yii::app()->user->name));
        $this->author = $author->fullname;
        return parent::beforeSave();
    }
}
