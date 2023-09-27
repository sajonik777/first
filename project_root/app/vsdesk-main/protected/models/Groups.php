<?php

/**
 * This is the model class for table "groups".
 *
 * The followings are the available columns in table 'groups':
 * @property integer $id
 * @property string $name
 * @property string $users
 * @property integer $send
 */
class Groups extends CActiveRecord
{
    /**
     * @return array
     */
    public static function all()
    {
        $models = self::model()->findAll();
        $array = [];
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    /**
     * @return array
     */
    public static function allWithId()
    {
        $models = self::model()->findAll();
        $array = [];
        foreach ($models as $one) {
            $array[$one->id] = $one->name;
        }

        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Groups the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'groups';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'required'],
            ['send', 'boolean'],
            ['name', 'length', 'max' => 100],
            ['email', 'length', 'max' => 100],
            ['phone', 'length', 'max' => 100],
            ['users', 'length', 'max' => 2000],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, users, email, phone', 'safe', 'on' => 'search'],
        ];
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
        return [
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'email' => Yii::t('main-ui', 'E-mail'),
            'phone' => Yii::t('main-ui', 'Phone'),
            'users' => Yii::t('main-ui', 'Users'),
            'send' => Yii::t('main-ui', 'Отправлять уведомления на общий ящик'),
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('users', $this->users, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if (isset ($_POST['users'])) {
            $this->users = implode(",", $_POST['users']);
        }
        return parent::beforeSave();
    }
}
