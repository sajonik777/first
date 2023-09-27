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
 * @property integer $photo
 * @property string $city
 * @property string $mobile
 *
 * @method pushMessage($notification, $url)
 */
class CUsers extends CActiveRecord
{
    use PushTrait;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_BANNED = 'banned';

    public $image;
    public $tbot;
    public $vbot;
    public $wbot;
    public $msbot;
    public $send_tbot;
    public $send_vbot;
    public $send_wbot;

    private $_oldFullname;

    /**
     * @param $category_id
     * @return array
     */
    public function getServicesArray($category_id = null)
    {
        $sharedServices = Service::getAllShared($category_id);
        $allServices = $sharedServices;
        /** @var Companies $company */
        $company = Companies::model()->findByAttributes(['name' => $this->company]);
        if ($company) {
            $companyServices = $company->getServicesArray($category_id);
            foreach ($companyServices as $key => $value) {
                if (!isset($allServices[$key])) {
                    $allServices[$key] = $value;
                }
            }
        }
        /** @var Depart $depart */
        $depart = Depart::model()->findByAttributes(['name' => $this->department]);
        if ($depart) {
            $departServices = $depart->getServicesArray($category_id);
            foreach ($departServices as $key => $value) {
                if (!isset($allServices[$key])) {
                    $allServices[$key] = $value;
                }
            }
        }

        return $allServices;
    }

    public static function getRole($username = null)
    {
        if(isset($username)){
            $user = self::model()->findByAttributes(['Username' => $username]);
            $rolename = Roles::model()->findByAttributes(['value' => $user->role]);
            $rights = RolesRights::model()->findAllByAttributes(['rid' => $rolename->id, 'value' => 1]);
            foreach ($rights as $right){
                if($right->name == 'systemManager' OR $right->name == 'systemUser' OR $right->name == 'systemAdmin'){
                    $role = $right->name;
                }
            }
        }
        return $role;
    }

    public static function all()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->managersAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $one) {
                $array[$one['Username']] = $one['fullname'];
            }
        }

        return $array;
    }

    public static function eall()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = self::model()->findAllByAttributes(array('active' => 1), $criteria);
        $array = array();
        foreach ($models as $one) {
            $array[] = $one->fullname;
        }

        return $array;
    }

    public static function allm()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->managersAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $one) {
                $array[$one['fullname']] = $one['fullname'];
            }
        }

        return $array;
    }

    public static function all_id()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->managersAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $one) {
                $array[$one['id']] = $one['fullname'];
            }
        }

        return $array;
    }

    public static function ufall()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->usersAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[$value->fullname] = $value->fullname;
            }
        }

        return $array;
    }

    public static function fall()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->usersAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[$value->Username] = $value->fullname;
            }
        }

        return $array;
    }

    public static function ffall()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->noadminAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[$value->Username] = $value->fullname;
            }
        }

        return $array;
    }

    public static function c_all()
    {
        $user = CUsers::model()->findByPk(Yii::app()->user->id);
        $criteria = new CDbCriteria;
        if ($user->company) {
            $criteria->condition = 'company = :ucompany';
            $criteria->params = array(':ucompany' => $user->company);
        }
        $models = Roles::model()->noadminAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[$value->fullname] = $value->fullname;
            }
        }

        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CUsers the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function d_all()
    {
        $user = CUsers::model()->findByPk(Yii::app()->user->id);
        $criteria = new CDbCriteria;
        if ($user->company) {
            $criteria->condition = 'company = :ucompany';
            $criteria->params = array(':ucompany' => $user->company);
        }
        $models = Roles::model()->noadminAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[] = $value->fullname;
            }
        }

        return $array;
    }

    public static function wall()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->noadminAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[$value->fullname] = $value->fullname;
            }
        }

        return $array;
    }

    public static function w2all()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->noadminAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[] = $value->fullname;
            }
        }

        return $array;
    }

    public static function w3all()
    {
        $criteria = new CDbCriteria(array('order' => 'fullname ASC'));
        $models = Roles::model()->noadminAll($criteria);
        $array = array();
        array_walk_recursive($models, function ($value, $key) use (&$result) {
            $result[] = $value;
        });
        if ($result) {
            foreach ($result as $value) {
                $array[$value->Username] = $value->fullname;
            }
        }

        return $array;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'CUsers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
//            ['image', 'file', 'types' => 'jpg, gif, png'],
            ['Username, fullname, role, company, lang', 'required', 'on' => 'insert'],
            ['Username, fullname, lang', 'required', 'on' => 'update'],
            ['Username, fullname', 'filter', 'filter' => 'trim'],
            //array('Password','required', 'on'=>'insert'),
            ['Email', 'email', 'message' => 'Неверный адрес электронной почты'],
            ['Email', 'unique', 'message' => 'Этот Email занят'],
            [
                'Username, Password, birth, Email, push_id,Phone, intphone, role, position, room',
                'length',
                'max' => 100
            ],
            ['umanager, city, mobile', 'length', 'max' => 50],
            ['department, company, fullname', 'length', 'max' => 100],
            ['Username', 'unique', 'message' => 'Этот логин занят'],
            [
                'Username',
                'match',
                'pattern' => '/^[\w@\.\-]+$/i',
                'message' => 'Логин должен состоять только из латиницы и цифр'
            ],
            ['fullname', 'unique', 'message' => 'Это имя занято'],
            ['sendmail, sendsms, send_vbot, send_tbot, send_wbot, active, photo', 'numerical', 'integerOnly' => true],
            //array('Phone', 'match', 'pattern' => '/^((\+?7)(-?\d{3})-?)?(\d{3})(-?\d{4})$/', 'message' => 'Некорректный формат поля {attribute}'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [
                'Username, umanager, fullname, Email, Phone, mobile, city,  push_id, department, position, tbot, vbot, wbot, msbot',
                'filter',
                'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ],
            [
                'Username, Password, fullname, Email, Phone, mobile, city, image, push_id, role, role_name, company, sendmail, sendsms, send_wbot, send_vbot, send_tbot, active, department, position, tbot, vbot, wbot, msbot',
                'safe',
                'on' => 'search'
            ],
        ];
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
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'service_history' => array(self::HAS_MANY, 'Service_history', 'sid'),
            'assets' => array(self::HAS_MANY, 'Asset', 'cusers_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main-ui', '#'),
            'Username' => Yii::t('main-ui', 'User login'),
            'fullname' => Yii::t('main-ui', 'Fullname'),
            'Password' => Yii::t('main-ui', 'Password'),
            'Email' => Yii::t('main-ui', 'Email'),
            'Phone' => Yii::t('main-ui', 'Phone'),
            'push_id' => Yii::t('main-ui', 'Pushover ID'),
            'role' => Yii::t('main-ui', 'Role'),
            'room' => Yii::t('main-ui', 'Room'),
            'role_name' => Yii::t('main-ui', 'Role'),
            'company' => Yii::t('main-ui', 'Company'),
            'department' => Yii::t('main-ui', 'Department'),
            'birth' => Yii::t('main-ui', 'Birthday'),
            'umanager' => Yii::t('main-ui', 'Department manager'),
            'intphone' => Yii::t('main-ui', 'Internal phone'),
            'position' => Yii::t('main-ui', 'Position'),
            'sendmail' => Yii::t('main-ui', 'Email notification'),
            'sendsms' => Yii::t('main-ui', 'SMS notification'),
            'lang' => Yii::t('main-ui', 'Language'),
            'active' => Yii::t('main-ui', 'Active'),
            'photo' => Yii::t('main-ui', 'Photo'),
            'city' => Yii::t('main-ui', 'City'),
            'mobile' => Yii::t('main-ui', 'Mobile'),
            'tbot' => Yii::t('main-ui', 'Telegram'),
            'vbot' => Yii::t('main-ui', 'Viber'),
            'msbot' => Yii::t('main-ui', 'Azure bot framework'),
            'wbot' => Yii::t('main-ui', 'WhatsApp'),
            'send_tbot' => Yii::t('main-ui', 'Telegram notification'),
            'send_vbot' => Yii::t('main-ui', 'Viber notification'),
            'send_wbot' => Yii::t('main-ui', 'WhatsApp notification'),
        ];
    }

    public function importLabels()
    {
        return [
            'id' => Yii::t('main-ui', '#'),
            'Username' => Yii::t('main-ui', 'User login'),
            'fullname' => Yii::t('main-ui', 'Fullname'),
            'Password' => Yii::t('main-ui', 'Password'),
            'Email' => Yii::t('main-ui', 'Email'),
            'Phone' => Yii::t('main-ui', 'Phone'),
            'role' => Yii::t('main-ui', 'Role value'),
            'room' => Yii::t('main-ui', 'Room'),
            'company' => Yii::t('main-ui', 'Company'),
            'department' => Yii::t('main-ui', 'Department'),
            'umanager' => Yii::t('main-ui', 'Department manager'),
            'position' => Yii::t('main-ui', 'Position'),
            'sendmail' => Yii::t('main-ui', 'Email notification') . '(1,0)',
            'sendsms' => Yii::t('main-ui', 'SMS notification') . '(1,0)',
            'lang' => Yii::t('main-ui', 'Language') . '(ru,en)',
            'active' => Yii::t('main-ui', 'Active') . '(1,0)',
            'photo' => Yii::t('main-ui', 'Photo'),
            'city' => Yii::t('main-ui', 'City'),
            'mobile' => Yii::t('main-ui', 'Mobile'),
            'intphone' => Yii::t('main-ui', 'Internal phone'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('fullname', $this->fullname, true);
        if (Yii::app()->user->checkAccess('systemAdmin') OR Yii::app()->user->checkAccess('systemManager')) {
            $criteria->compare('Username', $this->Username, true);
        } else {
            $criteria->compare('Username', Yii::app()->user->name, false);
        }
        $criteria->compare('Password', $this->Password, true);
        $criteria->compare('Email', $this->Email, true);
        $criteria->compare('Phone', $this->Phone, true);
        $criteria->compare('push_id', $this->push_id, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('role_name', $this->role_name);
        $criteria->compare('company', $this->company);
        $criteria->compare('room', $this->room);
        $criteria->compare('intphone', $this->intphone);
        $criteria->compare('birth', $this->birth);
        $criteria->compare('department', $this->department, true);
        $criteria->compare('umanager', $this->umanager);
        $criteria->compare('position', $this->position);
        $criteria->compare('lang', $this->lang);
        $criteria->compare('active', $this->active);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('tbot', $this->tbot, true);
        $criteria->compare('vbot', $this->vbot, true);
        $criteria->compare('msbot', $this->msbot, true);
        $criteria->compare('send_wbot', $this->send_wbot, true);
        $criteria->compare('send_vbot', $this->send_vbot, true);
        $criteria->compare('send_tbot', $this->send_tbot, true);

        if ('fullname.desc' === $_GET['CUsers_sort']) {
            $criteria->order = "`fullname`>'а',`fullname` DESC";
        } elseif ('fullname' === $_GET['CUsers_sort']) {
            $criteria->order = "`fullname`<'а',`fullname` ASC";
        }


        $users_data = new CActiveDataProvider(get_class($this), [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['usersPageCount'] ? Yii::app()->session['usersPageCount'] : 30,
            ],
        ]);
        $_SESSION['users_records'] = $users_data;

//        if ($_SERVER['REQUEST_URI'] !== '/api/users/') {
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/users') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
                'sort' => [
                    'defaultOrder' => "`fullname`<'а',`fullname` ASC",
                ],
                'pagination' => [
                    'pageSize' => (int)Yii::app()->session['usersPageCount'] ? Yii::app()->session['usersPageCount'] : 30,
                ],
            ]);
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => "`fullname`<'а',`fullname` ASC",
            ],
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);
    }

    public function psearch()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('fullname', $this->fullname, true);
        $criteria->compare('Username', $this->Username, true);
        $criteria->compare('Password', $this->Password, true);
        $criteria->compare('Email', $this->Email, true);
        if(Yii::app()->user->checkAccess('viewOnlyUserCompanyPhonebook')){
            $username = CUsers::model()->findByPk(Yii::app()->user->id);
            $company = Companies::model()->findByAttributes(['name' => $username->company]);
            $criteria->compare('company', $company->name);
        } else {
            $criteria->compare('company', $this->company);
        }
        $criteria->compare('Phone', $this->Phone, true);
        $criteria->compare('push_id', $this->push_id, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('role_name', $this->role_name);
        $criteria->compare('room', $this->room);
        $criteria->compare('intphone', $this->intphone);
        $criteria->compare('birth', $this->birth);
        $criteria->compare('department', $this->department, true);
        $criteria->compare('umanager', $this->umanager);
        $criteria->compare('position', $this->position);
        $criteria->compare('lang', $this->lang);
        $criteria->compare('active', $this->active);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('mobile', $this->mobile, true);

        if ('fullname.desc' === $_GET['CUsers_sort']) {
            $criteria->order = "`fullname`>'а',`fullname` DESC";
        } elseif ('fullname' === $_GET['CUsers_sort']) {
            $criteria->order = "`fullname`<'а',`fullname` ASC";
        }

//        if ($_SERVER['REQUEST_URI'] !== '/api/users/') {
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/users') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
                'sort' => [
                    'defaultOrder' => "`fullname`<'а',`fullname` ASC",
                ],
                'pagination' => [
                    'pageSize' => (int)Yii::app()->session['CUsersPageCount'] ? Yii::app()->session['CUsersPageCount'] : 30,
                ],
            ]);
        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => "`fullname`<'а',`fullname` ASC",
            ],
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);
    }

    public function ousearch()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $roles = Roles::uall();

        $criteria = new CDbCriteria;
        foreach ($roles as $name => $value) {
            $criteria->addSearchCondition('role', $name, true, 'OR', 'LIKE');
        }
        $criteria->compare('id', $this->id);
        $criteria->compare('fullname', $this->fullname, true);
        if (Yii::app()->user->checkAccess('systemAdmin') OR Yii::app()->user->checkAccess('systemManager')) {
            $criteria->compare('Username', $this->Username, false);
        } else {
            $criteria->compare('Username', Yii::app()->user->name, false);
        }
        $criteria->compare('Password', $this->Password, true);
        $criteria->compare('Email', $this->Email, true);
        $criteria->compare('Phone', $this->Phone, true);
        $criteria->compare('push_id', $this->push_id, true);

        $criteria->compare('role_name', $this->role_name);
        $criteria->compare('company', $this->company);
        $criteria->compare('room', $this->room);
        $criteria->compare('intphone', $this->intphone);
        $criteria->compare('birth', $this->birth);
        $criteria->compare('department', $this->department);
        $criteria->compare('umanager', $this->umanager);
        $criteria->compare('position', $this->position);
        $criteria->compare('lang', $this->lang);
        $criteria->compare('active', $this->active);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('mobile', $this->mobile, true);

        $users_data = new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['usersPageCount'] ? Yii::app()->session['usersPageCount'] : 30,
            ),
        ));
        $_SESSION['users_records'] = $users_data;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'fullname ASC',
            ),
            'pagination' => array(
                'pageSize' => (int)Yii::app()->session['usersPageCount'] ? Yii::app()->session['usersPageCount'] : 30,
            ),
        ));
    }

    public function beforeSave()
    {
        $cu = CUsers::model()->findByPk($this->id);
        $this->_oldFullname = $cu->fullname;

        $role_name = Roles::model()->findByAttributes(array('value' => $this->role));
        $this->role_name = $role_name->name;
        if ($this->isNewRecord) {
            $this->Password = md5('mdy65wtc76' . $this->Password);
        } else {
            if (isset($_POST['CUsers']['Password']) AND $_POST['CUsers']['Password'] !== '') {
                $this->Password = md5('mdy65wtc76' . $this->Password);
            } else {
                $oldpass = CUsers::model()->findByPk($this->id);
                $this->Password = $oldpass->Password;
            }
        }

        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->isNewRecord) {
            $connection = Yii::app()->db;
            if(isset(Yii::app()->params['req_columns_default']) AND !empty(Yii::app()->params['req_columns_default'])){
                $sql = "INSERT INTO `tbl_columns` (`id`, `data`) VALUES
                ('request-grid-full_" . $this->id . "', '".Yii::app()->params['req_columns_default']."'),
                ('request-grid-full2_" . $this->id . "', '".Yii::app()->params['req_columns_default']."'),
                ('request-grid_" . $this->id . "', '".Yii::app()->params['req_columns_default']."')";
            } else {
                $sql = "INSERT INTO `tbl_columns` (`id`, `data`) VALUES
                ('request-grid-full_" . $this->id . "', 'slabel||Date||EndTime||Name||cunits||fullname||mfullname||ZayavCategory_id||Priority||Действия'),
                ('request-grid-full2_" . $this->id . "', 'slabel||Date||EndTime||Name||cunits||fullname||mfullname||ZayavCategory_id||Priority||Действия'),
                ('request-grid_" . $this->id . "', 'slabel||Date||EndTime||Name||cunits||fullname||mfullname||ZayavCategory_id||Priority||Действия')";
            }

            $connection->createCommand($sql)->execute();
            $sql2 = "INSERT INTO `tbl_columns` (`id`, `data`) VALUES
            ('problems-grid_" . $this->id . "', 'slabel||date||creator||priority||category||manager||Действия'),
            ('cusers-grid_" . $this->id . "', 'photo||fullname||city||department||position||Email||Phone||intphone||mobile||company||Действия'),
            ('phonebook-grid_" . $this->id . "', 'fullname||city||department||position||Phone||intphone||mobile||Email')";
            $connection->createCommand($sql2)->execute();
            if (PHP_SAPI !== 'cli') {
                $message = 'User ' . Yii::app()->user->name . ' created new user account ' . $this->Username . ' with fullname ' . $this->fullname;
                Yii::log($message, 'created', 'USERS');
            }
        } else {
            if (PHP_SAPI !== 'cli') {
                $message = 'User ' . Yii::app()->user->name . ' updated user account ' . $this->Username . ' with fullname ' . $this->fullname;
                Yii::log($message, 'updated', 'USERS');
            }

            Chats::model()->updateAll(['name' => $this->fullname], "name='" . $this->_oldFullname . "'");
            Chats::model()->updateAll(['reader' => $this->fullname], "reader='" . $this->_oldFullname . "'");
        }

        return parent::afterSave();
    }

    public function afterDelete()
    {
        $connection = Yii::app()->db;
        $sql = "DELETE FROM `tbl_columns` WHERE `id` = 'request-grid-full_" . $this->id . "'";
        $sql1 = "DELETE FROM `tbl_columns` WHERE `id` = 'request-grid-full-report_" . $this->id . "'";
        $sql2 = "DELETE FROM `tbl_columns` WHERE `id` = 'request-grid_" . $this->id . "'";
        $sql3 = "DELETE FROM `tbl_columns` WHERE `id` = 'request-grid-full2_" . $this->id . "'";
        $connection->createCommand($sql)->execute();
        $connection->createCommand($sql1)->execute();
        $connection->createCommand($sql2)->execute();
        $connection->createCommand($sql3)->execute();

        $sql4 = "DELETE FROM `tbl_columns` WHERE `id` = 'problems-grid_" . $this->id . "'";
        $sql5 = "DELETE FROM `tbl_columns` WHERE `id` = 'cusers-grid_" . $this->id . "'";
        $sql6 = "DELETE FROM `tbl_columns` WHERE `id` = 'phonebook-grid_" . $this->id . "'";
        $connection->createCommand($sql4)->execute();
        $connection->createCommand($sql5)->execute();
        $connection->createCommand($sql6)->execute();

        $sql7 = "DELETE FROM `tbl_columns` WHERE `id` = 'assets-grid_" . $this->id . "'";
        $sql8 = "DELETE FROM `tbl_columns` WHERE `id` = 'cunits-grid_" . $this->id . "'";
        $sql9 = "DELETE FROM `tbl_columns` WHERE `id` = 'companies-grid_" . $this->id . "'";
        $connection->createCommand($sql7)->execute();
        $connection->createCommand($sql8)->execute();
        $connection->createCommand($sql9)->execute();
        $message = 'User ' . Yii::app()->user->name . ' deleted user account ' . $this->Username . ' with fullname ' .$this->fullname;
        Yii::log($message, 'deleted', 'USERS');

        @unlink(__DIR__ . '/../../media/userphoto/' . $this->id . '.png');

        return parent::afterDelete();
    }
}
