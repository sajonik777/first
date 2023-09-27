<?php

/**
 * This is the model class for table "Request".
 *
 * The followings are the available columns in table 'Request':
 * @property integer $id
 * @property integer $pid
 * @property string $Name
 * @property string $Type
 * @property string $ZayavCategory_id
 * @property string $Date
 * @property string $StartTime
 * @property string $EndTime
 * @property string $Status
 * @property string $Priority
 * @property string $Managers_id
 * @property string $CUsers_id
 * @property string $Address
 * @property string $SLA
 * @property string $Content
 * @property string $matching
 * @property string $Comment
 * @property string $lead_time
 * @property integer $leaving
 * @property integer $re_leaving
 * @property integer $contractors_id
 * @property integer $groups_id
 * @property integer $service_id
 * @property string $fields_history
 * @property string $last_comment
 * @property string $pendingTime
 *
 * @property string $watchers
 * @property string $cunits
 * @property string $tcategory
 *
 * @property string $timestamp
 * @property string $timestampStart
 * @property string $timestampfStart
 * @property string $timestampEnd
 * @property string $timestampfEnd
 * @property string $timestampClose
 * @property string $key
 * @property integer $newComments
 * @property integer $gr_id
 * @property string $paused
 * @property integer $previous_paused_status_id
 * @property integer $paused_total_time
 *
 * @property boolean $delayed_start
 * @property boolean $delayed_end
 * @property integer $delayedHours
 *
 * @property string $msbot_id
 * @property string $msbot_params
 *
 * @property bool $saveFilter
 *
 * @property string $delays
 * @property array $files
 * @property Files[] $reqFiles
 * @property RequestFiles[] $requestFiles
 */
class RequestAPI extends CActiveRecord
{
    public $fields = [];
    private $leadTimeEx = null;
    private $_newComments = 0;
    private $_pendingTime = null;

    public $delays;
    public $child;
    public $lastactivity;
    public $next_id;
    public $channel;
    public $channel_icon;
    public $pTime;

    public $reopened;
    public $canceled;
    public $dalayed;
    public $waspaused;
    public $wasautoclosed;
    public $wasescalated;

    public $saveFilter = false;

    /** @var array */
    private $_files = [];

    /**
     * @return array
     */
    public function getFiles()
    {
        if ($this->isNewRecord) {
            return $this->_files;
        } else {
            return $this->getAttachments();
        }
    }

    /**
     * @param array $value
     */
    public function setFiles(array $value)
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
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    /**
     * @return string
     */
    public function getPendingTime()
    {
        return $this->_pendingTime;
    }

    /**
     * @param $value string
     */
    public function setPendingTime($value)
    {
        $this->_pendingTime = $value;
    }

    /**
     * @return int
     */
    public function getNewComments()
    {
        $is_console = PHP_SAPI == 'cli'; //if is console app return bool
        if (!$is_console) {
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
        }
        return $this->_newComments;
    }

    /**
     * @param $sla
     * @return string
     * @throws Exception
     */
    public function calculateLeadTime($sla)
    {
        $currentTime = null === $this->paused ? date('Y-m-d H:i:s') : $this->paused;

        if (empty($sla)) {
            $sla = Sla::model()->findByPk(Yii::app()->params['zdsla']);
        }

        $workingTime = WorkingTimeComponent::createFromSla($sla);

        $start = new DateTime($this->Date);
        $end = new DateTime($currentTime);

        $leadMinutes = $workingTime->calculatingWorkingTime($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'));

        if (0 !== (int)$this->paused_total_time) {
            $leadMinutes = $leadMinutes - (int)$this->paused_total_time;
        }

        $hours = (int)($leadMinutes / 60);
        $minutes = $leadMinutes % 60;

        $this->leadTimeEx = $this->lead_time = "$hours:$minutes:00";

        return $this->lead_time;
    }

    /**
     * @return null|string
     * @throws Exception
     */
    public function getLeadTimeEx()
    {
        $service = Service::model()->findByPk($this->service_id);
        $sla = Sla::model()->findByAttributes(['name' => $service->sla]);

        return $this->calculateLeadTime($sla);
    }

    /**
     * @return array
     */
    public static function all()
    {
        $inc = Category::model()->findByAttributes(array('incident' => 1));
        $models = self::model()->findAllByAttributes(array('ZayavCategory_id' => $inc->name));
        $array = array();
        foreach ($models as $aid) {
            $array[$aid->id] = $aid->Name;
        }

        return $array;
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
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [
                'Type, Date, StartTime, EndTime, depart, fStartTime, fEndTime, Priority, Managers_id, CUsers_id, service_name, closed, update_by, room, phone, matching, paused',
                'length',
                'max' => 50
            ],
            ['company', 'length', 'max' => 100],
            ['ZayavCategory_id', 'length', 'max' => 100],
            ['Name, tchat_id', 'length', 'max' => 100],
            ['Address', 'length', 'max' => 250],
            ['key', 'length', 'max' => 32],
            ['fullname, creator, channel, channel_icon', 'length', 'max' => 100],
            ['slabel, Status, child', 'length', 'max' => 400],
            ['cunits', 'length', 'max' => 500, 'on' => 'update'],
            ['tcategory', 'length', 'max' => 500, 'on' => 'update'],
            ['watchers', 'length', 'max' => 500, 'on' => 'update'],
            ['image', 'length', 'max' => 250],
            ['leaving, re_leaving', 'length', 'max' => 1],
            (Yii::app()->user->checkAccess('canEditContent') or Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) ? [
                'Content',
                'required',
                'on' => 'update'
            ] : ['Name', 'required', 'on' => 'update'],
            //array('lead_time', 'length', 'max'=>10),
            // name, email, subject and body are required
            (PHP_SAPI !== 'cli' and Yii::app()->user->checkAccess('liteformRequest') and Yii::app()->user->checkAccess('systemUser')) ? [
                'Name, Content, CUsers_id',
                'required',
                'on' => 'insert'
            ] : ['Name, Content, CUsers_id, service_id', 'required', 'on' => 'insert'],
            //array('service_id', 'required', 'on' => 'update'),
            // array('timestamp', 'date', 'format'=>'dd.MM.yyyy'),
            [
                'rating, contractors_id, delayedHours, previous_paused_status_id, paused_total_time',
                'numerical',
                'integerOnly' => true
            ],
            [
                'Name, Content, slabel, Comment, StartTime, fStartTime, EndTime, fEndTime, service_id, cunits, tcategory, update_by, gfullname, mfullname, timestamp, id, pid, image, rating, groups_id, correct_timestamp, lead_time, leadTimeEx, watchers, delays, delayedHours, last_comment, pendingTime, files, company, saveFilter, tchat_id, timestampClose, paused, previous_paused_status_id, paused_total_time',
                'safe'
            ],
            ['Name, Content, Comment', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [
                'id, Name, mfullname, gfullname, Type, ZayavCategory_id, Date, lastactivity, timestamp, timestampStart, timestampfStart, timestampEnd, timestampfEnd,  StartTime, EndTime, fStartTime, fEndTime, Status, slabel, creator, Priority, Managers_id, CUsers_id, service_name, service_id, Address, company, Content, Comment, closed, watchers, matching, cunits, tcategory, rating, lead_time, leadTimeEx, leaving, contractors_id, re_leaving, groups_id, correct_timestamp, delayed_start, delayed_end, delays, delayedHours, child, last_comment, pendingTime, tchat_id, channel, channel_icon, pTime, paused, previous_paused_status_id, paused_total_time',
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
        return [
            'history' => [self::HAS_MANY, 'History', 'zid'],
            'comms' => [self::HAS_MANY, 'Comments', 'rid'],
            'flds' => [self::HAS_MANY, 'RequestFields', 'rid'],
            'groups_rl' => [self::BELONGS_TO, 'Groups', 'groups_id'],
            'service_rl' => [self::BELONGS_TO, 'Service', 'service_id'],
            'requestFiles' => [self::HAS_MANY, 'RequestFiles', 'request_id'],
            'reqFiles' => [self::MANY_MANY, 'Files', 'request_files(request_id, file_id)'],
            'tw_session_rl' => [self::HAS_ONE, 'TeamviewerSessions', 'request_id'],
        ];
    }

    /**
     * @return CActiveRecord[]
     */
    public function getChildRequests()
    {
        return $this->findAllByAttributes(['pid' => $this->id]);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main-ui', '#'),
            'channel' => Yii::t('main-ui', 'Channel'),
            'Name' => Yii::t('main-ui', 'Name'),
            'Type' => Yii::t('main-ui', 'Type'),
            'ZayavCategory_id' => Yii::t('main-ui', 'Category'),
            'Date' => Yii::t('main-ui', 'Created'),
            'StartTime' => Yii::t('main-ui', 'Start Time'),
            'EndTime' => Yii::t('main-ui', 'End Time'),
            'fStartTime' => Yii::t('main-ui', 'Fact Start time'),
            'fEndTime' => Yii::t('main-ui', 'Fact End Time'),
            'Status' => Yii::t('main-ui', 'Status'),
            'slabel' => Yii::t('main-ui', 'Status'),
            'Priority' => Yii::t('main-ui', 'Priority'),
            'Managers_id' => Yii::t('main-ui', 'Manager'),
            'CUsers_id' => Yii::t('main-ui', 'User'),
            'depart' => Yii::t('main-ui', 'Department'),
            'fullname' => Yii::t('main-ui', 'Customer'),
            'mfullname' => Yii::t('main-ui', 'Manager'),
            'gfullname' => Yii::t('main-ui', 'Group'),
            'Address' => Yii::t('main-ui', 'Address'),
            'company' => Yii::t('main-ui', 'Company'),
            'Content' => Yii::t('main-ui', 'Content'),
            'Comment' => Yii::t('main-ui', 'Comment'),
            'cunits' => Yii::t('main-ui', 'Configuration units'),
            'tcategory' => Yii::t('main-ui', 'Category KE'),
            'service_id' => Yii::t('main-ui', 'Service name'),
            'service_name' => Yii::t('main-ui', 'Service name'),
            'closed' => Yii::t('main-ui', 'Closed'),
            'image' => '',
            'creator' => Yii::t('main-ui', 'Creator'),
            'watchers' => Yii::t('main-ui', 'Observers'),
            'matching' => Yii::t('main-ui', 'Matching'),
            'room' => Yii::t('main-ui', 'Room'),
            'phone' => Yii::t('main-ui', 'Phone'),
            'rating' => Yii::t('main-ui', 'Rating'),
            'lead_time' => Yii::t('main-ui', 'Time worked'),
            'leaving' => 'Требуется выезд',
            're_leaving' => 'Повторный выезд',
            'contractors_id' => Yii::t('main-ui', 'Contractor'),
            'groups_id' => Yii::t('main-ui', 'Group'),
            'correct_timestamp' => 'correct_timestamp',
            'delayed_start' => Yii::t('main-ui', 'Delayed reaction'),
            'delayed_end' => Yii::t('main-ui', 'Delayed execution'),
            'delayedHours' => Yii::t('main-ui', 'Expired hours'),
            'paused' => Yii::t('main-ui', 'Suspend'),

            'delays' => Yii::t('main-ui', 'Delays'),
            'leadTimeEx' => Yii::t('main-ui', 'Lead time'),
            'last_comment' => Yii::t('main-ui', 'Last Comment'),
            'pendingTime' => Yii::t('main-ui', 'Pending Time'),
            'saveFilter' => Yii::t('main-ui', 'Save filter')
        ];
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     * @throws CException
     */
    public function search()
    {
        $criteria1 = new CDbCriteria;
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/requests') {
            if (isset($_GET['Request']['slabel']) and is_array($_GET['Request']['slabel'])) {
                $criteria1->addInCondition('Status', $_GET['Request']['slabel'], 'OR');
            } else {
                if ($_GET['Request']['slabel'] !== '0' and !isset(Yii::app()->session['customReport']['slabel'])) {
                    $endstatus = Status::model()->findAllByAttributes(array('hide' => 1));
                    foreach ($endstatus as $stat) {
                        $stt[$stat->name] = $stat->name;
                    }
                    $criteria1->addNotInCondition('Status', $stt, 'OR');
                } elseif (isset(Yii::app()->session['customReport']['slabel'])) {
                    $criteria1->addInCondition('Status', Yii::app()->session['customReport']['slabel'], 'OR');
                }
            }
        }

        $criteria = new CDbCriteria;

        $user = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
        $criteria->compare('id', $this->id);
        $criteria->compare('pid', 0, false);

        if (Yii::app()->user->checkAccess('viewMyAssignedRequest')) { //если в роли выбрано только Менеджер может видеть как назначенные ему, так и свои
            // $criteria->compare('Managers_id', Yii::app()->user->name, false); // сравниваем текущего пользователя с исполнителем
            // $criteria->addSearchCondition('CUsers_id', Yii::app()->user->name, false, 'OR', 'LIKE'); // сравниваем текущего пользователя с заказчиком
            // $criteria_grp = new CDbCriteria;
            // $criteria_grp->compare('users', Yii::app()->user->id, true);
            // $grp = Groups::model()->findAll($criteria_grp);
            // $groups = array();
            // if (isset($grp) AND !empty($grp)) {
            //     foreach ($grp as $grpname) {
            //         $groups[] = $grpname->id;
            //     }
            //     var_dump($groups);
            //     $criteria->addInCondition('groups_id', array($groups), 'OR');
            //     $criteria->addInCondition('mfullname', array(null), 'OR');
            // }
            // $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
            // $criteria->compare('pid', 0, false);
            $criteria->compare('Managers_id', Yii::app()->user->name, true);
            $criteria_grp = new CDbCriteria;
            $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
            //$criteria_grp->compare('users', Yii::app()->user->id, true);
            $grp = Groups::model()->findAll($criteria_grp);
            foreach ($grp as $grpname) {
                $criteria->addSearchCondition('groups_id', $grpname->id, true, 'OR', 'LIKE');
                $criteria->addInCondition('mfullname', array(null), 'AND');
                $criteria->addSearchCondition('Managers_id', Yii::app()->user->name, false, 'OR', 'LIKE');
            }
            $criteria->addSearchCondition('CUsers_id', Yii::app()->user->name, false, 'OR', 'LIKE');
            $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
            $criteria->compare('pid', 0, false);
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
        $criteria->addSearchCondition('matching', $user->fullname, true, 'OR', 'LIKE');
        $criteria->compare('Name', $this->Name, true);
        $criteria->compare('Type', $this->Type, true);
        $criteria->compare('ZayavCategory_id', $this->ZayavCategory_id, true);
        // Date range
        if (!empty($this->Date)) {
            $mdata = self::GetExplode($this->Date);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('timestamp', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('Date', $this->Date, true);
        }

        //$criteria->compare('StartTime', $this->StartTime, true);
        if (!empty($this->StartTime)) {
            $mdata = self::GetExplode($this->StartTime);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('timestampStart', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('StartTime', $this->StartTime, true);
        }

        //$criteria->compare('EndTime', $this->EndTime, true);
        if (!empty($this->EndTime)) {
            $mdata = self::GetExplode($this->EndTime);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('timestampEnd', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('EndTime', $this->EndTime, true);
        }

        $criteria->compare('depart', $this->depart, true);

        //$criteria->compare('fStartTime', $this->fStartTime, true);
        if (!empty($this->fStartTime)) {
            $mdata = self::GetExplode($this->fStartTime);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('timestampfStart', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            $criteria->compare('fStartTime', $this->fStartTime, true);
        }

        if (!empty($this->fEndTime)) {
            $mdata = self::GetExplode($this->fEndTime);
            $startDate = date('Y-m-d', strtotime($mdata[0]));
            $endDate = date('Y-m-d', strtotime($mdata[1]));
            $criteria->addBetweenCondition('timestampfEnd', $startDate . ' 00:00:00', $endDate . ' 23:59:59');
        } else {
            //$criteria->compare('fEndTime', $this->fEndTime, true);
        }

        if ((!isset($_GET['Request']['slabel']) or !is_array($_GET['Request']['slabel'])) and !isset(Yii::app()->session['customReport']['slabel'])) {
            $criteria->compare('slabel', $this->slabel, true);
        }
        if (isset($_GET['Request']['company'])) {
            $criteria->compare('company', $_GET['Request']['company'], true);
        } else {
            $criteria->compare('company', $this->company, true);
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
        $criteria->compare('id', $this->id);
        $criteria->compare('Priority', $this->Priority, true);
        $criteria->compare('timestamp', $this->timestamp, true);
        $criteria->compare('timestampStart', $this->timestampStart, true);
        $criteria->compare('timestampEnd', $this->timestampEnd, true);
        $criteria->compare('timestampfStart', $this->timestampfStart, true);
        $criteria->compare('timestampfEnd', $this->timestampfEnd, true);
        $criteria->compare('rating', $this->rating, true);
        $criteria->compare('lead_time', $this->lead_time, true);
        $criteria->compare('leaving', $this->leaving, true);
        $criteria->compare('re_leaving', $this->re_leaving, true);
        $criteria->compare('correct_timestamp', $this->correct_timestamp, true);
        $criteria->compare('contractors_id', $this->contractors_id);
        $criteria->compare('Content', $this->Content, true);
        //$criteria->compare('Comment', $this->Comment, true);
        $criteria->compare('cunits', $this->cunits, true);
        $criteria->compare('tcategory', $this->tcategory, true);
        $criteria->compare('service_id', $this->service_id, true);
        $criteria->compare('groups_id', $this->groups_id, false);
        if (isset($_GET['Request']['service_name']) and is_array($_GET['Request']['service_name'])) {
            $criteria->compare('service_name', $_GET['Request']['service_name'], true);
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
        $criteria->compare('lastactivity', $this->lastactivity, true);
        $criteria->mergeWith($criteria1, 'AND');

        $sort = new CSort();
        $sort->modelClass = 'Request';
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
            'tcategory',
            'depart',
            'creator',
            'Address',
            'ZayavCategory_id',
            'Priority'

        ];
        if (Yii::app()->getRequest()->getPathInfo() !== 'api/requests') {
            return new CActiveDataProvider($this, [
                'criteria' => $criteria,
                'sort' => $sort,
                'pagination' => [
                    'pageSize' => (int)Yii::app()->session['requestPageCount'] ? Yii::app()->session['requestPageCount'] : 30,
                ],
            ]);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 10000,
            ),
        ));
    }

    /**
     * @param $range
     * @return array
     */
    public static function GetExplode($range)
    {
        $date_range = explode(' - ', $range);
        return $date_range;
    }

    /**
     * @return bool
     * @throws CHttpException
     * @throws Exception
     */
    public function beforeSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->lastactivity = date("Y-m-d H:i:s");

        if (constant('redaction') == 'DEMO') {
            $lastId = Yii::app()->db->createCommand('SELECT id FROM request ORDER BY id DESC LIMIT 1')->queryScalar();
            if ((int)$lastId > 50) {
                throw new CHttpException(400, 'Ваша лицензия не позволяет создавать больше 50 заявок!');
            }
        }
        $olddata = self::model()->findByPk($this->id);

        if (!empty($this->service_id)) {
            $service = Service::model()->findByPk($this->service_id);
        } else {
            $service = Service::model()->findByPk($olddata->service_id);
        }
        if (!empty($this->Status)) {
            $status = Status::model()->findByAttributes(['name' => $this->Status]);
        }
        if (isset($service)) {
            $this->service_name = $service->name;
            $this->slabel = $status ? $status->label : null;
            if ($service->gtype == 1) { //if service has a one manager
                if ($olddata->service_id !== $this->service_id or ($this->Managers_id == null)) {//if service field has been changed or manager null
                    $mngr = CUsers::model()->findByAttributes(array('Username' => $service->manager));
                    $this->Managers_id = $service->manager;
                    $this->mfullname = $mngr->fullname;
                } elseif ($olddata->Status !== $this->Status and $olddata->service_id == $this->service_id) {
                    $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
                    $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
                    if (($wstatus->name == $_POST['Request']['Status']) or ($estatus->name == $_POST['Request']['Status'])) {
                        if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                            if(isset($this->Managers_id) AND !empty($this->Managers_id)){
                                $manager_n = CUsers::model()->findByAttributes(array('Username' => $this->Managers_id));
                            }else{
                                $manager_n = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
                            }
                            $this->Managers_id = $manager_n->Username;
                            $this->mfullname = $manager_n->fullname;
                        }
                    }
                } elseif ($olddata->Status !== $this->Status and $olddata->service_id !== $this->service_id) {
                    $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
                    $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
                    if (($wstatus->name == $this->Status) or ($estatus->name == $this->Status)) {
                        if(isset($this->Managers_id) AND !empty($this->Managers_id)){
                            $manager_n = CUsers::model()->findByAttributes(array('Username' => $this->Managers_id));
                        }else{
                            $manager_n = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
                        }
                        $this->Managers_id = $manager_n->Username;
                        $this->mfullname = $manager_n->fullname;
                    }
                }
            } elseif ($service->gtype == 2) { //if service has a group of managers
                if ($olddata->service_id !== $this->service_id and $olddata->Status == $this->Status) { //if service field has been changed only
                    $this->Managers_id = null;
                    $this->mfullname = null;
                    $this->gfullname = $service->group;
                    $group = Groups::model()->findByAttributes(array('name' => $service->group));
                    $this->groups_id = $group->id;
                } elseif ($olddata->Status !== $this->Status and $olddata->service_id == $this->service_id) { //if status field has been changed only
                    $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
                    $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
                    if (($wstatus->name == $this->Status) or ($estatus->name == $this->Status)) {
                        if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                            if(isset($this->Managers_id) AND !empty($this->Managers_id)){
                                $manager_n = CUsers::model()->findByAttributes(array('Username' => $this->Managers_id));
                            }else{
                                $manager_n = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
                            }
                            $this->Managers_id = $manager_n->Username;
                            $this->mfullname = $manager_n->fullname;
                        }
                    }
                } elseif ($olddata->Status !== $this->Status and $olddata->service_id !== $this->service_id) {
                    $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
                    $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
                    if (($wstatus->name == $this->Status) or ($estatus->name == $this->Status)) {
                        if(isset($this->Managers_id) AND !empty($this->Managers_id)){
                            $manager_n = CUsers::model()->findByAttributes(array('Username' => $this->Managers_id));
                        }else{
                            $manager_n = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
                        }
                        $this->Managers_id = $manager_n->Username;
                        $this->mfullname = $manager_n->fullname;
                    }
                }
            }
        } else {
            if ($olddata->Status !== $this->Status and PHP_SAPI !== 'cli') {
                $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
                $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
                if (($wstatus->name == $this->Status) or ($estatus->name == $this->Status)) {
                    if(isset($this->Managers_id) AND !empty($this->Managers_id)){
                        $manager_n = CUsers::model()->findByAttributes(array('Username' => $this->Managers_id));
                    }else{
                        $manager_n = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
                    }
                    $this->Managers_id = $manager_n->Username;
                    $this->mfullname = $manager_n->fullname;
                }
            }
        }

        //Если в поле Приоритет пусто, приоритет берется из SLA, закрепленного за сервисом, если нет, то берется значение из поля заявки.
        if ($olddata->service_id !== $this->service_id) {
            $this->Priority = $service->priority;
            $prior = Zpriority::model()->findByAttributes(array('name' => $service->priority));
        } elseif ($olddata->Priority !== $this->Priority) {
            $prior = Zpriority::model()->findByAttributes(array('name' => $this->Priority));
        } elseif ($olddata->Priority !== $this->Priority AND $olddata->service_id !== $this->service_id) {
            $prior = Zpriority::model()->findByAttributes(array('name' => $this->Priority));
        }

        if (!isset($service->sla) or empty($service->sla)) {
            $file5 = dirname(__FILE__) . '/../config/request.inc';
            $content5 = file_get_contents($file5);
            $arr5 = unserialize(base64_decode($content5));
            $model5 = new RequestForm();
            $model5->setAttributes($arr5);
            $sla = Sla::model()->findByAttributes(array('id' => $model5->zdsla));
        } else {
            $sla = Sla::model()->findByAttributes(array('name' => $service->sla));
        }

        $workingTime = new WorkingTimeComponent($sla, $prior);
        $currentDateTime = date('Y-m-d H:i');
        $rtimea = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
            $workingTime->getReaction($currentDateTime));
        $sltimea = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
            $workingTime->getSolution($currentDateTime));

        if ($olddata->Priority == $this->Priority && $olddata->service_id == $this->service_id) {
            if ($this->StartTime == $olddata->StartTime) {
                $this->StartTime = $olddata->StartTime;
                $this->timestampStart = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $olddata->StartTime);
            }
            if ($this->EndTime == $olddata->EndTime) {
                $this->EndTime = $olddata->EndTime;
                $this->timestampEnd = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $olddata->EndTime);
            }
        } else {
            $this->StartTime = $rtimea;
            $this->EndTime = $sltimea;
            $this->timestampEnd = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $sltimea);
        }

        $zst = $status;//Получаем статус заявки из модели Status

        //Если статус заявки имееет аттрибут close = 6, то заявка отменена и все метрики принимают значение текущей даты.
        if ($zst->close == 6) {
            $this->closed = 3; //этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
            $this->canceled = 1;
            $this->fEndTime = date("d.m.Y H:i");
            $this->timestampfEnd = date("Y-m-d H:i:s");
            $this->fStartTime = date("d.m.Y H:i");
            $this->timestampfStart = date("Y-m-d H:i:s");
            $this->EndTime = date("d.m.Y H:i");
            $this->timestampEnd = date("Y-m-d H:i:s");
            $this->StartTime = date("d.m.Y H:i");

            $this->lead_time = $this->calculateLeadTime($sla);
        }

        //Если статус заявки имеет аттрибут close = 3, то заявка Завершена. Добавляется история изменения метрик.
        if ($zst->close == 3) {
            //if ($olddata->closed !== '9') {
            if (true) {
                $this->fEndTime = date("d.m.Y H:i");
                $this->timestampfEnd = date("Y-m-d H:i:s");
                $this->timestampClose = null;

                $this->lead_time = $this->calculateLeadTime($sla);

                $this->timestampfStart = date('Y-m-d H:i:s');
                $this->fEndTime = date('d.m.Y H:i');

                // Время просрочки
                if ($this->delayed_end == 1) {
                    $t = new Timing();
                    $hours = $t->getExpiredHours($this->timestampEnd, $this->timestampfEnd, $sla->wstime,
                        $sla->wetime, $sla->round_days, $sla->taxes);
                    $this->delayedHours = $hours;
                }

                $this->closed = 3;//этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
                if ($this->fStartTime == null) {
                    $this->fStartTime = date("d.m.Y H:i");
                    $this->timestampfStart = date("Y-m-d H:i:s");
                    $this->AddHistory(Yii::t('main-ui',
                            'Fact start time is set to: ') . '<b>' . $this->fStartTime . '</b>');
                }
                $this->AddHistory(Yii::t('main-ui',
                        'Fact end time is set to: ') . '<b>' . $this->fEndTime . '</b>');
            }

            if (isset($this->pendingTime) and !empty($this->pendingTime)) {

                //$this->fStartTime = null;
                if (isset($this->service_id) and !empty($this->service_id)) {
                    $service = Service::model()->findByPk($this->service_id);
                    $sla = Sla::model()->findByAttributes(array('name' => $service->sla));
                    $prior = Zpriority::model()->findByAttributes(array('name' => $this->Priority));
                } else {
                    $prior = Zpriority::model()->findByAttributes(array('name' => Yii::app()->params['zdpriority']));
                    $sla = Sla::model()->findByPk(Yii::app()->params['zdsla']);
                }

                /******/
                $workingTime = new WorkingTimeComponent($sla, $prior);
                $currentDateTime = date('Y-m-d H:i');
                $rtime = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
                    $workingTime->getReaction($currentDateTime));
                $sltime = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
                    $workingTime->getSolution($currentDateTime));
                $auto_close = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
                    $workingTime->getAutoClose($currentDateTime));

                //Присваиваем значения модели
                $this->StartTime = $rtime;
                $this->EndTime = $sltime;

                $this->timestampStart = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $rtime);

                $this->timestampEnd = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $sltime);
                $this->timestampClose = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $auto_close);
                /*****/
            }

            // При закрытии сохраним id манагера для поиска заявок по видимости внутри группы
            $mng = CUsers::model()->findByAttributes(['Username' => $this->Managers_id]);
            $this->gr_id = $mng->id;

        } else { //Если статус заявки имееет аттрибут close = 2, то заявка В работе. Добавляется история изменения метрик
            if ($zst->close == 2) {
                if ($olddata->closed !== '9') {
                    $this->fEndTime = null;
                    $this->closed = 2; //этот параметр означает, что его должен обрабатывать CRON и сверять дедлайны
                    if ($this->fStartTime == null) {
                        $this->fStartTime = date("d.m.Y H:i");
                        $this->timestampfStart = date("Y-m-d H:i:s");
                        $this->AddHistory(Yii::t('main-ui',
                                'Fact start time is set to: ') . '<b>' . $this->fStartTime . '</b>');
                    }
                }
            } else //Если статус заявки имееет аттрибут close = 1, то заявка Новая.
            {
                if ($zst->close == 1) {
                    $this->closed = null; //этот параметр означает, что заявка Открыта и ее должен обрабатывать CRON и сверять дедлайны
                }
                if ($zst->close == 9) {
                    $this->fEndTime = null;
                    $this->timestampfEnd = null;
                    $this->reopened = 1;
                    if (isset($this->fStartTime)) {
                        $this->closed = 2;
                    } else {
                        $this->closed = 1;
                    }
                }
                if ($zst->close == 8) {
                    $this->delayed = 1;
                }
            }
        }

        $this->Comment = null;

        //Если заявка не новая сверяем значения данных из базы с данными из POST и если они различаются, то записываем в историю изменения
        if (!$this->isNewRecord) {
            $olddata = Request::model()->findByPk($this->id);

            if (isset($this->Status) && $olddata->Status !== $this->Status and PHP_SAPI !== 'cli') {
                $this->AddHistory(Yii::t('main-ui', 'Ticket status is set to: ') . $this->slabel);
                if (Yii::app()->user->checkaccess('systemManager') or Yii::app()->user->checkaccess('systemAdmin')) {
                    if(isset($this->Managers_id) AND !empty($this->Managers_id)){
                        $mngr = CUsers::model()->findByPk(array('Username' => $this->Managers_id));
                    }else{
                        $mngr = CUsers::model()->findByPk(array('id' => Yii::app()->user->id));
                    }
                    if (($this->Managers_id !== $olddata->Managers_id or $olddata->Managers_id == null)) {
                        $this->AddHistory(Yii::t('main-ui', 'Manager is set to: ') . '<b>' . $mngr->fullname . '</b>');
                    }
                }
            }

            if (isset($this->Priority) && $olddata->Priority !== $this->Priority) {
                $this->AddHistory(Yii::t('main-ui', 'Ticket priority is set to: ') . '<b>' . $this->Priority . '</b>');
                $this->AddHistory(Yii::t('main-ui', 'Start time is set to: ') . '<b>' . $this->StartTime . '</b>');
                $this->AddHistory(Yii::t('main-ui', 'End time is set to: ') . '<b>' . $this->EndTime . '</b>');
            }

            if ($olddata->service_id !== $this->service_id and !empty($this->service_id)) {
                $mngr = CUsers::model()->findByAttributes(array('Username' => $service->manager));
                $this->AddHistory(Yii::t('main-ui', 'Service is set to: ') . '<b>' . $service->name . '</b>');
                if ($service->gtype == 1) {
                    $this->AddHistory(Yii::t('main-ui', 'Manager is set to: ') . '<b>' . $mngr->fullname . '</b>');
                } elseif ($service->gtype == 2) {
                    $group = Groups::model()->findByAttributes(array('name' => $service->group));
                    $this->AddHistory(Yii::t('main-ui', 'Group is set to: ') . '<b>' . $group->name . '</b>');
                }
                $this->AddHistory(Yii::t('main-ui', 'Start time is set to: ') . '<b>' . $this->StartTime . '</b>');
                $this->AddHistory(Yii::t('main-ui', 'End time is set to: ') . '<b>' . $this->EndTime . '</b>');
            }
            if (trim(strip_tags($olddata->Content)) !== trim(strip_tags($this->Content))) {
                $this->AddHistory(Yii::t('main-ui',
                        'Content is set to: ') . '<b>' . trim(strip_tags($this->Content)) . '</b>');
            }
        }

        // Согласование
        $mstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 7));
        if ((isset($service) and !empty($service->matching)) and (isset($this->Status) and ($this->Status == $mstatus->name))) {
            $this->matching = $service->matching;
        } else {
            $this->matching = null;
        }

        if (isset($this->Status) && !empty($this->Status)) {
            $st = Status::model()->findByAttributes(['name' => $this->Status]);
            $autoCloseTime = $this->getAutoCloseTime($sla, $prior, $st);
            if (null != $autoCloseTime) {
                $this->timestampClose = date('Y-m-d H:i:s', strtotime($autoCloseTime));
            }
        }

        return parent::beforeSave();
    }

    /**
     * @param $sla Sla
     * @param $priority Zpriority
     * @param $status Status
     * @return null|string
     */
    protected function getAutoCloseTime($sla, $priority, $status)
    {
        $timing = new Timing();
        $timing->set_format('d.m.Y H:i');
        if ($sla->autoClose && $sla->autoCloseStatus == $status->id) {
            $mod = $timing->get_lead_time(date('Y-m-d H:i'), $sla->rhours, $sla->shours, $sla->wstime, $sla->wetime,
                $sla->round_days, $priority->rcost, $priority->scost, $sla->taxes, $sla->autoCloseHours);
            return $mod['auto_close'];
        }

        return null;
    }

    // Добавление записи в историю
    public function AddHistory($action)
    {
        if (PHP_SAPI === 'cli') {
            return;
        }
        $cusers_id = CUsers::model()->findByPk(Yii::app()->user->id);
        $history = new History();
        $history->datetime = date("d.m.Y H:i");
        $history->cusers_id = $cusers_id->fullname;
        $history->zid = $this->id;
        $history->action = $action;
        $history->save(false);
    }

    /**
     *
     * @throws \CDbException
     */
    public function beforeDelete()
    {
        // Удаляем связи с файлами
        foreach ($this->requestFiles as $requestFile) {
            /** @var Files $file */
            $file = $requestFile->file;
            /** @var RequestFiles $file */
            $requestFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }

    public function afterSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        //$os_type = DetectOS::getOS();
        //$files = array();

        if (isset($_FILES['image'])) {
            foreach ($_FILES['image']['name'] as $key => $value) {
                $model_f = new Files;
                $model_f->uploadFile = CUploadedFile::getInstanceByName('image[' . $key . ']');
                $result = $model_f->upload();
                if ($result['error'] !== true) {
                    $this->_files[] = $result['id'];
                }
            }
        }

        $afiles = [];
        // Новый блок загрузки файла в форме создания заявки
        /*********************************************/
        $allFiles = [];
        $result1 = [];
        preg_match_all('#src="/uploads/([^"]+)"#i', $this->Content, $result1);
        $result2 = [];
        preg_match_all('#href="/uploads/([^"]+)"#i', $this->Content, $result2);
        if (!empty($result1[0][0])) {
            $allFiles = array_merge($allFiles, $result1[1]);
        }
        if (!empty($result2[0][0])) {
            $allFiles = array_merge($allFiles, $result2[1]);
        }

        // Удаляем все которые были удалены из редактора
//        $attachments = $this->getAttachments();
//        if (!empty($attachments)) {
//            foreach ($attachments as $id => $attachment) {
//                if (!in_array($attachment, $allFiles, false)) {
//                    /** @var Files $fileObj */
//                    $fileObj = Files::model()->findByPk($id);
//                    $fileObj->requestFile->delete();
//                    $fileObj->delete();
//                }
//            }
//        }

        // Сохраняем вложения
        if (!empty($this->_files)) {
            $attachments = $this->getAttachments();
            foreach ($this->_files as $file) {
                // Если такое вложение существует, пропускаем.
                if (array_key_exists($file, $attachments)) {
                    continue;
                }
                $requestFile = new RequestFiles;
                $requestFile->file_id = (int)$file;
                $requestFile->request_id = $this->id;
                $requestFile->save(false);

                // url для письма
                $fileObj = Files::model()->findByPk($file);
                $afiles[] = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileObj->file_name;
                $this->AddHistory(Yii::t('main-ui', 'Added file: ') . '<b>' . $fileObj->name . '</b>');
            }
        }
        /*********************************************/

        //Добавляем в историю запись о создании заявки
        if ($this->isNewRecord) {
            $this->AddHistory(Yii::t('main-ui', 'Ticket created'));
            $this->AddHistory(Yii::t('main-ui', 'Start time is set to: ') . '<b>' . $this->StartTime . '</b>');
            $this->AddHistory(Yii::t('main-ui', 'End time is set to: ') . '<b>' . $this->EndTime . '</b>');
            $this->AddHistory(Yii::t('main-ui', 'Ticket status is set to: ') . $this->slabel);
            if ($this->fStartTime) {
                $this->AddHistory(Yii::t('main-ui',
                        'Fact start time is set to: ') . '<b>' . $this->fStartTime . '</b>');
            }
            if ($this->fEndTime) {
                $this->AddHistory(Yii::t('main-ui', 'Fact end time is set to: ') . '<b>' . $this->fEndTime . '</b>');
            }
            if ($this->cunits) {
                $this->AddHistory(Yii::t('main-ui', 'Assigned unit: ') . '<b>' . $this->cunits . '</b>');
            }
            if ($this->cunits) {
                $this->AddHistory(Yii::t('main-ui', 'Assigned tcategory: ') . '<b>' . $this->tcategory . '</b>');
            }
        }
        //Блок отправки уведомлений
        $key = $this->isNewRecord ? 1 : 0;
        Email::prepare($this->id, $key, $afiles);

        foreach ($this->getChildRequests() as $child) {
            Email::prepare($child->id, $key, $afiles);
        }

        //Дополнительные поля
        if ($this->isNewRecord or empty($this->flds)) {
            $fid = $_POST['Request']['service_id'];
            $service = Service::model()->findByPk($fid);
            $criteria = new CDbCriteria(array('order' => 'sid ASC'));
            $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset), $criteria);
            foreach ($fields as $field) {
                if (isset($_POST['Request'][$field->id])) {
                    $fieldset = new RequestFields();
                    $fieldset->rid = $this->id;
                    $fieldset->fid = $field->id;
                    $fieldset->name = $field->name;
                    $fieldset->type = $field->type;
                    $fieldset->value = $_POST['Request'][$field->id];
                    $fieldset->save(false);
                }
            }
        } else {
            if (!empty($this->flds)) {
                $fields = RequestFields::model()->findAllByAttributes(array('rid' => $this->id));
                foreach ($fields as $field) {
                    if (isset($_POST['Request'][$field->id])) {
                        RequestFields::model()->updateByPk($field->id, array('value' => $_POST['Request'][$field->id]));
                    }
                }
            }
        }

        return parent::afterSave();
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        $fid = $_POST['Request']['service_id'];
        $service = Service::model()->findByPk($fid);
        $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset));
        $fields_arr = array();
        foreach ($fields as $field) {
            if (isset($_POST['Request'][$field->id])) {
                $fields_arr[$field->id] = $_POST['Request'][$field->id];
                if ($field->req and empty($_POST['Request'][$field->id])) {
                    $this->addError($field->id, 'Поле "' . $field->name . '" не может быть пустым!');
                }
            }
        }
        Yii::app()->session->add('fields', $fields_arr);
        return parent::beforeValidate();
    }

    /**
     *
     */
    public function afterValidate()
    {
        return parent::afterValidate();
    }

    /**
     *
     */
    public function afterFind()
    {
        $is_console = 'cli' === PHP_SAPI; //if is console app return bool
        if (!$is_console) {
            // TODO: Показываем только не прочитанные
            if ($this->newComments != 0) {
                //$this->Comment = '<span class="lb-info">' . $this->newComments . '</span>';
                $this->Comment = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #666; vertical-align: baseline; white-space: nowrap; border: 1px solid #666; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $this->newComments . '</span>';
            }
            $this->delays = $this->delayed_end ? 'исполнение' : ($this->delayed_start ? "реакция" : null);
        }

        if (isset($this->flds) and !empty($this->flds)) {
            foreach ($this->flds as $fld) {
                $this->fields[$fld->name] = $fld->value;
            }
        }

        return parent::afterFind();
    }

    /**
     * Сканирование папки с вложениями
     *
     * @param $dir
     * @param int $sort
     * @return array|bool|false
     */
    public function myscandir($dir, $sort = 0)
    {
        $list = scandir($dir, $sort);

        // если директории не существует
        if (!$list) {
            return false;
        }

        // удаляем . и .. (я думаю редко кто использует)
        if ($sort == 0) {
            unset($list[0], $list[1]);
        } else {
            unset($list[count($list) - 1], $list[count($list) - 1]);
        }
        return $list;
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
     * @param $model
     * @param $fields
     */
    public function replaceByValues($model, $fields)
    {
        $this->updateByPk($this->id, $model->getAttributes($fields));
    }

    /**
     *
     */
    public function saveOldFields()
    {
        $fields = ['Date', 'StartTime', 'EndTime', 'Priority', 'mfullname', 'Status', 'slabel'];
        $attrs = $this->getAttributes($fields);

        $this->updateByPk($this->id, ['fields_history' => json_encode($attrs)]);
    }

    /**
     *
     */
    public function restoreOldFields()
    {
        $attrs = json_decode($this->fields_history, true);
        $this->updateByPk($this->id, $attrs);
    }
}
