<?php

/**
 * This is the model class for table "request".
 *
 * The followings are the available columns in table 'request':
 * @property integer $id
 * @property integer $pid
 * @property string $child
 * @property string $channel
 * @property string $channel_icon
 * @property string $Name
 * @property string $Type
 * @property string $ZayavCategory_id
 * @property string $KE_type
 * @property string $Date
 * @property string $StartTime
 * @property string $fStartTime
 * @property string $EndTime
 * @property string $fEndTime
 * @property string $Status
 * @property string $slabel
 * @property string $Priority
 * @property string $Managers_id
 * @property string $CUsers_id
 * @property string $phone
 * @property string $room
 * @property string $Address
 * @property string $company
 * @property string $Content
 * @property string $Comment
 * @property string $cunits
 * @property string $closed
 * @property integer $service_id
 * @property string $service_name
 * @property string $image
 * @property string $timestamp
 * @property string $timestampStart
 * @property string $timestampfStart
 * @property string $timestampEnd
 * @property string $timestampfEnd
 * @property string $fullname
 * @property string $mfullname
 * @property string $gfullname
 * @property string $depart
 * @property string $creator
 * @property string $watchers
 * @property string $matching
 * @property string $update_by
 * @property string $correct_timestamp
 * @property integer $rating
 * @property string $lead_time
 * @property string $leaving
 * @property integer $contractors_id
 * @property string $re_leaving
 * @property string $groups_id
 * @property string $fields_history
 * @property string $key
 * @property integer $delayed_start
 * @property integer $delayed_end
 * @property string $timestampClose
 * @property string $delayedHours
 * @property string $lastactivity
 * @property string $tchat_id
 * @property string $paused
 * @property integer $previous_paused_status_id
 * @property integer $paused_total_time
 *
 * @property string $msbot_id
 * @property string $msbot_params
 *
 * The followings are the available model relations:
 * //@property Comments[] $comments
 * //@property History[] $histories
 * @property RequestFields[] $requestFields
 * @property Files[] $reqFiles
 *
 * @property array $files
 * @property integer $newComments
 */
class RequestFullFields extends CActiveRecord
{
    /**
     * @var array
     */
    private $_attributes = [];

    /**
     * @var array
     */
    public $fields = [];

    /**
     * @var array
     */
    public $field_keys = [];


    /**
     * @var array
     */
    private $_files = [];

    /**
     * @var int
     */
    private $_newComments = 0;

    public $Comment = 0;

    /**
     * @var array
     */
    private $_fullAttributes = [];

    /**
     * @var bool
     */
    public $saveFilter = false;

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (isset($this->$name)) {
            parent::__set($name, $value);
        } else {
            $this->_attributes[$name] = $value;
        }
    }

    /**
     * @return array
     */
    public function getFullAttributes()
    {
        return $this->_fullAttributes;
    }

    public function setFullAttributes($value)
    {
        return $this->_fullAttributes = $value;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $field_keys = ' ';
        if (!empty($this->field_keys)) {
            foreach ($this->field_keys as $value) {
                $field_keys .= ', ' . $value;
            }
        }

        return [
            [
                'Type, Date, StartTime, EndTime, depart, fStartTime, fEndTime, Priority, Managers_id, CUsers_id, service_name, company, closed, update_by, room, phone, matching, paused',
                'length',
                'max' => 50
            ],
            ['ZayavCategory_id', 'length', 'max' => 100],
            ['KE_type', 'length', 'max' => 100],
            ['Name, tchat_id', 'length', 'max' => 100],
            ['Address', 'length', 'max' => 250],
            ['key', 'length', 'max' => 32],
            ['fullname, creator, channel, channel_icon', 'length', 'max' => 100],
            ['slabel, Status, child', 'length', 'max' => 400],
            ['cunits', 'length', 'max' => 500, 'on' => 'update'],
            ['watchers', 'length', 'max' => 500, 'on' => 'update'],
            ['image', 'length', 'max' => 250],
            ['leaving, re_leaving', 'length', 'max' => 1],
            (Yii::app()->user->checkAccess('canEditContent') or Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) ? [
                'Content',
                'required',
                'on' => 'update'
            ] : ['Name', 'required', 'on' => 'update'],
            (Yii::app()->user->checkAccess('liteformRequest') and Yii::app()->user->checkAccess('systemUser')) ? [
                'Name, Content, CUsers_id',
                'required',
                'on' => 'insert'
            ] : ['Name, Content, CUsers_id, service_id', 'required', 'on' => 'insert'],
            [
                'rating, contractors_id, delayedHours, previous_paused_status_id, paused_total_time',
                'numerical',
                'integerOnly' => true
            ],
            [
                'Name, Content, slabel, Comment, StartTime, fStartTime, EndTime, fEndTime, service_id, cunits, update_by, gfullname, mfullname, timestamp, id, pid, image, rating, groups_id, correct_timestamp, lead_time, leadTimeEx, watchers, delays, delayedHours, last_comment, pendingTime, files, company, saveFilter, tchat_id, paused, previous_paused_status_id, paused_total_time',
                'safe'
            ],
            ['Name, Content, Comment', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
            [
                'id, Name, mfullname, gfullname, Type, ZayavCategory_id, KE_type, Date, lastactivity, timestamp, timestampStart, timestampfStart, timestampEnd, timestampfEnd,  StartTime, EndTime, fStartTime, fEndTime, Status, slabel, creator, Priority, Managers_id, CUsers_id, service_name, service_id, Address, company, Content, Comment, closed, watchers, matching, cunits, rating, lead_time, leadTimeEx, leaving, contractors_id, re_leaving, groups_id, correct_timestamp, delayed_start, delayed_end, delays, delayedHours, child, last_comment, pendingTime, tchat_id, channel, channel_icon, paused, previous_paused_status_id, paused_total_time' . $field_keys,
                'safe',
                'on' => 'search'
            ]
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
            'groups_rl' => array(self::BELONGS_TO, 'Groups', 'groups_id'),
            'requestFields' => [self::HAS_MANY, 'RequestFields', 'rid'],
            'reqFiles' => [self::MANY_MANY, 'Files', 'request_files(request_id, file_id)'],
        ];
    }

    /**
     * @return int
     */
    public function getNewComments()
    {

        if (Yii::app()->user->checkAccess('systemUser')) {
            $comms = Comments::model()->findAllByAttributes(array('rid' => $this->id, 'show' => '0'));
        } else {
            $comms = Comments::model()->findAllByAttributes(array('rid' => $this->id));
        }

        $totalNew = 0;
        foreach ($comms as $comm) {
            if (!$comm->read) {
                $totalNew++;
            }
        }

        $this->_newComments = $totalNew;

        return $this->_newComments;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        if ($this->isNewRecord) {
            return $this->_files;
        }

        return $this->getAttachments();
    }

    /**
     * @param array $value
     */
    public function setFiles($value)
    {
        if (!empty($value)) {
            $this->_files = $value;
        }
    }

    /**
     * @return array
     */
    private function getAttachments()
    {
        $attachments = [];
        if (!empty($this->reqFiles)) {
            foreach ($this->reqFiles as $file) {
                /** @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->preLoadFields();
    }

    /**
     *
     */
    public function preLoadFields()
    {
        $fieldsets_fields = Yii::app()->db->createCommand('SELECT id, name FROM fieldsets_fields')->queryAll();
        foreach ($fieldsets_fields as $field) {
            $this->__set('ff_id_' . $field['id'], null);
            $this->field_keys[$field['id']] = 'ff_id_' . $field['id'];
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        if ($this->newComments != 0) {
            $this->Comment = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #666; vertical-align: baseline; white-space: nowrap; border: 1px solid #666; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $this->newComments . '</span>';
        }
        $this->delays = $this->delayed_end ? "исполнение" : ($this->delayed_start ? "реакция" : null);

        if (isset($this->requestFields) and !empty($this->requestFields)) {
            foreach ($this->requestFields as $fld) {
                /** @var RequestFields $fld */
                if ($fld->type == 'toggle') {
                    $value = $fld->value == 1 ? 'Да' : 'Нет';
                } else {
                    $value = $fld->value;
                }
                $this->fields[$fld->name] = $value;
                $this->__set('ff_id_' . $fld->fid, $value);
                $this->field_keys[$fld->fid] = 'ff_id_' . $fld->fid;
            }
        }

        return parent::afterFind();
    }

    /**
     * @param $range
     * @return array
     */
    public static function GetExplode($range)
    {
        return explode(' - ', $range);
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
        $criteria1 = new CDbCriteria;
        if (isset($_GET['RequestFullFields']['slabel']) and is_array($_GET['RequestFullFields']['slabel'])) {
            $criteria1->addInCondition('Status', $_GET['RequestFullFields']['slabel'], 'OR');
        } else {
            if ($_GET['RequestFullFields']['slabel'] !== '0' and !isset(Yii::app()->session['customReport']['slabel'])) {
                $endstatus = Status::model()->findAllByAttributes(array('hide' => 1));
                foreach ($endstatus as $stat) {
                    $stt[$stat->name] = $stat->name;
                }
                $criteria1->addNotInCondition('Status', $stt, 'OR');
            } elseif (isset(Yii::app()->session['customReport']['slabel'])) {
                $criteria1->addInCondition('Status', Yii::app()->session['customReport']['slabel'], 'OR');
            }
        }

        $rids = [];
        $rid_key_for_clean_result = false;
        foreach ($this->field_keys as $id => $key) {
            if (isset($_GET['RequestFullFields'][$key]) and !empty($_GET['RequestFullFields'][$key])) {
                $rid = Yii::app()->db->createCommand([
                    'select' => ['rid'],
                    'from' => 'request_fields',
                    'where' => "fid = {$id} AND value LIKE '%{$_GET['RequestFullFields'][$key]}%'",
                ])->queryAll();
                $rids = array_merge($rids, $rid);
                $rid_key_for_clean_result = true;
            }
        }
        $requests_ids = [];
        if (!empty($rids)) {
            foreach ($rids as $rid) {
                $requests_ids[] = $rid['rid'];
            }
        }

        $criteria = new CDbCriteria;

        $user = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
        $criteria->compare('id', $this->id);
        $criteria->compare('pid', 0, false);

        if (Yii::app()->user->checkAccess('viewMyAssignedRequest')) {
            $criteria2 = new CDbCriteria;
            $criteria2->compare('Managers_id', Yii::app()->user->name, true);
            $criteria_grp = new CDbCriteria;
            $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
            //$criteria_grp->compare('users', Yii::app()->user->id, true);
            $grp = Groups::model()->findAll($criteria_grp);
            foreach ($grp as $grpname) {
                $criteria2->addSearchCondition('groups_id', $grpname->id, false, 'OR', 'LIKE');
                $criteria2->addInCondition('mfullname', array(null), 'AND');
                $criteria2->addSearchCondition('Managers_id', Yii::app()->user->name, false, 'OR', 'LIKE');
            }
            $criteria2->addSearchCondition('CUsers_id', Yii::app()->user->name, false, 'OR', 'LIKE');
            $criteria2->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
            $criteria2->compare('pid', 0, false);

            $criteria->mergeWith($criteria2, 'AND');
        }

        if (Yii::app()->user->checkAccess('viewAssignedRequest') and !Yii::app()->user->checkAccess('viewCompanyRequest')) {
            $criteria->compare('Managers_id', Yii::app()->user->name, true);
            $criteria_grp = new CDbCriteria;
            $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
            //$criteria_grp->compare('users', Yii::app()->user->id, true);
            $grp = Groups::model()->findAll($criteria_grp);
            foreach ($grp as $grpname) {
                $criteria->addSearchCondition('gfullname', $grpname->name, true, 'OR', 'LIKE');
                $criteria->addInCondition('mfullname', array(null), 'AND');
                $criteria->addSearchCondition('Managers_id', Yii::app()->user->name, false, 'OR', 'LIKE');
            }
            $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
            $criteria->compare('pid', 0, false);
        } else {
            if (Yii::app()->user->checkAccess('viewCompanyRequest') and !Yii::app()->user->checkAccess('viewAssignedRequest')) {
                $criteria->compare('Managers_id', $this->Managers_id);
                $companies = Companies::model()->findAllByAttributes(array('manager' => Yii::app()->user->name));
                if ($companies) {
                    $criteria->compare('company', '000', true);
                    foreach ($companies as $comps) {
                        $criteria->addSearchCondition('company', $comps->name, false, 'OR', 'LIKE');
                        $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                    }
                    $criteria->compare('pid', 0, false);
                } else {
                    $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                    $criteria->compare('company', '000', true);
                }
            } else {
                if (Yii::app()->user->checkAccess('viewCompanyRequest') and Yii::app()->user->checkAccess('viewAssignedRequest')) {
                    $companies = Companies::model()->findAllByAttributes(array('manager' => Yii::app()->user->name));
                    if ($companies) {
                        $criteria->compare('company', '000', true);
                        foreach ($companies as $comps) {
                            $criteria->addSearchCondition('company', $comps->name, false, 'OR', 'LIKE');
                            $criteria->addSearchCondition('Managers_id', Yii::app()->user->name, false, 'AND', 'LIKE');
                        }
                        $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                        $criteria->addInCondition('Managers_id', array(null), 'OR');
                        $criteria->compare('pid', 0, false);
                    } else {
                        $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                        $criteria->compare('company', '000', true);
                    }
                } else {
                    $criteria->compare('Managers_id', $this->Managers_id);
                }
            }
        }

        //Если текущий пользователь Админ или Пользователь, то выводим все или заявки созданные пользователем
        if (!Yii::app()->user->checkAccess('viewMyselfRequest') and !Yii::app()->user->checkAccess('viewMyCompanyRequest')) {
            $criteria->compare('CUsers_id', $this->CUsers_id);
        } else {
            if (Yii::app()->user->checkAccess('viewMyCompanyRequest')) {
                $company = Companies::model()->findByAttributes(array('name' => $user->company));
                if ($company) {
                    $criteria->addSearchCondition('company', $company->name, false, 'AND', 'LIKE');
                    $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                }
            } else {
                $criteria->compare('CUsers_id', Yii::app()->user->name);
                $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
            }
        }
        if (!Yii::app()->user->checkAccess('systemUser') && Yii::app()->user->checkAccess('viewGroupRequest')) {
            $criteria_grp = new CDbCriteria;
            $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
            $grp = Groups::model()->findAll($criteria_grp);
            $all_user_gr = [];
            foreach ($grp as $grpname) {
                if (!empty($grpname->users)) {
                    $gr = explode(',', $grpname->users);
                    if (!empty($gr) && is_array($gr)) {
                        $all_user_gr = array_merge($all_user_gr, $gr);
                    }
                }
            }
            $all_user_gr = array_filter($all_user_gr);
            if (count($all_user_gr) > 0) {
                $criteria->addInCondition('gr_id', $all_user_gr, 'OR');
            }
        }
        // Если пользователь может видеть завершенные заявки своей группы
        if (!Yii::app()->user->checkAccess('systemUser') && Yii::app()->user->checkAccess('viewGroupRequest')) {
            $criteria_grp = new CDbCriteria;
            $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
            $grp = Groups::model()->findAll($criteria_grp);
            $all_user_gr = [];
            foreach ($grp as $grpname) {
                if (!empty($grpname->users)) {
                    $gr = explode(',', $grpname->users);
                    if (!empty($gr) && is_array($gr)) {
                        $all_user_gr = array_merge($all_user_gr, $gr);
                    }
                }
            }
            $all_user_gr = array_filter($all_user_gr);
            if (count($all_user_gr) > 0) {

                $criteria->addInCondition('gr_id', $all_user_gr, 'OR');
            }
        }
        // Если пользователь может видеть все заявки своей группы
        if (!Yii::app()->user->checkAccess('systemUser') && Yii::app()->user->checkAccess('viewAllGroupRequest')) {
            $criteria_grp = new CDbCriteria;
            $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
            $grp = Groups::model()->findAll($criteria_grp);
            $all_user_gr = [];
            foreach ($grp as $grpname) {
                if (!empty($grpname->users)) {
                    $gr = explode(',', $grpname->users);
                    foreach ($gr as $gritem) {
                        $uname = Cusers::model()->findByPk($gritem);
                        $all_user_grp[] = $uname->Username;
                    }
                    if (!empty($gr) && is_array($gr)) {
                        $all_user_gr = array_merge($all_user_gr, $all_user_grp);
                    }
                }
            }
            $all_user_gr = array_filter($all_user_gr);
            if (count($all_user_gr) > 0) {
                $criteria->addInCondition('Managers_id', $all_user_gr, 'OR');
            }
        }
        //если пользователь может видеть все заявки в подразделениях, где он руководитель
        if (Yii::app()->user->checkAccess('viewMyDepartRequest')) {
            $departs = Depart::model()->findAllByAttributes(['manager_id' => Yii::app()->user->id]);
            foreach ($departs as $depart) {
                //$criteria->addSearchCondition('depart_id', $depart->id, false, 'OR', 'LIKE');
                $deps[] = $depart->id;
            }
            $criteria->addInCondition('depart_id', $deps, 'AND');
        }
        $criteria->addSearchCondition('matching', $user->id, true, 'OR', 'LIKE');
        $criteria->compare('Name', $this->Name, true);
        $criteria->compare('Type', $this->Type, true);
        $criteria->compare('ZayavCategory_id', $this->ZayavCategory_id, true);
        $criteria->compare('KE_type', $this->KE_type, true);
        // Date range
        if (null !== $this->Date && '' !== $this->Date) {
            $mdataD = self::GetExplode($this->Date);
            $criteria->addBetweenCondition('timestamp', date('Y-m-d', strtotime($mdataD[0])) . ' 00:00:00',
                date('Y-m-d', strtotime($mdataD[1])) . ' 23:59:59');
        } else {
            $criteria->compare('Date', $this->Date, true);
        }

        if (null !== $this->StartTime && '' !== $this->StartTime) {
            $mdataST = self::GetExplode($this->StartTime);
            $criteria->addBetweenCondition('timestampStart', date('Y-m-d', strtotime($mdataST[0])) . ' 00:00:00',
                date('Y-m-d', strtotime($mdataST[1])) . ' 23:59:59');
        } else {
            $criteria->compare('StartTime', $this->StartTime, true);
        }

        if (null !== $this->EndTime && '' !== $this->EndTime) {
            $mdataET = self::GetExplode($this->EndTime);
            $criteria->addBetweenCondition('timestampEnd', date('Y-m-d', strtotime($mdataET[0])) . ' 00:00:00',
                date('Y-m-d', strtotime($mdataET[1])) . ' 23:59:59');
        } else {
            $criteria->compare('EndTime', $this->EndTime, true);
        }

        $criteria->compare('depart', $this->depart, true);

        if (null !== $this->fStartTime && '' !== $this->fStartTime) {
            $mdataFST = self::GetExplode($this->fStartTime);
            $criteria->addBetweenCondition('timestampfStart', date('Y-m-d', strtotime($mdataFST[0])) . ' 00:00:00',
                date('Y-m-d', strtotime($mdataFST[1])) . ' 23:59:59');
        } else {
            $criteria->compare('fStartTime', $this->fStartTime, true);
        }

        if (null !== $this->fEndTime && '' !== $this->fEndTime) {
            $mdataFET = self::GetExplode($this->fEndTime);
            $criteria->addBetweenCondition('timestampfEnd', date('Y-m-d', strtotime($mdataFET[0])) . ' 00:00:00',
                date('Y-m-d', strtotime($mdataFET[1])) . ' 23:59:59');
        } else {
            //$criteria->compare('fEndTime', $this->fEndTime, true);
        }

        if ((!isset($_GET['RequestFullFields']['slabel']) or !is_array($_GET['RequestFullFields']['slabel'])) and !isset(Yii::app()->session['customReport']['slabel'])) {
            $criteria->compare('slabel', $this->slabel, true);
        }
        if (isset($_GET['RequestFullFields']['company'])) {
            $criteria->compare('company', $_GET['RequestFullFields']['company'], true);
        } else {
            $criteria->compare('company', $this->company, true);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('Priority', $this->Priority, true);
        $criteria->compare('timestamp', $this->timestamp, true);
        $criteria->compare('timestampStart', $this->timestamp, true);
        $criteria->compare('rating', $this->rating, true);
        $criteria->compare('lead_time', $this->lead_time, true);
        $criteria->compare('leaving', $this->leaving, true);
        $criteria->compare('re_leaving', $this->re_leaving, true);
        $criteria->compare('correct_timestamp', $this->correct_timestamp, true);
        $criteria->compare('contractors_id', $this->contractors_id);
        $criteria->compare('Content', $this->Content, true);
        $criteria->compare('cunits', $this->cunits, true);
        $criteria->compare('service_id', $this->service_id, true);
        $criteria->compare('groups_id', $this->groups_id, false);
        if (isset($_GET['RequestFullFields']['service_name']) and is_array($_GET['RequestFullFields']['service_name'])) {
            $criteria->compare('service_name', $_GET['RequestFullFields']['service_name'], true);
        } else {
            $criteria->compare('service_name', $this->service_name, true);
        }
        $criteria->compare('closed', $this->closed, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('fullname', $this->fullname, true);
        $criteria->compare('mfullname', $this->mfullname, true);
        $criteria->compare('gfullname', $this->gfullname, true);
        $criteria->compare('creator', $this->creator, true);
        $criteria->compare('room', $this->room, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('channel', $this->channel, true);
        if (!empty($requests_ids) && $rid_key_for_clean_result) {
            $criteria->addInCondition('id', $requests_ids);
        } elseif (empty($requests_ids) && $rid_key_for_clean_result) {
            $criteria->addInCondition('id', $requests_ids);
        }
        $criteria->compare('pid', 0, false);

        $criteria->mergeWith($criteria1, 'AND');

        if (isset(Yii::app()->session['sortFilter'])) {
            $_GET['sort'] = Yii::app()->session['sortFilter'];
        }

        $sort = new CSort();
        $sort->modelClass = 'Request';
        $sort->multiSort = true;
        $sort->defaultOrder = (Yii::app()->session['requestlastactivity'] == 1) ? 'lastactivity DESC' : 'id DESC';
        $sort->attributes = [
            'Date' => [
                'asc' => 'timestamp ASC',
                'desc' => 'timestamp DESC'
            ],
            'StartTime' => [
                'asc' => 'timestampStart ASC',
                'desc' => 'timestampStart DESC'
            ],
            'fStartTime' => [
                'asc' => 'timestampfStart ASC',
                'desc' => 'timestampfStart DESC'
            ],
            'EndTime' => [
                'asc' => 'timestampEnd ASC',
                'desc' => 'timestampEnd DESC'
            ],
            'fEndTime' => [
                'asc' => 'timestampfEnd ASC',
                'desc' => 'timestampfEnd DESC'
            ],
            'slabel' => [
                'asc' => 'Status ASC',
                'desc' => 'Status DESC'
            ],
            'groups_id' => [
                'asc' => 'gfullname ASC',
                'desc' => 'gfullname DESC'
            ],
            'channel',
            'service_name',
            'fullname',
            'mfullname',
            'phone',
            'room',
            'company',
            'cunits',
            'depart',
            'creator',
            'Address',
            'ZayavCategory_id',
            'Priority',
            // 'KE_type'

        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => [
                'pageSize' => (int)Yii::app()->session['requestPageCount'] ? Yii::app()->session['requestPageCount'] : 30,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RequestFullFields the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
