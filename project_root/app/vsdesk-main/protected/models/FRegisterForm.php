<?php

/**
 * This is the model class for table "CUsers".
 *
 * The followings are the available columns in table 'CUsers':
 * @property integer $id
 * @property string $Username
 * @property string $Password
 * @property string $Email
 * @property string $Phone
 * @property integer $role
 */
class FRegisterForm extends CActiveRecord
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_BANNED = 'banned';
    public $verifyCode;
    public $tbot;
    public $vbot;
    public $msbot;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CUsers the static model class
     */
    public static
    function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public
    function tableName()
    {
        return 'CUsers';
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
            array('Username, fullname, Password', 'required'),
            //array('Password','required', 'on'=>'insert'),
            array('Email', 'email', 'message' => 'Неверный адрес электронной почты'),
            array('Username, Password, fullname, Email', 'length', 'max' => 100),
            array('Username', 'unique', 'message' => 'Этот логин занят'),
            array('Email', 'unique', 'message' => 'Этот Email занят'),
            array('Username', 'match', 'pattern' => '/^[\w@\.\-]+$/i', 'message' => 'Логин должен состоять только из латиницы и цифр'),
            array('fullname', 'unique', 'message' => 'Это имя занято'),
            array(
                'verifyCode',
                'CaptchaExtendedValidator',
                // авторизованным пользователям код можно не вводить
                'allowEmpty' => !Yii::app()->user->isGuest || !CCaptcha::checkRequirements(),
            ),
            //array('Phone', 'match', 'pattern' => '/^((\+?7)(-?\d{3})-?)?(\d{3})(-?\d{4})$/', 'message' => 'Некорректный формат поля {attribute}'),
            array('Username, Password, company, Phone, fullname', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public function uniqueIdAndName($attribute, $params = array())
    {
        if (!$this->hasErrors()) {
            $params['criteria'] = array(
                'condition' => 'id=:id',
                'params' => array(':id' => $this->id),
            );
            $validator = CValidator::createValidator('unique', $this, $attribute, $params);
            $validator->validate($this, array($attribute));
        }
    }

    /**
     * @return array relational rules.
     */
    public
    function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */

    public
    function attributeLabels()
    {
        return array(
            'id' => Yii::t('main-ui', '#'),
            'Username' => Yii::t('main-ui', 'User login'),
            'fullname' => Yii::t('main-ui', 'Fullname'),
            'Password' => Yii::t('main-ui', 'Password'),
            'company' => Yii::t('main-ui', 'Company'),
            'Email' => Yii::t('main-ui', 'Email'),
            'Phone' => Yii::t('main-ui', 'Phone'),

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

        $criteria->compare('id', $this->id);
        $criteria->compare('Username', $this->Username, true);
        $criteria->compare('fullname', $this->fullname, true);
        $criteria->compare('Password', $this->Password, true);
        $criteria->compare('Email', $this->Email, true);

        $criteria = new CDbCriteria;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public
    function beforeSave()
    {
        $role_name = Roles::model()->findByAttributes(array('value' => 'univefuser'));
        if(isset($role_name)){
            $this->role_name = $role_name->name;
            $this->role = 'univefuser';
            $this->Password = md5('mdy65wtc76' . $this->Password);
        } else {
            $roles = RolesRights::model()->findAllByAttributes(array('name' => 'systemUser', 'value' => '1'));
            $role_name = Roles::model()->findByPk($roles[0]['rid']);
            $this->role_name = $role_name->name;
            $this->role = $role_name->value;
            $this->Password = md5('mdy65wtc76' . $this->Password);
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->isNewRecord) {
            $connection = Yii::app()->db;
            $sql = "INSERT INTO `tbl_columns` (`id`, `data`) VALUES
            ('request-grid-full_" . $this->id . "', 'slabel||Date||EndTime||Name||cunits||fullname||mfullname||ZayavCategory_id||Priority||Действия'),
            ('request-grid_" . $this->id . "', 'slabel||Date||EndTime||Name||cunits||fullname||mfullname||ZayavCategory_id||Priority||Действия')";
            $connection->createCommand($sql)->execute();
            if ($this->role !== ('univefuser')) {
                $sql2 = "INSERT INTO `tbl_columns` (`id`, `data`) VALUES
            ('problems-grid_" . $this->id . "', 'slabel||date||creator||priority||category||manager||Действия'),
            ('cusers-grid_" . $this->id . "', 'fullname||company||department||position||Email||Phone||role_name||Действия')";
                $connection->createCommand($sql2)->execute();
            }

        } else {
            Chats::model()->updateAll(['name' => $this->fullname], "name='" . $this->_oldFullname . "'");
            Chats::model()->updateAll(['reader' => $this->fullname], "reader='" . $this->_oldFullname . "'");
        }

        return parent::afterSave();
    }

}
