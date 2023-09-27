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
 * @property integer $sla
 */
class MailRequest extends CActiveRecord
{
    use RequestProcessingRuleTrait;

    public $flds;
    public $lastactivity;
    public $channel;
    public $channel_icon;
    public $getmailconfig;

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
        return 'request';
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
     * @return array
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [
                'Name, Type, ZayavCategory_id, Date, StartTime, EndTime, depart, fStartTime, fEndTime, Priority, Managers_id, CUsers_id, service_name, Address, company, closed, getmailconfig',
                'length',
                'max' => 50
            ],
            ['fullname, creator, channel, channel_icon', 'length', 'max' => 100],
            ['sla', 'numerical', 'integerOnly' => true],
            ['slabel, Status', 'length', 'max' => 400],
            ['image', 'length', 'max' => 250],
            ['key', 'length', 'max' => 32],
            // name, email, subject and body are required
            ['Name, Content, CUsers_id', 'required'],
            ['ZayavCategory_id, service_id', 'required', 'on' => 'update'],
            [
                'image',
                'file',
                'types' => 'doc,docx,xls,xlsx,odt,ods,pdf,jpg, jpeg, png, gif',
                'allowEmpty' => true
            ],
            // array('timestamp', 'date', 'format'=>'dd.MM.yyyy'),
            [
                'Name, Content, slabel, Comment, StartTime, fStartTime, EndTime, fEndTime, service_id, flds, getmailconfig,sla',
                'safe'
            ],
            ['Name, Content, Comment', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [
                'id, Name, mfullname, Type, ZayavCategory_id, Date, lastactivity, timestamp, StartTime, EndTime, fStartTime, fEndTime, Status, slabel, sla, creator, Priority, Managers_id, CUsers_id, service_name, service_id, Address, company, Content, Comment, cunits, closed, flds, channel_icon, channel',
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
            'sla' => Yii::t('main-ui', 'Sla'),
            'slabel' => Yii::t('main-ui', 'Status'),
            'Priority' => Yii::t('main-ui', 'Priority'),
            'Managers_id' => Yii::t('main-ui', 'Manager'),
            'CUsers_id' => Yii::t('main-ui', 'User'),
            'depart' => Yii::t('main-ui', 'Department'),
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
     * @return bool
     * @throws CException
     * @throws CHttpException
     * @throws Exception
     */
    public function beforeSave()
    {

        var_dump("IN MODEL");
        var_dump($this);
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
                            $success = $this->checkCondition($this->Name, $condition->val, $condition->condition);
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
        require(dirname(__FILE__) . '/../config/license.php');
        if (constant('redaction') == 'DEMO') {
            $lastId = Yii::app()->db->createCommand('SELECT id FROM request ORDER BY id DESC LIMIT 1')->queryScalar();
            if ((int)$lastId > 50) {
                throw new CHttpException(400, 'Ваша лицензия не позволяет создавать больше 50 заявок!');
            }
        }

        if ($ruleService_name !== false) {
            $this->service_name = $ruleService_name;
            $this->service_id = $ruleService_id;
        }
        $service = Service::model()->findByPk(array('id' => $this->service_id));

        
//         if (isset($service)) {
//             if ($rulePriority !== false) {
//                 $service->priority = $rulePriority;
//             }
//             $prior = Zpriority::model()->findByAttributes(array('name' => $service->priority));
//             $sla = Sla::model()->findByAttributes(array('name' => $service->sla));
// //            if (isset($service->watcher)){
// //              $this->watchers = $service->watcher;
// //          }
//         } else {
            $prior = Zpriority::model()->findByAttributes(array('name' => Yii::app()->params['zdpriority']));
            // $sla = Sla::model()->findByPk(Yii::app()->params['zdsla']);
            $sla = Sla::model()->findByPk($this->sla);
            var_dump(">>>>>>>>>>>>>");
            var_dump($this->sla);

        // }
        if ($rulePriority !== false) {
            $this->Priority = $rulePriority;
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
                $this->closed = 1;
            } else {
                $this->Status = 'Открыта';
                $this->slabel = '<span class="label label-success">Открыта</span>';
                $this->closed = 1;
            }

        } else {
            //Ищем в модели Status запись с аттрибутом close = 1 этот статус новой заявки.
            $nstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 1));
            $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
            $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
            $rstatus = Status::model()->findByAttributes(['enabled' => 1, 'close' => 4]);
            $sstatus = Status::model()->findByAttributes(['enabled' => 1, 'close' => 5]);
            $cstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 6));

            if ($nstatus) {
                $this->Status = $nstatus->name;
                $this->slabel = $nstatus->label;
                $this->closed = 1;
            } else {
                $this->Status = 'Открыта';
                $this->slabel = '<span class="label label-success">Открыта</span>';
                $this->closed = 1;
            }

            if ($this->Status == $wstatus->name) {
                $this->Status = $wstatus->name;
                $this->slabel = $wstatus->label;
                $this->closed = 2;
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

        if ($ruleManagers_id !== false) {
            $this->mfullname = $ruleMfullname;
            $this->Managers_id = $ruleManagers_id;
        }

        if ($ruleGfullname !== false) {
            $this->gfullname = $ruleGfullname;
            $this->groups_id = $ruleGroups_id;
        }

        if ($ruleDepart !== false) {
            $this->depart = $ruleDepart;
            $this->depart_id = $ruleDepart_id;
        }


        return parent::beforeSave();
    }

    /**
     *
     */
    public function afterSave()
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $afiles = array();
        Yii::app()->language = Yii::app()->params['languages'];
        //Добавляем в историю запись о создании заявки
        if ($this->isNewRecord) {

            /***********************************/
            $service = Service::model()->findByPk($this->service_id);
            $fields = FieldsetsFields::model()->findAllByAttributes(array('fid' => $service->fieldset));
            foreach ($fields as $field) {
                if (isset($this->flds[$field->id])) {
                    $fieldset = new RequestFields();
                    $fieldset->rid = $this->id;
                    $fieldset->name = $field->name;
                    $fieldset->type = $field->type;
                    $fieldset->value = $this->flds[$field->id];
                    $fieldset->save(false);
                }
            }
            /***********************************/

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

            $this->AddHistory(Yii::t('main-ui', 'Ticket created'));
            $this->AddHistory(Yii::t('main-ui', 'Start time is set to: ') . '<b>' . $this->StartTime . '</b>');
            $this->AddHistory(Yii::t('main-ui', 'End time is set to: ') . '<b>' . $this->EndTime . '</b>');
            if ($this->cunits) {
                $this->AddHistory(Yii::t('main-ui', 'Assigned unit: ') . '<b>' . $this->cunits . '</b>');
            }
            if ($this->Status){
                $this->AddHistory(Yii::t('main-ui', 'Ticket status is set to: ') . $this->slabel);
            }

            $mstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 7));

            if ($this->Status == $mstatus->name) {
                if(isset($this->service_id)){
                    $service = Service::model()->findByPk(array('id' => $this->service_id));
                    $this->matching = $service->matchings;
                    if(isset($service->matchings) AND !empty($service->matchings)){
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
            }
        }
        $message = 'Created new E-mail ticket #' . $this->id . ' named "' . $this->Name . '"';
        Yii::log($message, 'created', 'CREATED');
        var_dump("BEFORE SAVE END");
        return parent::afterSave();
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
