<?php

/**
 * This is the model class for table "service".
 *
 * The followings are the available columns in table 'service':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $sla
 * @property string $priority
 * @property string $manager
 * @property string $watcher
 * @property string $matching
 * @property string $matchings
 * @property string $matchingNames
 * @property int $category_id
 * @property int $checklist_id
 * @property bool $outsource
 *
 * @property Escalates[] $escalates
 * @property ServiceCategories $category
 * @property Checklists $checklist
 *
 * @property integer $shared
 */
class Service extends CActiveRecord
{
    public $fieldset;
    public $support_services;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Service the static model class
     */
    public $manager_name;
    public $autoinwork;

    /**
     * @return array
     */
    public static function all()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `service` ORDER BY name ASC';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['id']] = $one['name'];
        }
        return $array;
    }

    /**
     * @return array
     */
    public static function slas($service)
    {
        function get_id($n)
        {
            return ($n['id']);
        }

        $connection = Yii::app()->db;
        $service = Service::model()->findByPk(2);
        $las = Sla::model()->findAllByPk(explode(",", $service['sla']));
        $sla_ids = array_map('get_id', $las);
        // $service_ids = [1,2,3];
        $slas = 'SELECT * FROM `sla` WHERE id IN (' . implode(',', array_map('intval', $sla_ids)) . ') ORDER BY name ASC';
        // var_dump($slas);
        // die();
        // $sql = 'SELECT * FROM `service` ORDER BY name ASC';
        $models = $connection->createCommand($slas)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['id']] = $one['name'];
        }
        return $array;
    }

    /**
     * @return array
     */
    public function get_available_support_services()
    {
        if (!$this->id){
            return array();
        }
        $connection = Yii::app()->db;
        // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        $sql = 'SELECT `id`, `name` FROM `service` WHERE `category_id` = "1" AND `id` NOT IN (SELECT `support_service` FROM `service_user_support` WHERE `user_service` = '.$this->id.')';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['id']] = $one['name'];
        }
        return $array;
    }
    /**
     * @return array
     */
    public function get_support_services()
    {
        if (!$this->id){
            return array();
        }
        $connection = Yii::app()->db;
        // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        $sql = 'SELECT `se`.`id`, `se`.`name` FROM `service_user_support` AS `ss` LEFT JOIN `service` AS `se` ON `ss`.`support_service` = `se`.`id` WHERE `ss`.`user_service` = '.$this->id.' AND `se`.`category_id` = 1;';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['id']] = $one['name'];
        }
        return $array;
    }

     /**
     * @return array
     */
    public function get_user_services()
    {
        if (!$this->id){
            return array();
        }
        $connection = Yii::app()->db;
        // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        // $sql = 'SELECT `se`.`id`, `se`.`name` FROM `service_user_support` AS `ss` LEFT JOIN `service` AS `se` ON `ss`.`support_service` = `se`.`id` WHERE `ss`.`support_service` = '.$this->id.' AND `se`.`target_type` = "user-service";';
        $sql = 'SELECT `se`.`id`, `se`.`name` 
        FROM `service_user_support` AS `ss` 
        LEFT JOIN `service` AS `se` ON `se`.`id` = `ss`.`user_service`  
        WHERE `ss`.`support_service` = '.$this->id.' AND `se`.`category_id` = 2;';
        // WHERE `ss`.`support_service` = '.$this->id.' AND `se`.`target_type` = "user-service";';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one['id']] = $one['name'];
        }
        // var_dump($sql);
        return $array;
    }

    /**
     * @return bool
     */
    public function add_support_service($id)
    {
        $connection = Yii::app()->db;
        // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        // $sql = 'SELECT `se`.`id`, `se`.`name` FROM `service_user_support` AS `ss` LEFT JOIN `service` AS `se` ON `ss`.`support_service` = `se`.`id` WHERE `ss`.`user_service` = '.$this->id.' AND `se`.`target_type` = "support-service";';
        $sql = 'INSERT INTO `service_user_support` (`user_service`, `support_service`) VALUES ('.$this->id.', '.$id.')';
        $connection->createCommand($sql)->execute();

        return true;
    }

        /**
     * @return bool
     */
    public function remove_support_service($id)
    {
        $connection = Yii::app()->db;
        // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        // $sql = 'SELECT `se`.`id`, `se`.`name` FROM `service_user_support` AS `ss` LEFT JOIN `service` AS `se` ON `ss`.`support_service` = `se`.`id` WHERE `ss`.`user_service` = '.$this->id.' AND `se`.`target_type` = "support-service";';
        // $sql = 'INSERT INTO `service_user_support` (`user_service`, `support_service`) VALUES ('.$this->id.', '.$id.')';
        $sql = 'DELETE FROM `service_user_support` WHERE `user_service` = '.$this->id.' AND `support_service` = '.$id.'';
        $connection->createCommand($sql)->execute();

        return true;
    }



    /**
     * @param null $category_id
     * @return array
     */
    public static function getAllShared($category_id = null)
    {
        if ($category_id) {
            return CHtml::listData(Service::model()->findAllByAttributes([
                'shared' => '1',
                'category_id' => $category_id
            ]), 'id', 'name');
        }

        return CHtml::listData(Service::model()->findAllByAttributes(['shared' => '1']), 'id', 'name');
    }

    /**
     * @return array
     */
    public static function sall()
    {
        $models = self::model()->findAll();
        $array = array();
        foreach ($models as $one) {
            $array[$one->name] = $one->name;
        }
        return $array;
    }

    /**
     * @param string $className
     * @return CActiveRecord|mixed
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
        return 'service';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['sla, priority, manager, manager_name, matchings, group, gtype', 'length', 'max' => 200],
            ['name', 'length', 'max' => 100],
            ['watcher', 'length', 'max' => 500],
            ['description', 'length', 'max' => 100],
            ['content', 'length', 'max' => 2000],
            ['name, availability, priority', 'required'],
            // ['sla', 'sla_required'],
            ['shared, autoinwork, category_id, checklist_id', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [
                'id, name, description, sla, priority, manager, manager_name, watcher, matchings, group, gtype, fieldset, shared',
                'safe'
            ],
            [
                'id, name, description, sla, priority, manager, manager_name, watcher, matchings,  group, gtype, fieldset, shared',
                'safe',
                'on' => 'search'
            ],
            ['name, description', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
        ];
    }

    public function sla_required($attribute_name, $params)
    {
        // echo '<pre>';
        // var_dump($this);
        // echo '</pre>';
        // die();
        if ($this->sla == "") {
            $this->addError($attribute_name, Yii::t('user', 'At least 1 of the field must be filled up properly'));

            return false;
        }

        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'service_history' => array(self::HAS_MANY, 'ServiceHistory', 'sid'),
            'zayavkis' => [self::HAS_MANY, 'Request', 'service_id'],
            'escalates' => [self::HAS_MANY, 'Escalates', 'service_id'],
            'category' => [self::BELONGS_TO, 'ServiceCategories', 'category_id'],
            'checklist' => [self::BELONGS_TO, 'Checklists', 'checklist_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('main-ui', 'Name'),
            'description' => Yii::t('main-ui', 'Ticket subject'),
            'sla' => Yii::t('main-ui', 'Service level'),
            'priority' => Yii::t('main-ui', 'Priority'),
            'manager' => Yii::t('main-ui', 'Manager'),
            'manager_name' => Yii::t('main-ui', 'Manager'),
            'watcher' => Yii::t('main-ui', 'Watcher'),
            'matchings' => Yii::t('main-ui', 'Matching'),
            'matchingNames' => Yii::t('main-ui', 'Matching'),
            'group' => Yii::t('main-ui', 'Group'),
            'gtype' => Yii::t('main-ui', 'Manager type'),
            'target_type' => Yii::t('main-ui', 'Target type'),
            'availability' => Yii::t('main-ui', 'Availability %'),
            'fieldset' => Yii::t('main-ui', 'Fieldset'),
            'content' => Yii::t('main-ui', 'Content'),
            'shared' => Yii::t('main-ui', 'Shared'),
            'category_id' => Yii::t('main-ui', 'Service category'),
            'autoinwork' => Yii::t('main-ui', 'Make tiket in work when you open'),
            'checklist_id' => Yii::t('main-ui', 'Checklist'),
            'outsource' => Yii::t('main-ui', 'Source'),
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('sla', $this->sla, true);
        $criteria->compare('priority', $this->priority, true);
        $criteria->compare('manager', $this->manager, true);
        $criteria->compare('group', $this->group, true);
        $criteria->compare('gtype', $this->gtype, true);
        $criteria->compare('manager_name', $this->manager_name, true);
        $criteria->compare('availability', $this->availability, true);
        $criteria->compare('fieldset', $this->fieldset, true);
        $criteria->compare('target_type', $this->target_type, true);
        $criteria->compare('outsource', $this->outsource, true);
        

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['servicesPageCount'] ? Yii::app()->session['servicesPageCount'] : 30,
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getMatchingNames()
    {
        $names = [];
        if($this->matchings) {
            foreach (explode(',', $this->matchings) as $userId) {
                /** @var CUsers $user */
                $user = CUsers::model()->findByPk($userId);
                $names[] = $user->fullname;
            }

            return implode(',', $names);
        }

        return null;
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        // if ($this->category_id == '2'){
        //     $connection = Yii::app()->db;
        //     // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        //     $sql = 'DELETE FROM `service_user_support` WHERE `user_service` = '.$this->id;
        //     $connection->createCommand($sql)->execute();
        // }

        // if ($this->category_id == '1'){
        //     $connection = Yii::app()->db;
        //     // select * from service_user_support as ss left join service as se on ss.support_service = se.id  where ss.user_service = 1 and se.target_type = 'support-service';
        //     $sql = 'DELETE FROM `service_user_support` WHERE `support_service` = '.$this->id;
        //     $connection->createCommand($sql)->execute();

        //     $this->sla = NULL;
        // }
        
        if ($this->gtype == 2) {
            $this->manager = null;
            $this->manager_name = null;
        } else {
            $manager_name = CUsers::model()->findByAttributes(['Username' => $this->manager]);
            $this->manager_name = $manager_name->fullname;
            $this->group = null;
        }
        if ($_POST['watcher']) $this->watcher = implode(',', $_POST['watcher']);
        if ($_POST['matchings']) $this->matchings = implode(',', $_POST['matchings']);
        $this->fieldset = $_POST['Service']['fieldset'];
        $this->target_type = $_POST['Service']['target_type'];
        $this->outsource = $_POST['Service']['outsource'];
        // $this->sla = $_POST['sla'];
        if ($_POST['sla']) $this->sla = implode(",", $_POST['sla']);
        // var_dump($_POST['sla']);
        // die();

    if (!$this->isNewRecord) {

        $creator_id = Yii::app()->user->id;
        $olddata = Service::model()->findByPk($this->id);
        if ($olddata->name !== $_POST['Service']['name']) {
            $this->addHistory(Yii::t('main-ui', 'Service name changed:'). ' ' . $this->name, $creator_id);
        }
        if ($_POST['Service']['checklist_id'] !== "" && $olddata->checklist_id !== $_POST['Service']['checklist_id']) {
            $this->addHistory(Yii::t('main-ui', 'Checklist changed:'). ' '  . Checklists::model()->findByPk($this->checklist_id)->name, $creator_id);
        }
        if ($_POST['Service']['checklist_id'] == "" && strval($olddata->checklist_id) !== $_POST['Service']['checklist_id']) {
            $this->addHistory(Yii::t('main-ui', 'Checklist changed:'). ' '  . Yii::t('main-ui', 'Field cleared'), $creator_id);
        }
        if ($olddata->sla !== $_POST['Service']['sla'] && $_POST['Service']['category_id'] !== "1") {
            $this->addHistory(Yii::t('main-ui', 'SLA changed:'). ' '  . $this->sla, $creator_id);
        }
        if ($olddata->gtype !== $_POST['Service']['gtype']) {
            $gt = $this->gtype == "1" ? Yii::t('main-ui', 'User') : Yii::t('main-ui', 'Group');
            $this->addHistory(Yii::t('main-ui', 'Gtype changed:') . ' ' . $gt  , $creator_id); 
        }
        if ($olddata->group !== $_POST['Service']['group'] && $this->group !== null) {
            $this->addHistory(Yii::t('main-ui', 'Group changed:'). ' '  . $this->group, $creator_id);
        }
        if ($olddata->priority !== $_POST['Service']['priority']) {
            $this->addHistory(Yii::t('main-ui', 'Priority changed:'). ' ' . $this->priority, $creator_id);
        }
        if ($olddata->manager !== $_POST['Service']['manager'] && $this->manager !== null) {
            $this->addHistory(Yii::t('main-ui', 'Manager changed:'). ' '  . $this->manager, $creator_id);
        }
        if ($this->watcher && $olddata->watcher !== implode(',', $_POST['watcher'])) {
            $this->addHistory(Yii::t('main-ui', 'Watcher changed:'). ' '  . $this->watcher, $creator_id);
        }
        if ($this->matchings && $olddata->matchings !== implode(',', $_POST['matchings'])) {
            $this->addHistory(Yii::t('main-ui', 'Matchings changed:'). ' '  . $this->matchingNames, $creator_id);
        }
        if ($olddata->availability !== $_POST['Service']['availability']) {
            $this->addHistory(Yii::t('main-ui', 'Availability changed:'). ' '  . $this->availability, $creator_id);
        }
        if ($olddata->category_id !== $_POST['Service']['category_id']) {
            $this->addHistory(Yii::t('main-ui', 'Category changed:'). ' '  . ServiceCategories::model()->findByPk($this->category_id)->name, $creator_id);
        }
        if ($olddata->outsource !== $_POST['Service']['outsource']) {
            $os = $this->outsource == "1" ? Yii::t('main-ui', 'Outsource true') :  Yii::t('main-ui', 'Outsource false');
            $this->addHistory(Yii::t('main-ui', 'Outsource changed:'). ' ' . $os , $creator_id);
        }

        $auto_in_work = $_POST['Service']['autoinwork'];
        if ($olddata->autoinwork !== $auto_in_work) {
            $os = $auto_in_work== 1 ? Yii::t('main-ui', 'Autoinwork true') :  Yii::t('main-ui', 'Autoinwork false');
            $this->addHistory(Yii::t('main-ui', 'Autoinwork changed:'). ' ' . $os , $creator_id);
        }

        $shared = $_POST['Service']['shared'];
        if ($olddata->shared !== $shared) {
            $os = $shared == 1 ? Yii::t('main-ui', 'Shared true') :  Yii::t('main-ui', 'Shared false');
            $this->addHistory(Yii::t('main-ui', 'Shared changed:'). ' ' . $os , $creator_id);
        }

    }

        return parent::beforeSave();
    }

    public function afterSave(){
        $creator_id = Yii::app()->user->id;
        if ($this->isNewRecord) {
            $this->addHistory(Yii::t('main-ui', 'Service created!'), $creator_id);
        }

        return parent::afterSave();
    }

    public function addHistory($action, $user)
    {
        $service_history = new ServiceHistory();
        $service_history->sid = $this->id;
        $service_history->date = date("Y-m-d H:i:s");
        $service_history->user = $user;
        $service_history->user_name = CUsers::model()->findByPk($user)->fullname;
        $service_history->action = $action;
        $service_history->save(false);
    }

}
