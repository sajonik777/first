<?php

/**
 * This is the model class for table "zstatus".
 *
 * The followings are the available columns in table 'zstatus':
 * @property integer $id
 * @property string $name
 * @property integer $enabled
 * @property string $label
 * @property string $tag
 * @property integer $close
 * @property integer $notify_user
 * @property integer $notify_user_sms
 * @property integer $notify_manager
 * @property integer $notify_manager_sms
 * @property integer $notify_group
 * @property integer $notify_matching
 * @property integer $notify_matching_sms
 * @property string $sms
 * @property string $message
 * @property string $msms
 * @property string $mmessage
 * @property string $gmessage
 * @property integer $hide
 * @property integer $freeze
 * @property integer $show
 * @property integer $is_need_comment
 *
 * @property string $mwsms
 * @property string $mwmessage
 *
 */
class Status extends CActiveRecord
{
    public $sort_id;
    public $is_need_comment;
    public $is_need_rating;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'zstatus';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, tag, sms, message, msms, mmessage, mwsms, mwmessage', 'required'],
            [
                'enabled, close, notify_user, notify_manager, notify_user_sms, notify_manager_sms, notify_group, notify_matching, notify_matching_sms, freeze, hide, show, is_need_comment, is_need_rating',
                'numerical',
                'integerOnly' => true
            ],
            [
                'name, sms, message, mmessage, mwsms, mwmessage, gmessage, matching_message, matching_sms',
                'length',
                'max' => 50
            ],
            ['label', 'length', 'max' => 400],
            ['tag', 'length', 'max' => 100],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            [
                'id, name, enabled, label, tag, close, notify_user, notify_manager, notify_matching, notify_matching_sms, notify_group, matching_message, matching_sms, sms, message, mwsms, mwmessage, is_need_comment, is_need_rating',
                'safe',
                'on' => 'search'
            ],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'roles_rl' => [self::MANY_MANY, 'Roles', 'zstatus_to_roles(zstatus_id, roles_id)'],
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
            'enabled' => Yii::t('main-ui', 'Active'),
            'label' => Yii::t('main-ui', 'Label'),
            'tag' => Yii::t('main-ui', 'Label color'),
            'close' => Yii::t('main-ui', 'Ticket action'),
            'notify_user' => Yii::t('main-ui', 'Notify customer by E-mail'),
            'notify_user_sms' => Yii::t('main-ui', 'Notify customer by SMS'),
            'notify_manager' => Yii::t('main-ui', 'Notify manager by E-mail'),
            'notify_manager_sms' => Yii::t('main-ui', 'Notify manager by SMS'),
            'notify_group' => Yii::t('main-ui', 'Notify observers by E-mail'),
            'notify_matching' => Yii::t('main-ui', 'Notify matching by E-mail'),
            'notify_matching_sms' => Yii::t('main-ui', 'Notify matching by SMS'),
            'sms' => Yii::t('main-ui', 'Customer SMS template'),
            'message' => Yii::t('main-ui', 'Customer E-mail template'),
            'msms' => Yii::t('main-ui', 'Manager SMS template'),
            'mmessage' => Yii::t('main-ui', 'Manager E-Mail template'),
            'mwsms' => Yii::t('main-ui', 'SMS template warning'),
            'mwmessage' => Yii::t('main-ui', 'E-Mail template warning'),
            'gmessage' => Yii::t('main-ui', 'Observer E-Mail template'),
            'matching_message' => Yii::t('main-ui', 'Matching E-Mail template'),
            'matching_sms' => Yii::t('main-ui', 'Matching SMS template'),
            'hide' => Yii::t('main-ui', 'Hide from Dashboard'),
            'freeze' => Yii::t('main-ui', 'Freeze ticket'),
            'show' => Yii::t('main-ui', 'Display on graph'),
            'is_need_comment' => Yii::t('main-ui', 'Need comment'),
            'is_need_rating' => Yii::t('main-ui', 'Need to rate'),
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
        $criteria->compare('enabled', $this->enabled);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('tag', $this->tag, true);
        $criteria->compare('close', $this->close);
        $criteria->compare('notify_user', $this->notify_user);
        $criteria->compare('notify_manager', $this->notify_manager);
        $criteria->compare('notify_user_sms', $this->notify_user);
        $criteria->compare('notify_manager_sms', $this->notify_manager);
        $criteria->compare('notify_group', $this->notify_group);
        $criteria->compare('notify_matching', $this->notify_matching);
        $criteria->compare('notify_matching_sms', $this->notify_matching_sms);
        $criteria->compare('sms', $this->sms, true);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('msms', $this->msms, true);
        $criteria->compare('mmessage', $this->mmessage, true);
        $criteria->compare('mwsms', $this->mwsms, true);
        $criteria->compare('mwmessage', $this->mwmessage, true);
        $criteria->compare('gmessage', $this->gmessage, true);
        $criteria->compare('matching_message', $this->matching_message, true);
        $criteria->compare('matching_sms', $this->matching_sms, true);
        $criteria->compare('freeze', $this->freeze);
        $criteria->compare('hide', $this->hide);
        $criteria->compare('show', $this->show);
        $criteria->compare('is_need_comment', $this->is_need_comment);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Status the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public static function all()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `zstatus` `t` WHERE `t`.`enabled`=1';
        $models = $connection->createCommand($sql)->queryAll();
        $array = [];
        foreach ($models as $one) {
            $array[$one['name']] = $one['name'];
        }

        return $array;
    }

    /**
     * @return array
     */
    public static function KeyAll()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `zstatus` `t` WHERE `t`.`enabled`=1';
        $models = $connection->createCommand($sql)->queryAll();
        $array = [];
//        $array[0] = Yii::t('main-ui', 'All');
        foreach ($models as $one) {
            $array[(int)$one['id']] = $one['name'];
        }

        return $array;
    }

    /**
     * @return array
     */
    public static function gall()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `zstatus` `t` WHERE `t`.`enabled`=1';
        $models = $connection->createCommand($sql)->queryAll();
        $array = [];
        foreach ($models as $one) {
            $array[] = ['label' => $one['name']];
        }

        return $array;
    }

    /**
     * @return array
     */
    public static function fall()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT * FROM `zstatus` `t` WHERE `t`.`enabled`=1';
        $models = $connection->createCommand($sql)->queryAll();
        $array = [];
        foreach ($models as $one) {
            $array[$one['label']] = $one['name'];
        }

        return $array;
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: ' . $this->tag . '; vertical-align: baseline; white-space: nowrap; border: 1px solid ' . $this->tag . '; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $this->name . '</span>';

        return parent::beforeSave();
    }


    public static function countRequests()
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT `zs`.`name` as `status`, `zs`.`tag` as `color`, `zs`.`show` as `show`, count(`re`.`id`) as `requests_count`
                FROM `zstatus` as `zs`
                LEFT JOIN `request` as `re`
                ON `zs`.`name` = `re`.`Status`
                GROUP BY `zs`.`name`';
        $models = $connection->createCommand($sql)->queryAll();
        $array = array();

        foreach ($models as $one) {

            $name = $one['status'];
            $value = intval($one['requests_count']);

            if ($name !== 'Выполнена' && $name !== 'Завершена' && $one['show']) {
                array_push($array, array(
                    'name' => $name,
                    'y' => $value,
                    'color' => $one['color'],
                ));
            }
        }
        return $array;
    }
}
