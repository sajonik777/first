<?php

/**

 *
 * @property string $service_name
 * @property string $company
 * @property string $slabel
 * @property string $gfullname
 * @property string $mfullname
 * @property string $depart
 * @property integer $depart_id
 * @property string $wbot_id
 */
class PortalRequest extends CActiveRecord
{
    use RequestProcessingRuleTrait;

    public $flds;
    private $_files = [];
    public $verifyCode;
    public $lastactivity;
    public $channel;
    public $channel_icon;
    public $service_required;
    public $getmailconfig;

    /**
     * @param string $className
     * @return CActiveRecord|mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return [
            'processingRuleBehavior' => [
                'class' => RequestProcessingRuleBehavior::class,
            ],
        ];
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

        $this->lead_time = "$hours:$minutes:00";

        return $this->lead_time;
    }

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
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'request';
    }

    /**
     * @return array
     */
    public function relations()
    {
        return [
            'requestFiles' => [self::HAS_MANY, 'RequestFiles', 'request_id'],
            'reqFiles' => [self::MANY_MANY, 'Files', 'request_files(request_id, file_id)'],
            'flds' => [self::HAS_MANY, 'RequestFields', 'rid'],
        ];

    }

    /**
     * @return array
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        if (Yii::app()->params['portalAllowCaptcha'] == 1) {
            $usecaptcha = true;
        }
        if (Yii::app()->params['WidgetService'] == 1 and (Yii::app()->getRequest()->getPathInfo() == 'portal/widget' or Yii::app()->getRequest()->getPathInfo() == 'portal/createwidget')) {
            $service_required = true;
        }
        if (Yii::app()->params['portalAllowService'] == 1 and (Yii::app()->getRequest()->getPathInfo() == 'portal' or Yii::app()->getRequest()->getPathInfo() == 'portal/create')) {
            $service_required = true;
        }

        return [
            [
                'Name, Type, ZayavCategory_id, Date, StartTime, EndTime, depart, fStartTime, fEndTime, Priority, Managers_id, CUsers_id, service_name, Address, company, closed',
                'length',
                'max' => 100
            ],
            ['fullname, creator, tchat_id, channel, channel_icon', 'length', 'max' => 100],
            ['slabel, Status', 'length', 'max' => 400],
            ['image', 'length', 'max' => 250],
            ['key', 'length', 'max' => 32],
            // name, email, subject and body are required
            $service_required ? [
                'Name, Content, CUsers_id, depart, service_id',
                'required'
            ] : ['Name, Content, CUsers_id, depart', 'required'],
            ['depart', 'email', 'message' => 'Неверный адрес электронной почты'],
            ['ZayavCategory_id, service_id', 'required', 'on' => 'update'],
            [
                'image',
                'file',
                'types' => 'doc,docx,xls,xlsx,odt,ods,pdf,jpg, jpeg, png, gif',
                'allowEmpty' => true
            ],
            $usecaptcha ? [
                'verifyCode',
                'CaptchaExtendedValidator',
                'allowEmpty' => !CCaptcha::checkRequirements()
            ] : ['slabel', 'length', 'max' => 400],
            // array('timestamp', 'date', 'format'=>'dd.MM.yyyy'),
            [
                'Name, Content, slabel, Comment, StartTime, fStartTime, EndTime, fEndTime, service_id, flds, files, Cusers_id, verifyCode, tchat_id, getmailconfig',
                'safe'
            ],
            [
                'Name, Content, Comment, CUsers_id',
                'filter',
                'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [
                'id, Name, mfullname, Type, ZayavCategory_id, Date,timestamp, lastactivity, StartTime, EndTime, fStartTime, fEndTime, Status, slabel, creator, Priority, Managers_id, CUsers_id, service_name, service_id, Address, company, Content, Comment, cunits, closed, flds, tchat_id, channel, channel_icon',
                'safe',
                'on' => 'search'
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main-ui', '#'),
            'Name' => Yii::t('main-ui', 'Ticket subject'),
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
            'depart' => Yii::t('main-ui', 'E-mail'),
            'fullname' => Yii::t('main-ui', 'User'),
            'mfullname' => Yii::t('main-ui', 'Manager'),
            'Address' => Yii::t('main-ui', 'Address'),
            'company' => Yii::t('main-ui', 'Company'),
            'Content' => Yii::t('main-ui', 'Content'),
            'Comment' => Yii::t('main-ui', 'Comment'),
            'cunits' => Yii::t('main-ui', 'Configuration units'),
            'service_id' => Yii::t('main-ui', 'Service name'),
            'service_name' => Yii::t('main-ui', 'Service name'),
            'closed' => Yii::t('main-ui', 'Closed'),
            'image' => '',
            'creator' => Yii::t('main-ui', 'Creator'),
            'verifyCode' => Yii::t('main-ui', 'Verify code'),
        ];
    }

    /**
     * @return bool
     * @throws CHttpException
     * @throws Exception
     */
    public function beforeSave()
    {
        $ruleStatus = false;
        $ruleSLabel = false;
        $rulePriority = false;
        $ruleZayavCategory_id = false;
        $ruleService_name = false;
        $ruleService_id = false;
        $ruleCompany = false;
        $ruleDepart = false;
        $ruleDepart_id = false;
        $ruleManagers_id = false;
        $ruleMfullname = false;
        $ruleGfullname = false;
        $ruleGroups_id = false;

        $rules = RequestProcessingRules::model()->findAll();
        if (!empty($rules)) {

            foreach ($rules as $rule) {

                if (empty($rule->conditions) || empty($rule->actions)) {
                    continue;
                }

                if (!$rule->is_apply_to_bots && $this->isBot()) {
                    continue;
                }

                $allCondition = 0;
                foreach ($rule->conditions as $condition) {

                    $success = false;

                    switch ($condition->target) {
                        case RequestProcessingRuleConditions::TARGET_SENDER:
                            if ($this->CUsers_id) {
                                $user = CUsers::model()->findByAttributes(['Username' => $this->CUsers_id]);
                                if ($user) {
                                    $success = $this->checkCondition($user->Email, $condition->val, $condition->condition);
                                }
                            } else {
                                $success = $this->checkCondition($this->fullname, $condition->val, $condition->condition);
                            }
                            break;

                        case RequestProcessingRuleConditions::TARGET_SUBJECT:
                            if (!$this->isBot()) {
                                $success = $this->checkCondition($this->Name, $condition->val, $condition->condition);
                            }
                            break;

                        case RequestProcessingRuleConditions::TARGET_CONTENT:
                            $success = $this->checkCondition(strip_tags($this->Content), $condition->val,
                                $condition->condition);
                            break;
                    }

                    if ($success) {
                        $allCondition++;
                    }

                }

                if (($rule->is_all_match && $allCondition === count($rule->conditions)) || (!$rule->is_all_match && $allCondition !== 0)) {

                    foreach ($rule->actions as $action) {

                        switch ($action->target) {
                            case RequestProcessingRuleActions::TARGET_STATUS:
                                $status = Status::model()->findByAttributes(['name' => $action->val]);
                                $ruleStatus = $action->val;
                                $ruleSLabel = $status->label;
                                break;

                            case RequestProcessingRuleActions::TARGET_PRIORITY:
                                $rulePriority = $action->val;
                                break;

                            case RequestProcessingRuleActions::TARGET_CATEGORY:
                                $ruleZayavCategory_id = $action->val;
                                break;

                            case RequestProcessingRuleActions::TARGET_SERVICE:
                                $service = Service::model()->findByPk($action->val);
                                $ruleService_name = $service->name;
                                $ruleService_id = $service->id;
                                break;

                            case RequestProcessingRuleActions::TARGET_COMPANY:
                                $ruleCompany = $action->val;
                                break;

                            case RequestProcessingRuleActions::TARGET_DEPARTS:
                                $depart = Depart::model()->findByAttributes(['name' => $action->val]);
                                $ruleDepart = $action->val;
                                $ruleDepart_id = $depart->id;
                                break;

                            case RequestProcessingRuleActions::TARGET_MANAGER:
                                $manager = CUsers::model()->findByAttributes(['Username' => $action->val]);
                                $ruleManagers_id = $manager->Username;
                                $ruleMfullname = $manager->fullname;
                                break;

                            case RequestProcessingRuleActions::TARGET_GROUP:
                                $group = Groups::model()->findByAttributes(['name' => $action->val]);
                                $ruleGfullname = $action->val;
                                $ruleGroups_id = $group->id;
                                break;
                        }

                    }

                }
            }

        }

        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->lastactivity = date("Y-m-d H:i:s");

        if (constant('redaction') == 'DEMO') {
            $lastId = Yii::app()->db->createCommand('SELECT id FROM request ORDER BY id DESC LIMIT 1')->queryScalar();
            if ((int)$lastId > 50) {
                throw new CHttpException(400, 'Ваша лицензия не позволяет создавать больше 50 заявок!');
            }
        }

        $depart = Depart::model()->findByPk($this->depart_id);
        $this->depart = $depart ? $depart->name : null;
        if ($this->CUsers_id == $_POST['PortalRequest']['depart']) {
            $this->CUsers_id = null;
        }

        if ($ruleDepart !== false) {
            $this->depart = $ruleDepart;
            $this->depart_id = $ruleDepart_id;
        }

        if ($ruleService_name !== false) {
            $this->service_name = $ruleService_name;
            $this->service_id = $ruleService_id;
            $_POST['PortalRequest']['service_id'] = $ruleService_id;
        }
        if (isset($_POST['PortalRequest']['service_id'])) {
            $service = Service::model()->findByPk(array('id' => $_POST['PortalRequest']['service_id']));
            $this->service_id = $service->id;
            $this->service_name = $service->name;
            if (isset($service->watcher)) {
                $this->watchers = $service->watcher;
            }
            if ($service->gtype == 1) {
                $this->Managers_id = $service->manager;
                $manager = CUsers::model()->findByAttributes(array('Username' => $service->manager));//Here we find manager of service
                $this->mfullname = $manager->fullname;
            } else {
                $this->gfullname = $service->group;
                $group = Groups::model()->findByAttributes(array('name' => $service->group));
                $this->groups_id = $group->id;
            }
        }

        if ($ruleManagers_id !== false) {
            $this->mfullname = $ruleMfullname;
            $this->Managers_id = $ruleManagers_id;
        }

        if ($ruleGfullname !== false) {
            $this->gfullname = $ruleGfullname;
            $this->groups_id = $ruleGroups_id;
        }

        if (isset($service)) {
            if ($rulePriority !== false) {
                $service->priority = $rulePriority;
            }
            $prior = Zpriority::model()->findByAttributes(array('name' => $service->priority));
            $sla = Sla::model()->findByAttributes(array('name' => $service->sla));
            $this->Priority = $prior->name;
        } else {
            $prior = Zpriority::model()->findByAttributes(array('name' => Yii::app()->params['zdpriority']));
            $sla = Sla::model()->findByPk(Yii::app()->params['zdsla']);
        }

        if ($rulePriority !== false) {
            $this->Priority = $rulePriority;
        }

        if (!isset($this->Status)) {
            //Ищем в модели Status запись с аттрибутом close = 1 этот статус новой заявки.
            $nstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 1));
            if ($nstatus) {
                $this->Status = $nstatus->name;
                $this->slabel = $nstatus->label;
                $this->closed = 1;
            } else {
                $this->Status = 'Открыта';
                $this->slabel = '<span class="label label-success">Открыта</span>';
                $this->closed = 1;
            }
        } else {
            $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
            $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
            $rstatus = Status::model()->findByAttributes(['enabled' => 1, 'close' => 4]);
            $sstatus = Status::model()->findByAttributes(['enabled' => 1, 'close' => 5]);
            $cstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 6));

            if ($this->Status == $wstatus->name) {
                $this->Status = $wstatus->name;
                $this->slabel = $wstatus->label;
                $this->closed = 1;
                $this->fStartTime = date("d.m.Y H:i");
                $this->timestampfStart = date("Y-m-d H:i:s");
            }

            if ($this->Status == $estatus->name) {
                $this->Status = $estatus->name;
                $this->slabel = $estatus->label;
                $this->fEndTime = date('d.m.Y H:i');
                $this->timestampfEnd = date('Y-m-d H:i:s');

                $this->lead_time = $this->calculateLeadTime($sla);

                $this->closed = 3;//этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
                if ($this->fStartTime == null) {
                    $this->fStartTime = date('d.m.Y H:i');
                    $this->timestampfStart = date('Y-m-d H:i:s');
                }
            }
            if ($this->Status == $rstatus->name) {
                $this->Status = $rstatus->name;
                $this->slabel = $rstatus->label;
                $this->closed = 1;
            }

            if ($this->Status == $sstatus->name) {
                $this->Status = $sstatus->name;
                $this->slabel = $sstatus->label;
                $this->closed = 9;
                $this->lead_time = $this->calculateLeadTime($sla);
            }

            if ($this->Status == $cstatus->name) {
                $this->Status = $cstatus->name;
                $this->slabel = $cstatus->label;
                $this->closed = 3; //этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
                $this->canceled = 1;
                $this->fEndTime = date('d.m.Y H:i');
                $this->timestampfEnd = date('Y-m-d H:i:s');
                $this->fStartTime = date('d.m.Y H:i');
                $this->timestampfStart = date('Y-m-d H:i:s');
                $this->EndTime = date('d.m.Y H:i');
                $this->timestampEnd = date('Y-m-d H:i:s');
                $this->StartTime = date('d.m.Y H:i');

                $this->lead_time = $this->calculateLeadTime($sla);
            }
        }

        $workingTime = new WorkingTimeComponent($sla, $prior);
        $currentDateTime = date('Y-m-d H:i');
        $rtime = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm', $workingTime->getReaction($currentDateTime));
        $sltime = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
            $workingTime->getSolution($currentDateTime));
        $auto_close = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
            $workingTime->getAutoClose($currentDateTime));

        //Присваиваем значения модели
        $this->Date = date("d.m.Y H:i");
        $this->StartTime = $rtime;
        $this->EndTime = $sltime;
        $this->timestamp = date('Y-m-d H:i:s');

        $this->timestampStart = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $rtime);
        $this->timestampEnd = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $sltime);

        //проверяем что автозакрытие включено и указан статус Открыта
        if ($sla->autoClose) {
            $acstatus = Status::model()->findByPk($sla->autoCloseStatus);
            if ($acstatus->close == 1) {
                $this->timestampClose = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $auto_close);
            }

        }

        $ts = time();
        $rnd = rand();
        $this->key = md5($ts + $rnd);

        if ($ruleStatus !== false) {
            $this->Status = $ruleStatus;
            $this->slabel = $ruleSLabel;
        }

        if (!isset($this->Status)) {
            //Ищем в модели Status запись с аттрибутом close = 1 этот статус новой заявки.
            $nstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 1));
            if ($nstatus) {
                $this->Status = $nstatus->name;
                $this->slabel = $nstatus->label;
            } else {
                $this->Status = 'Открыта';
                $this->slabel = '<span class="label label-success">Открыта</span>';
            }
        } else {
            $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
            $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
            $rstatus = Status::model()->findByAttributes(['enabled' => 1, 'close' => 4]);
            $sstatus = Status::model()->findByAttributes(['enabled' => 1, 'close' => 5]);
            $cstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 6));

            if ($this->Status == $wstatus->name) {
                $this->Status = $wstatus->name;
                $this->slabel = $wstatus->label;
                $this->closed = 1;
                $this->fStartTime = date("d.m.Y H:i");
                $this->timestampfStart = date("Y-m-d H:i:s");
            }

            if ($this->Status == $estatus->name) {
                $this->Status = $estatus->name;
                $this->slabel = $estatus->label;
                $this->fEndTime = date('d.m.Y H:i');
                $this->timestampfEnd = date('Y-m-d H:i:s');

                $this->lead_time = $this->calculateLeadTime($sla);

                $this->closed = 3;//этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
                if ($this->fStartTime == null) {
                    $this->fStartTime = date('d.m.Y H:i');
                    $this->timestampfStart = date('Y-m-d H:i:s');
                }
            }
            if ($this->Status == $rstatus->name) {
                $this->Status = $rstatus->name;
                $this->slabel = $rstatus->label;
                $this->closed = 1;
            }

            if ($this->Status == $sstatus->name) {
                $this->Status = $sstatus->name;
                $this->slabel = $sstatus->label;
                $this->closed = 9;
                $this->lead_time = $this->calculateLeadTime($sla);
            }

            if ($this->Status == $cstatus->name) {
                $this->Status = $cstatus->name;
                $this->slabel = $cstatus->label;
                $this->closed = 3; //этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
                $this->canceled = 1;
                $this->fEndTime = date('d.m.Y H:i');
                $this->timestampfEnd = date('Y-m-d H:i:s');
                $this->fStartTime = date('d.m.Y H:i');
                $this->timestampfStart = date('Y-m-d H:i:s');
                $this->EndTime = date('d.m.Y H:i');
                $this->timestampEnd = date('Y-m-d H:i:s');
                $this->StartTime = date('d.m.Y H:i');

                $this->lead_time = $this->calculateLeadTime($sla);
            }
        }

        if ($ruleZayavCategory_id !== false) {
            $this->ZayavCategory_id = $ruleZayavCategory_id;
        }

        if ($ruleCompany !== false) {
            $this->company = $ruleCompany;
        }

        return parent::beforeSave();
    }

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function afterSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->_files = $_POST['PortalRequest']['files'];

        if ($this->isNewRecord) {
            $fid = $_POST['PortalRequest']['service_id'];
            $service = Service::model()->findByPk($fid);
            $criteria = new CDbCriteria(array('order' => 'sid ASC'));
            $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset), $criteria);
            foreach ($fields as $field) {
                if (isset($_POST['PortalRequest'][$field->id])) {
                    $fieldset = new RequestFields();
                    $fieldset->rid = $this->id;
                    $fieldset->fid = $field->id;
                    $fieldset->name = $field->name;
                    $fieldset->type = $field->type;
                    $fieldset->value = $_POST['PortalRequest'][$field->id];
                    $fieldset->save(false);
                }
            }
        }

        //чек-лист
        if (isset($this->service_id)){
            $fid = $this->service_id;
            /** @var Service $service */
            $service = Service::model()->findByPk($fid);
            $criteria = new CDbCriteria(['order' => 'sorting ASC']);
            $fields = ChecklistFields::model()->findAllByAttributes(['checklist_id' => $service->checklist_id],
                $criteria);
            foreach ($fields as $field) {
                $checklist = new RequestChecklistFields();
                $checklist->request_id = $this->id;
                $checklist->checklist_field_id = $field->id;
                $checklist->sorting = $field->sorting;
                $checklist->save(false);
            }
        }
// согласование
        $mstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 7));

        if ($this->Status == $mstatus->name) {
            if(isset($this->service_id)){
                $service = Service::model()->findByPk(array('id' => $this->service_id));
                $this->matching = $service->matchings;
                $matching_array = explode(',', $service->matchings);

                $params = [':request_id' => $this->id];
                $iteration = (int)yii::app()->db
                    ->createCommand('select max(iteration) from request_matching_reaction where request_id = :request_id')
                    ->queryScalar($params);

                $matchings = [];

                if ($iteration === 0) {
                    foreach ($matching_array as $matching) {

                        $matchings[] = $matching;

                        $requestMatchingReaction = new RequestMatchingReaction();
                        $requestMatchingReaction->request_id = $this->id;
                        $requestMatchingReaction->user_id = (int)$matching;
                        $requestMatchingReaction->iteration = $iteration + 1;
                        $requestMatchingReaction->save(false);
                    }
                } else {
                    foreach ($matching_array as $matching) {
                        $params = [':request_id' => $this->id, ':user_id' => $matching];

                        $sql = 'select * from request_matching_reaction where request_id = :request_id AND user_id = :user_id ORDER BY id DESC LIMIT 1';
                        $rmrOld = RequestMatchingReaction::model()->findBySql($sql, $params);

                        $matchings[] = $matching;

                        if (!$rmrOld || $rmrOld->checked > 1) {
                            $requestMatchingReaction = new RequestMatchingReaction();
                            $requestMatchingReaction->request_id = $this->id;
                            $requestMatchingReaction->user_id = (int)$matching;
                            $requestMatchingReaction->iteration = $iteration + 1;
                            $requestMatchingReaction->save(false);
                        } else {
                            $rmrOld->iteration = $iteration + 1;
                            $rmrOld->save(false);
                        }
                    }
                }

            }
        }

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

        Yii::app()->language = Yii::app()->params['languages'];
        //Добавляем в историю запись о создании заявки
        if ($this->isNewRecord) {

            $this->AddHistory(Yii::t('main-ui', 'Ticket created'));
            $this->AddHistory(Yii::t('main-ui', 'Start time is set to: ') . '<b>' . $this->StartTime . '</b>');
            $this->AddHistory(Yii::t('main-ui', 'End time is set to: ') . '<b>' . $this->EndTime . '</b>');
            if ($this->cunits) {
                $this->AddHistory(Yii::t('main-ui', 'Assigned unit: ') . '<b>' . $this->cunits . '</b>');
            }
            if ($this->Status){
                $this->AddHistory(Yii::t('main-ui', 'Ticket status is set to: ') . $this->slabel);
            }
        }
        $message = 'Created new E-mail ticket #' . $this->id . ' named "' . $this->Name . '"';
        Yii::log($message, 'created', 'CREATED');

        //Блок отправки уведомлений
        $key = $this->isNewRecord ? 1 : 0;
        Email::prepare($this->id, $key, $afiles);

        return parent::afterSave();
    }

    public function beforeValidate()
    {
        $fid = $_POST['PortalRequest']['service_id'];

        $service = Service::model()->findByPk($fid);
        $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset));
        $fields_arr = array();
        foreach ($fields as $field) {
            if (isset($_POST['PortalRequest'][$field->id])) {
                $fields_arr[$field->id] = $_POST['PortalRequest'][$field->id];
                if ($field->req and empty($_POST['PortalRequest'][$field->id])) {
                    $this->addError($field->id, 'Необходимо заполнить поле "' . $field->name . '".');
                }
            }
        }
        if (PHP_SAPI !== 'cli') {
            Yii::app()->session->add('fields', $fields_arr);
        }
        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        return parent::afterValidate();
    }

    /**
     * @param $action
     */
    public function AddHistory($action)
    {
        $history = new History();
        $history->datetime = date("d.m.Y H:i");
        $history->cusers_id = $this->fullname;
        $history->zid = $this->id;
        $history->action = $action;
        $history->save(false);
    }

    /**
     * @param $user
     * @param $message
     * @param $id
     */
    public function alert_send($user, $message, $id)
    {
        $alert = new Alerts();
        $alert->user = $user;
        $alert->name = $id;
        $alert->message = $message;
        $alert->save();
    }
}
