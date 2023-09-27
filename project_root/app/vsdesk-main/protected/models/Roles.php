<?php

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles':
 * @property integer $id
 * @property string $name
 */
class Roles extends CActiveRecord
{
    public $copyfrom;

    public static
    function all()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->value] = $one->name;
        }
        return $array;
    }

    public static
    function idall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->id] = $one->name;
        }
        return $array;
    }
    public static
    function fall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Roles the static model class
     */
    public static
    function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static
    function RolesAll()
    {
        $mroles = RolesRights::model()->findAllByAttributes(array('name' => 'systemAdmin', 'value' => 1));
        $criteria2 = new CDbCriteria;
        $roles_arr = array(NULL);
        foreach ($mroles as $mrole) {
            $roles_arr[] = $mrole->rname;
        }
        $roles = array_filter(array_unique($roles_arr));
        foreach ($roles as $key => $value) {
            $criteria2->condition = 'name != "' . $value . '"';
        }
        $models = self::model()->findAll($criteria2);
        $array = array();
        foreach ($models as $one) {
            $array[] = $one->name;
        }
        return $array;
    }

    public static
    function muAll()
    {
        $mroles = RolesRights::model()->findAllByAttributes(array('name' => 'systemAdmin', 'value' => 1));
        $criteria2 = new CDbCriteria;
        $roles_arr = array(NULL);
        foreach ($mroles as $mrole) {
            $roles_arr[] = $mrole->rname;
        }
        $roles = array_filter(array_unique($roles_arr));
        foreach ($roles as $key => $value) {
            $criteria2->condition = 'name != "' . $value . '"';
        }
        $models = self::model()->findAll($criteria2);
        $array = array();
        foreach ($models as $one) {
            $array[$one->value] = $one->name;
        }
        return $array;
    }

    public static
    function uall()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'name = "systemUser"';
        $mroles = RolesRights::model()->findAllByAttributes(array('value' => 1), $criteria);
        $roles_arr = array(NULL);
        foreach ($mroles as $mrole) {
            $r_name = Roles::model()->findByAttributes(array('name'=>$mrole->rname));
            $roles_arr[$r_name->value] = $mrole->rname;
        }

        $roles = array_unique($roles_arr);

        return array_filter($roles);
    }

    /**
     * @return string the associated database table name
     */
    public
    function tableName()
    {
        return 'roles';
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
            array('name, value', 'required'),
            array('value, name', 'unique', 'message' => 'Роль с таким именем существует'),
            array('name, value', 'length', 'max' => 50),
            array('value', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/',
                'message' => Yii::t('main-ui', 'Only Latin letters and numbers without spaces')),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
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
        return array(
            'role_rights' => array(self::HAS_MANY, 'RolesRights', 'rid'),
            'status_rl'=>array(self::MANY_MANY, 'Status', 'zstatus_to_roles(roles_id, zstatus_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public
    function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'value' => Yii::t('main-ui', 'Role'),
            'copyfrom' => Yii::t('main-ui', 'Copy rights from:'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('value', $this->value, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC'
            ),
        ));
    }

    public function managersAll($criteria = NULL)
    {
        $mroles = RolesRights::model()->findAllByAttributes(array('name' => 'systemManager', 'value' => 1));
        $roles_arr = array(NULL);
        $managers = array(NULL);
        foreach ($mroles as $mrole) {
            $roles_arr[] = $mrole->rname;
        }
        $roles = array_filter(array_unique($roles_arr));
        foreach ($roles as $role) {
            $managers[] = CUsers::model()->findAllByAttributes(array('role_name' => $role), $criteria);
        }
        return array_filter($managers);
    }

    public function usersAll($criteria = NULL)
    {
        $mroles = RolesRights::model()->findAllByAttributes(array('name' => 'systemUser', 'value' => 1));
        $roles_arr = array(NULL);
        $users = array(NULL);
        $users_list = array(NULL);
        foreach ($mroles as $mrole) {
            $roles_arr[] = $mrole->rname;
        }
        $roles = array_filter(array_unique($roles_arr));
        foreach ($roles as $role) {
            $users[] = CUsers::model()->findAllByAttributes(array('role_name' => $role),$criteria);
        }
        return array_filter($users);
    }

    public function noadminAll($criteria = NULL)
    {
        $mroles1 = RolesRights::model()->findAllByAttributes(array('name' => 'systemUser', 'value' => 1));
        $mroles2 = RolesRights::model()->findAllByAttributes(array('name' => 'systemManager', 'value' => 1));
        $mroles = array_merge($mroles1, $mroles2);
        $roles_arr = array(NULL);
        $users = array(NULL);
        $users_list = array(NULL);
        foreach ($mroles as $mrole) {
            $roles_arr[] = $mrole->rname;
        }
        $roles = array_filter(array_unique($roles_arr));
        foreach ($roles as $role) {
            $users[] = CUsers::model()->findAllByAttributes(array('role_name' => $role), $criteria);
        }
        return array_filter($users);

    }

    public function beforeSave()
    {
        if (!$this->isNewRecord) {
            $rname = Roles::model()->findByPk($this->id);

            //обновляем в справочнике пользователей роль
            if ($rname->name !== $_POST['Roles']['name'] OR $rname->value !== $_POST['Roles']['value']){
              $users = CUsers::model()->findAllByAttributes(array('role' => $rname->value));
              foreach ($users as $user) {
                  CUsers::model()->updateByPk($user->id, array('role_name' => $_POST['Roles']['name'], 'role' => $_POST['Roles']['value']));
              }
            }

            if ($rname->name !== $_POST['Roles']['name']){
              $connection = Yii::app()->db;
              //обновляем в категории базы знанй доступ
              $cat= 'SELECT * FROM `bcats` WHERE `access` LIKE \'%'.$rname->name.'%\'';
              $cats = $connection->createCommand($cat)->queryAll();
              foreach ($cats as $cat) {
                if (isset($cat['access'])){
                  $watchers = explode(',', $cat['access']);
                  $newwatcher = array();
                  foreach ($watchers as $watcher) {
                    if ($watcher == $rname->name){
                      $newwatcher[] = $_POST['Roles']['name'];
                    } else {
                      $newwatcher[] = $watcher;
                    }
                  }
                  Categories::model()->updateByPk($cat['id'], array('access' => implode(',', $newwatcher)));
                }
              }
              //обновляем в записях базы знанй доступ
              $req= 'SELECT * FROM `brecords` WHERE `access` LIKE \'%'.$rname->name.'%\'';
              $records = $connection->createCommand($req)->queryAll();
              foreach ($records as $record) {
                if (isset($record['access'])){
                  $watchers = explode(',', $record['access']);
                  $newwatcher = array();
                  foreach ($watchers as $watcher) {
                    if ($watcher == $rname->name){
                      $newwatcher[] = $_POST['Roles']['name'];
                    } else {
                      $newwatcher[] = $watcher;
                    }
                  }
                  Knowledge::model()->updateByPk($record['id'], array('access' => implode(',', $newwatcher)));
                }
              }
            }
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        if ($this->isNewRecord) {
            if (isset($_POST['Roles']['copyfrom'])){
                $role_arr = RolesRights::model()->findAllByAttributes(array('rid' => $_POST['Roles']['copyfrom']));
                foreach ($role_arr as $value) {
                        $rights = new RolesRights();
                        $rights->rid = $this->id;
                        $rights->rname = $_POST['Roles']['name'];
                        $rights->name = $value->name;
                        $rights->description = $value->description;
                        $rights->category = $value->category;
                        $rights->value = $value->value;
                        $rights->save(false);
                }
            }else{
                $roles = Yii::app()->authManager->roles;
                foreach ($roles as $role => $value) {
                    if ($role !== 'guest') {
                        $rights = new RolesRights();
                        $rights->rid = $this->id;
                        $rights->rname = $_POST['Roles']['name'];
                        $rights->name = $role;
                        $rights->description = $value->description;
                        $rights->category = $value->data;
                        $rights->value = 0;
                        $rights->save(false);
                    }
                }
            }
        } else {
            if (isset($_POST['Roles']['name'])) {
                $rights = $this->role_rights;
                foreach ($rights as $value) {
                    RolesRights::model()->updateByPk($value->id, array('rname' => $_POST['Roles']['name']));
                }
            }
        }
        return parent::afterSave();
    }
}
