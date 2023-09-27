<?php


/**
 * Class RequestController
 */
class RequestController extends Controller
{
    public $layout = '//layouts/design3';

    /**
     * @return array
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    /**
     * @return array
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'get_child', 'grid', 'grid2', 'selectuser', 'getevents'],
                'roles' => ['listRequest'],
            ],
            [
                'allow',
                'actions' => ['suspend'],
                'roles' => ['canSuspendRequest'],
            ],
            [
                'allow',
                'actions' => ['view', 'viewsingle', 'export', 'reorder', 'checklist_check', 'reactionFromMail'],
                'roles' => ['viewRequest'],
            ],
            [
                'allow',
                'actions' => [
                    'update',
                    'copy',
                    'addcomment',
                    'SelectObject',
                    'release',
                    'updName',
                    'updUnits',
                    'updTcategory',
                    'updUser',
                    'updUser2',
                    'SelectFObject',
                    'SelectFObject2',
                    'updWatchers',
                    'updCategory',
                    'assign',
                    'assignGroup',
                    'Addsubs',
                    'addchild',
                    'deletesub',
                    'deletefile',
                    'UpdateAdmObject',
                    'SelectAdmObject',
                    'SelectDepart',
                    'SelectTemplate',
                    'SelectKB',
                    'SetFields',
                    'Merge',
                    'rating',
                    'mergeList',
                    'setStatus',
                    'setStatusOne',
                    'sort',
                    'move',
                    'checkupdates',
                    'setUser',
                    'setGroup',
                    'split',
                    'inJob',
                    'inClose',
                    'getservices',
                    'addban',
                    'inline',
                    'batchUpdateWithComment',
                    'setStatusWithComment',
                ],
                'roles' => ['updateRequest'],
            ],
            [
                'allow',
                'actions' => [
                    'create',
                    'createfromcall',
                    'SelectObject',
                    'SelectAdmObject',
                    'SetFields',
                    'SetFields2',
                    'createMerge',
                    'Settemplate',
                    'SelectPriority',
                    'SelectSLA',
                    'selectService',
                    'getservices'
                ],
                'roles' => ['createRequest'],
            ],
            [
                'allow',
                'actions' => ['updStartTime'],
                'roles' => ['updateDatesRequest'],
            ],
            [
                'allow',
                'actions' => ['batchUpdate'],
                'roles' => ['batchUpdateRequest'],
            ],
            [
                'allow',
                'actions' => ['batchDelete'],
                'roles' => ['batchDeleteRequest'],
            ],
            [
                'allow',
                'actions' => ['delete'],
                'roles' => ['deleteRequest'],
            ],
            [
                'allow',
                'actions' => ['Addsubs'],
                'roles' => ['canAddCommentsRequest'],
            ],
            [
                'allow',
                'actions' => ['printForm'],
                'roles' => ['printRequest'],
            ],
            [
                'allow',
                'actions' => ['createtwsession'],
                'roles' => ['canStartTWSession'],
            ],
            [
                'allow',
                'actions' => ['ratingFromMail', 'reopenFromMail', 'reaction'],
                'users' => ['*'],
            ],

            [
                'deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'eexcelview' => [
                'class' => 'ext.eexcelview.EExcelBehavior',
            ]
        ];
    }

    /**
     * @param $action
     * @return bool
     */
    protected function beforeAction($action)
    {
        date_default_timezone_set(Yii::app()->params['timezone']);
        return true;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function actions()
    {
        return [
            'reorder' => [
                'class' => 'bootstrap.actions.TbSortableAction',
                'modelName' => 'RequestChecklistFields',
            ]
        ];
    }

    /**
     * @param $id
     * @param $reaction
     * @param null $user_id
     */
    public function actionReactionFromMail($id, $reaction, $user_id = null)
    {
        if ($reaction == RequestMatchingReaction::REACTION_AGREED) {
            $this->redirect(['reaction', 'id' => $id, 'reaction' => $reaction]);
        }

        $this->redirect(['view', 'id' => $id, 'reaction' => $reaction]);
    }

    /**
     * @param int $id
     * @param int $reaction
     * @param null $user_id
     *
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionReaction($id, $reaction, $user_id = null)
    {
        $user_id = !empty($user_id) ? $user_id : Yii::app()->user->id;

        $params = [':request_id' => $id, ':user_id' => $user_id];
        $iteration = (int)yii::app()->db
            ->createCommand('select max(iteration) from request_matching_reaction where request_id = :request_id AND user_id = :user_id')
            ->queryScalar($params);

        /** @var $matching RequestMatchingReaction */
        $matching = RequestMatchingReaction::model()->findByAttributes([
            'request_id' => $id,
            'iteration' => $iteration,
            'user_id' => $user_id,
        ]);

        if (empty($matching) || (int)$matching->checked > 0) {
            $this->redirect(['view', 'id' => $id]);
        }

        $matching->checked = $reaction;
        $matching->reaction_time = date('Y-m-d H:i:s');
        $matching->update(['checked', 'reaction_time']);

        if ($reaction == RequestMatchingReaction::REACTION_ADD_INFO || $reaction == RequestMatchingReaction::REACTION_DENIED) {
            $status = Status::model()->findByAttributes(['close' => 8]);
            $model = $this->loadModel($id);

            // Если заявка уже в этом статусе
            if ($model->Status === $status->name) {
                $this->redirect(['view', 'id' => $id]);
            }

            $_POST['Request'] = $model->attributes;
            $_POST['Request']['Status'] = $status->name;
            $model->attributes = $_POST['Request'];
            $model->save(false);
            if ($reaction == RequestMatchingReaction::REACTION_ADD_INFO) {
                $this->AddHistory(Yii::t('main-ui', 'The user has requested additional information for approval'), $id);
            } elseif ($reaction == RequestMatchingReaction::REACTION_DENIED) {
                $this->AddHistory(Yii::t('main-ui', 'The user denied ticket approval'), $id);
            }
        }

        if ($reaction == RequestMatchingReaction::REACTION_AGREED) {
            $matchings = RequestMatchingReaction::model()->findAllByAttributes([
                'request_id' => $id,
                'iteration' => $iteration,
            ]);

            $allAgreed = true;

            foreach ($matchings as $matching) {
                if ($matching->checked != RequestMatchingReaction::REACTION_AGREED) {
                    $allAgreed = false;
                    break;
                }
            }

            if ($allAgreed) {
                $status = Status::model()->findByPk(9);
                $model = $this->loadModel($id);

                $_POST['Request'] = $model->attributes;
                $_POST['Request']['Status'] = $status->name;
                $_POST['Request']['matching'] = null;
                $model->attributes = $_POST['Request'];
                $model->save(false);
            }
            $this->AddHistory(Yii::t('main-ui', 'The user approved this ticket'), $id);
        }

        $this->redirect(['view', 'id' => $id]);
    }

    public function actionChecklist_check()
    {
        $id = $_POST['id'];
        $check = RequestChecklistFields::model()->findByPk($id);
        $check_name = ChecklistFields::model()->findByPk($check->checklist_field_id);
        if ($check) {
            $check->checked = $check->checked ? 0 : 1;
            if ($check->checked) {
                $this->AddHistory(Yii::t('main-ui',
                        'Checklist value ') . '<b>' . $check_name->name . '</b>' . Yii::t('main-ui',
                        ' is set to checked'), $check->request_id);
            } else {
                $this->AddHistory(Yii::t('main-ui',
                        'Checklist value ') . '<b>' . $check_name->name . '</b>' . Yii::t('main-ui',
                        ' is set to unchecked'), $check->request_id);
            }
            $check->checked_user_id = $check->checked ? Yii::app()->user->id : null;
            $check->checked_time = $check->checked ? date('Y-m-d H:i:s') : null;
            $check->save(false);
        }
    }


    /**
     * Приостановка выполнения заявки
     *
     * @param int $id
     * @throws Exception
     */
    public function actionSuspend($id)
    {
        $request = Request::model()->findByPk($id);
        $arr = $request->getAttributes();
        if (empty($request->service_rl)) {
            $sla = Sla::model()->findByPk(Yii::app()->params['zdsla']);
        } else {
            $sla_name = $request->service_rl->sla;
            $sla = Sla::model()->findByAttributes(['name' => $sla_name]);
        }

        if ($sla) {
            $paused = $request->paused;

            $request->paused = $request->paused === null ? date('Y-m-d H:i:s') : null;
            $arr['paused'] = $request->paused;
            $arr['waspaused'] = 1;

            // Постановка на паузу
            if (null === $request->paused) {
                $workingTime = WorkingTimeComponent::createFromSla($sla);

                $priority = Zpriority::model()->findByAttributes(['name' => $request->Priority]);
                $time_passed = $workingTime->calculatingWorkingTime($paused, date('Y-m-d H:i:s'));

                $request->paused_total_time = $arr['paused_total_time'] = ($time_passed + (int)$request->paused_total_time);

                if (empty($request->timestampfStart)) {
                    $reaction_min = 60 * (int)$sla->retimeh + (int)$sla->retimem + (int)$priority->rcost;
                    $new_reaction = $workingTime->modify($reaction_min + $request->paused_total_time,
                        $request->timestamp);
                    $request->StartTime = $arr['StartTime'] = date('d.m.Y H:i', strtotime($new_reaction));
                    $request->timestampStart = $arr['timestampStart'] = $new_reaction;
                    $this->AddHistory(Yii::t('main-ui', 'Start time is set to: ') . '<b>' . date('d.m.Y H:i',
                            strtotime($new_reaction)) . '</b>', $id);
                }

                if (empty($request->timestampfEnd)) {
                    $reshenie_min = 60 * (int)$sla->sltimeh + (int)$sla->sltimem + (int)$priority->scost;
                    $new_reshenie = $workingTime->modify($reshenie_min + $request->paused_total_time,
                        $request->timestamp);
                    $request->EndTime = $arr['EndTime'] = date('d.m.Y H:i', strtotime($new_reshenie));
                    $request->timestampEnd = $arr['timestampEnd'] = $new_reshenie;
                    $this->AddHistory(Yii::t('main-ui', 'End time is set to: ') . '<b>' . date('d.m.Y H:i',
                            strtotime($new_reshenie)) . '</b>', $id);
                }

                /** @var null|Status $status */
                $statusId = $request->previous_paused_status_id ?: 1;
                $status = Status::model()->findByPk($statusId);
                if ($status) {
                    $request->Status = $arr['Status'] = $status->name;
                    $request->slabel = $arr['slabel'] = $status->label;
                    $request->previous_paused_status_id = $arr['previous_paused_status_id'] = null;
                }
            } else {
                // Снятие с паузы
                /** @var null|Status $status */
                $status = Status::model()->findByAttributes(['close' => 10]);
                $statusOld = Status::model()->findByAttributes(['name' => $request->Status]);
                if ($status) {
                    $request->Status = $arr['Status'] = $status->name;
                    $request->slabel = $arr['slabel'] = $status->label;
                    if ($statusOld) {
                        $request->previous_paused_status_id = $arr['previous_paused_status_id'] = $statusOld->id;
                    } else {
                        $request->previous_paused_status_id = $arr['previous_paused_status_id'] = 1;
                    }
                }
            }

            $_POST['Request'] = $arr;
            $request->attributes = $_POST['Request'];
            $request->save(false);
            Request::model()->updateByPk($id, $arr);
        }

        $this->redirect(['view', 'id' => $id]);
    }

    public function actionInline()
    {
        if (isset($_POST)) {
            Request::model()->updateByPk($_POST['id'], ['Content' => $_POST['text']]);
        }
        $this->AddHistory(Yii::t('main-ui', 'Content is set to: ') . $_POST['text'], $_POST['id']);
    }

    public function actionGetEvents()
    {
        $model = new Request('searchmain');
        $dp = $model->searchmain();
        $allCronReqs = $dp->getData();
        $json = [];
        if (!empty($allCronReqs)) {
            foreach ($allCronReqs as $cronReq) {
                $status = Status::model()->findByAttributes(['name' => $cronReq->Status]);
                /** @var $cronReq CronReq */
                $json[] = [
                    'id' => $cronReq->id,
                    'title' => $cronReq->Name,
                    'overlap' => true,
                    'start' => date("H:i", strtotime($cronReq->EndTime)),
                    'end' => date("H:i", strtotime($cronReq->EndTime . "+1 minutes")),
                    'color' => $status->tag,
                    'allDay' => false,
                    'dow' => [0, 1, 2, 3, 4, 5, 6],
                    'ranges' => [
                        [
                            'start' => date("Y-m-d H:m:i", strtotime($cronReq->timestampEnd)),
                            'end' => date('Y-m-d', strtotime($cronReq->timestampEnd)) . ' 23:59:59',
                        ]
                    ]
                ];
            }
        }
        echo json_encode($json);
    }

    public function actionCreatetwsession()
    {
        if (isset($_GET['request'])) {
            $tw_api = new TeamViewer;
            $ping = $tw_api->ping(Yii::app()->params['TeamViewerAccessToken']);
            if (!$ping) {
                $script = "
<script>
    function openTW() {
        var client_id = '" . Yii::app()->params['TeamViewerClientId'] . "';
        var client_secret = '" . Yii::app()->params['TeamViewerClientSecret'] . "';
        window.open('" . CHtml::normalizeUrl(['config/twtest2', 'client_id' => null]) . "' + client_id + '&client_secret=' + client_secret,'teamviewer','width=640,height=500');
    }
</script>
                \r\n";
                $link = '<br><a class="btn btn-warning" href="javascript:(openTW())">Получить токен</a>';
                echo $script . $link;
                exit;
            }
            $session = $tw_api->createSessions(Yii::app()->params['TeamViewerAccessToken']);
            if (isset($session['code'])) {
                $teamviewer_sessions = TeamviewerSessions::model()->findByAttributes(['request_id' => $_GET['request']]);
                if (!$teamviewer_sessions) {
                    $teamviewer_sessions = new TeamviewerSessions;
                }
                $teamviewer_sessions->request_id = $_GET['request'];
                $teamviewer_sessions->code = $session['code'];
                $teamviewer_sessions->supporter_link = $session['supporter_link'];
                $teamviewer_sessions->end_customer_link = $session['end_customer_link'];
                $teamviewer_sessions->valid_until = date('Y-m-d H:i:s', strtotime($session['valid_until']));
                $teamviewer_sessions->save();
                if (Yii::app()->user->checkAccess('systemManager')) {
                    $this->widget('bootstrap.widgets.TbButton', [
                        'type' => 'warning',
                        'buttonType' => 'link',
                        'htmlOptions' => ['target' => '_blank'],
                        'url' => $session['supporter_link'],
                        'label' => Yii::t('main-ui', 'Start session ') . $session['code']
                    ]);
                }
                if (Yii::app()->user->checkAccess('systemUser')) {
                    $this->widget('bootstrap.widgets.TbButton', [
                        'type' => 'warning',
                        'buttonType' => 'link',
                        'htmlOptions' => ['target' => '_blank'],
                        'url' => $session['end_customer_link'],
                        'label' => Yii::t('main-ui', 'Start session ') . $session['code']
                    ]);
                }
                if (Yii::app()->user->checkAccess('systemAdmin')) {
                    $ret = "<div><b>Ссылка на подключение к сессии {$session['code']}: <a href='{$session['supporter_link']}'>для исполнителя</a> | <a href='{$session['end_customer_link']}'>для заказчика</a> ( <a href='{$session['end_customer_link']}'>{$session['end_customer_link']}</a> )</b></div>";
                    echo $ret;
                }
                $request = Request::model()->findByPk($_GET['request']);
                $usermail = CUsers::model()->findByAttributes(['Username' => $request->CUsers_id]);
                $managermail = CUsers::model()->findByAttributes(['Username' => $request->Managers_id]);
                $subject = '[Ticket #' . $request->id . '] ' . $request->Name . '';
                $message = "Инициирована сессия TeamViewer! <br><b>Ссылка на подключение к сессии {$session['code']}: <a href='{$session['supporter_link']}'>для исполнителя</a> | <a href='{$session['end_customer_link']}'>для заказчика</a> ( <a href='{$session['end_customer_link']}'>{$session['end_customer_link']}</a> )</b>";
                $uaddress = [$usermail->Email];
                $maddress = isset($managermail) ? [$managermail->Email] : '';
                if (isset($request->Managers_id)) {
                    if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                        Yii::app()->mailQueue->push($maddress, $subject, $message, $priority = 0, $from = '', null,
                            null);
                    } else {
                        SendMail::send($maddress, $subject, $message, $afiles = null, $request->getmailconfig);
                    }
                }
                if (isset($request->CUsers_id)) {
                    if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                        Yii::app()->mailQueue->push($uaddress, $subject, $message, $priority = 0, $from = '', null,
                            null);
                    } else {
                        SendMail::send($uaddress, $subject, $message, $afiles = null, $request->getmailconfig);
                    }
                }
                if (!isset($request->Managers_id) and isset($request->groups_id)) {
                    $groups = Groups::model()->findByPk($request->groups_id);
                    $managers = explode(",", $groups->users);
                    if (isset($managers)) {
                        foreach ($managers as $manager_id) {
                            $email = CUsers::model()->findByPk($manager_id);
                            if (Yii::app()->user->name !== $email->Username) {
                                if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                                    Yii::app()->mailQueue->push($email->Email, $subject, $message, $priority = 0,
                                        $from = '', null, null);
                                } else {
                                    SendMail::send($email->Email, $subject, $message, $afiles = null,
                                        $request->getmailconfig);
                                }
                            }
                        }
                    }
                }
                exit;
            }
        }
    }

    public function actionExport()
    {
        $connection = Yii::app()->db;
        $columns_query = 'SELECT * FROM `tbl_columns` `t` WHERE `t`.`id`="request-grid-full_' . Yii::app()->user->id . '"';
        $columns = $connection->createCommand($columns_query)->queryAll();
        $columns_array = explode('||', $columns[0]['data']);
        if (!empty($columns)) {
            foreach ($columns_array as $item) {
                if ($item !== 'Действия') {
                    if ($item !== 'slabel') {
                        $new_arr[]['name'] = $item;
                    } else {
                        $new_arr[]['name'] = 'Status';
                    }
                }
            }
            $this->toExcel($_SESSION['request_records'],
                $columns = $new_arr,

                Yii::t('main-ui', 'Requests'),
                array(
                    'creator' => 'Univef',
                    'title' => Yii::t('main-ui', 'Requests'),
                ),
                'Excel5'
            );
        } else {
            throw new CHttpException(500, Yii::t('main-ui', 'Select columns settings to export data.'));
        }
    }

    public function actionKanban()
    {
        $model = new Request('searchmain');

        $this->render('kanban', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        
        if (isset($_GET['reaction'])) {
            $reaction = $_GET['reaction'];
        } else {
            $reaction = null;
        }

        $statusClose = Status::model()->findByAttributes(['close' => 3]);
        $closeNeedComment = (int)$statusClose->is_need_comment;
        $closeNeedRating = (int)$statusClose->is_need_rating;

        $statusSuspend = Status::model()->findByAttributes(['close' => 10]);
        $suspendNeedComment = (int)$statusSuspend->is_need_comment;
        $suspendNeedRating = (int)$statusSuspend->is_need_rating;

        $statusInJob = $status = Status::model()->findByAttributes(['close' => 2]);
        $inJobNeedComment = (int)$statusInJob->is_need_comment;
        $inJobNeedRating = (int)$statusInJob->is_need_rating;

        $model = $this->loadModel($id);
        // $status_temp = Status::model()->findByAttributes(['name' => $model->Status]);
        // var_dump($status_temp);
        // $managerCriteria = new CDbCriteria;
        // $managerCriteria->condition = 'name LIKE :name';
        // $requestsCriteria->params = array(
        //     'name'=>$manager->fullname,
        // );
        // $manager = Cusers::model()->findOne($requestsCriteria);


        //autoinwork on open the ticket
        $service = Service::model()->findByPk($model->service_id);
        if ($model->closed == null and Yii::app()->user->checkAccess('systemManager') and $service->autoinwork == 1) {
            $manager = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
            $status = Status::model()->findByAttributes(['close' => 2]);
            if ($model->groups_id !== null and $model->groups_id !== '0' and $model->Managers_id == null) {
                $criteria_grp = new CDbCriteria;
                $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
                //$criteria_grp->compare('users', Yii::app()->user->id, true);
                $grp = Groups::model()->findByPk($model->groups_id, $criteria_grp);
            }
            if (($model->creator_id == Yii::app()->user->id and $model->Managers_id == Yii::app()->user->name) or ($model->creator_id !== Yii::app()->user->id and $model->Managers_id == Yii::app()->user->name)) {
                $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                $_POST['Request']['mfullname'] = $manager->fullname;
                $_POST['Request']['CUsers_id'] = $model->CUsers_id;
                $_POST['Request']['Status'] = $status->name;
                $_POST['Request']['service_id'] = $model->service_id;
                $_POST['Request']['Priority'] = $model->Priority;
                $_POST['Request']['Content'] = $model->Content;
                $model->attributes = $_POST['Request'];
                $model->save(false);
            } elseif (isset($grp)) {
                $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                $_POST['Request']['mfullname'] = $manager->fullname;
                $_POST['Request']['CUsers_id'] = $model->CUsers_id;
                $_POST['Request']['Status'] = $status->name;
                $_POST['Request']['service_id'] = $model->service_id;
                $_POST['Request']['Priority'] = $model->Priority;
                $_POST['Request']['Content'] = $model->Content;
                $model->attributes = $_POST['Request'];
                $model->save(false);
            }
        }

        $manager = CUsers::model()->findByAttributes(['fullname' => $model->mfullname]);
        $company = Companies::model()->findByAttributes(['name' => $model->company]);
        if (Yii::app()->user->checkAccess('listContracts')) {
            $criteriac = new CDbCriteria;
            $criteriac->addSearchCondition('customer_id', $company->id, false, 'OR', 'LIKE');
            $criteriac->addSearchCondition('company_id', $company->id, false, 'OR', 'LIKE');
            $contracts = Contracts::model()->findAll($criteriac);
        }
        if (Yii::app()->user->checkAccess('systemUser')) {
            //$subs = Comments::model()->findAllByAttributes(array('rid' => $id, 'show' => 0));
            $subs = new CActiveDataProvider('comments', [
                'criteria' => [
                    'condition' => 'rid=:rid AND `show`=0',
                    'order' => 'id DESC',
                    'params' => [':rid' => $id],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);
        } else {
            //$subs = $model->comms;
            $subs = new CActiveDataProvider('comments', [
                'criteria' => [
                    'condition' => 'rid=:rid',
                    'order' => 'id DESC',
                    'params' => [':rid' => $id],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);
        }
        $units = explode(",", trim($model->cunits));
        $criteria = new CDbCriteria;
        $criteria->addInCondition('name', $units, true, 'OR');
        $unit = Cunits::model()->findAll($criteria);
        $merged = Request::model()->findAllByAttributes(['pid' => $model->id]);
        $history = $model->history;
        $path = Yii::getPathOfAlias('webroot') . '/media/' . $id;
        if (is_dir($path)) {
            $files = $this->myscandir($path);
        }
        if (isset($model->CUsers_id)) {
            $user = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
        } else {
            $user = null;
        }

        $call = Calls::model()->findByAttributes(['rid' => (int)$id]);

        

        $this->render('view', [
            'model' => $model,
            'unit' => $unit,
            'user' => $user,
            'merged' => $merged,
            'company' => $company ? $company : null,
            'contracts' => $contracts ? $contracts : null,
            'files' => $files ? $files : null,
            'history' => $history,
            'subs' => $subs,
            'mid' => $manager ? $manager : null,
            'mphone' => isset($manager->Phone) ? $manager->Phone : null,
            'memail' => isset($manager->Email) ? $manager->Email : null,
            'mposition' => isset($manager->position) ? $manager->position : null,
            'call' => $call,
            'closeNeedComment' => $closeNeedComment,
            'suspendNeedComment' => $suspendNeedComment,
            'inJobNeedComment' => $inJobNeedComment,
            'closeNeedRating' => $closeNeedRating,
            'suspendNeedRating' => $suspendNeedRating,
            'inJobNeedRating' => $inJobNeedRating,
            'reaction' => $reaction,
        ]);
    }

    public function actionGrid()
    {
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['assetPageCount'] = $_GET['pageCount'];
        }

        $model = new CUsers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CUsers'])) {
            $model->attributes = $_GET['CUsers'];
        }
        $this->renderPartial('_ugrid', [
            'model' => $model,
        ]);
    }

    public function actionGrid2()
    {
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['assetPageCount'] = $_GET['pageCount'];
        }

        $model = new CUsers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CUsers'])) {
            $model->attributes = $_GET['CUsers'];
        }
        $this->renderPartial('_ugrid2', [
            'model' => $model,
        ]);
    }

    public function loadModel($id)
    {
        $usr = Yii::app()->user->name;
        $user = CUsers::model()->findByAttributes(['Username' => $usr]);
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
                $model = Request::model()->findByPk($id,
                    'FIND_IN_SET(gr_id, :gr_ids)', [
                        ':gr_ids' => implode(',', $all_user_gr)
                    ]);
                // $model = Request::model()->findByPk($id,
                //     'gr_id IN (:gr_ids)', [
                //         ':gr_ids' => implode(',', $all_user_gr)
                //     ]);

                if (null !== $model) {
                    return $model;
                }
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
                $model = Request::model()->findByPk($id,
                    'FIND_IN_SET(Managers_id, :gr_ids)', [
                        ':gr_ids' => implode(',', $all_user_gr)
                    ]);

                if (null !== $model) {
                    return $model;
                }
            }
        }

        if (Yii::app()->user->checkAccess('viewMyselfRequest')) {
            $model = Request::model()->findByPk($id,
                'CUsers_id = :usr OR watchers LIKE :watch OR matching LIKE :match', [
                    ':usr' => $usr,
                    ':watch' => '%' . $user->fullname . '%',
                    ':match' => '%' . $user->id . '%'
                ]);
        }
        if (Yii::app()->user->checkAccess('viewAssignedRequest')) {
            $request = Request::model()->findByPk($id);
            if ($request->groups_id !== null and $request->groups_id !== '0' and $request->Managers_id == null) {
                $criteria_grp = new CDbCriteria;
                $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
                //$criteria_grp->compare('users', Yii::app()->user->id, true);
                $grp = Groups::model()->findByPk($request->groups_id, $criteria_grp);
                if (isset($grp)) {
                    $model = $request;
                } else {
                    $model = Request::model()->findByPk($id,
                        'watchers LIKE :watch OR find_in_set(' . Yii::app()->user->id . ', matching)', [
                            ':watch' => '%' . $user->fullname . '%',
                        ]);
                }
            } else {
                $model = Request::model()->findByPk($id,
                    'Managers_id = :usr OR watchers LIKE :watch OR  find_in_set(' . Yii::app()->user->id . ', matching)',
                    [
                        ':usr' => $usr,
                        ':watch' => '%' . $user->fullname . '%',
                    ]);
            }
        }
        if (Yii::app()->user->checkAccess('viewMyAssignedRequest')) {
            $request = Request::model()->findByPk($id);
            if ($request->groups_id !== null and $request->groups_id !== '0' and $request->Managers_id == null and $request->CUsers_id !== $usr) {
                $criteria_grp = new CDbCriteria;
                $criteria_grp->addCondition('find_in_set(' . Yii::app()->user->id . ', users)');
                //$criteria_grp->compare('users', Yii::app()->user->id, true);
                $grp = Groups::model()->findByPk($request->groups_id, $criteria_grp);
                if (isset($grp)) {
                    $model = $request;
                } else {
                    $model = Request::model()->findByPk($id,
                        'watchers LIKE :watch OR  find_in_set(' . Yii::app()->user->id . ', matching)', [
                            ':watch' => '%' . $user->fullname . '%',
                        ]);
                }
            } else {
                $model = Request::model()->findByPk($id,
                    'CUsers_id = :usr OR Managers_id = :usr OR watchers LIKE :watch OR  find_in_set(' . Yii::app()->user->id . ', matching)',
                    [
                        ':usr' => $usr,
                        ':watch' => '%' . $user->fullname . '%',
                    ]);
            }
        }
        if (Yii::app()->user->checkAccess('viewMyCompanyRequest')) {
            $company = Companies::model()->findByAttributes(['name' => $user->company]);
            $model = Request::model()->findByPk($id,
                'watchers LIKE :watch OR company LIKE :comp OR  find_in_set(' . Yii::app()->user->id . ', matching)',
                [
                    ':comp' => $company->name,
                    ':watch' => '%' . $user->fullname . '%',
                ]);
        }
        // Если пользователь может видеть все заявки подразделений, где он руководитель
        if (Yii::app()->user->checkAccess('viewMyDepartRequest')) {
            $departs = Depart::model()->findAllByAttributes(['manager_id' => Yii::app()->user->id]);
            $ticket = Request::model()->findByPk($id);
            $model = null;
            foreach ($departs as $depart) {
                if ($ticket->depart_id == $depart->id) {
                    $model = Request::model()->findByPk($id,
                        'watchers LIKE :watch OR depart_id LIKE :comp OR find_in_set(' . Yii::app()->user->id . ', matching)',
                        array(
                            ':comp' => $depart->id,
                            ':watch' => '%' . $user->fullname . '%',
                        ));
                }
            }
            if (!isset($model) and !empty($model)) {
                $model = Request::model()->findByPk($id,
                    'watchers LIKE :watch OR find_in_set(' . Yii::app()->user->id . ', matching)',
                    array(
                        ':watch' => '%' . $user->fullname . '%',
                    ));
            }
        }

        if (Yii::app()->user->checkAccess('viewCompanyRequest')) {
            $request = Request::model()->findByPk($id);
            $company = Companies::model()->findByAttributes([
                'name' => $request->company,
                'manager' => Yii::app()->user->name
            ]);
            $model = Request::model()->findByPk($id,
                'watchers LIKE :watch OR company LIKE :comp OR find_in_set(' . Yii::app()->user->id . ', matching)',
                [
                    ':comp' => $company->name,
                    ':watch' => '%' . $user->fullname . '%',
                ]);
        }

        if (!Yii::app()->user->checkAccess('viewMyselfRequest') and !Yii::app()->user->checkAccess('viewAssignedRequest') and !Yii::app()->user->checkAccess('viewMyAssignedRequest') and !Yii::app()->user->checkAccess('viewMyCompanyRequest') and !Yii::app()->user->checkAccess('viewCompanyRequest') and !Yii::app()->user->checkAccess('viewMyDepartRequest')) {
            $model = Request::model()->findByPk($id);
        }
        if ($model === null) {
            if ($_SERVER['HTTP_REFERER'] == Yii::app()->params['homeUrl'] . '/request/create') {
                Yii::app()->user->setFlash('danger', Yii::t('main-ui',
                        '<strong>Congratulations!</strong> You successfully create a new request.') . ' ' . Yii::t('main-ui',
                        'You dont have rights to access this element!'));
                $this->redirect('index');
            } elseif ($_SERVER['HTTP_REFERER'] == Yii::app()->params['homeUrl'] . '/request/update/' . $id) {
                Yii::app()->user->setFlash('danger', Yii::t('main-ui',
                    'You dont have rights to access this element!'));
                $this->redirect('index');
            } else {
                Yii::app()->user->setFlash('danger', Yii::t('main-ui',
                    'You dont have rights to access this element!'));
                $this->redirect('index');
                //throw new CHttpException(403, Yii::t('main-ui', 'You dont have rights to access this element!'));
            }
        }

        return $model;
    }

    public function ActionGet_child($id)
    {
        $model = Request::model()->findAllByAttributes(['pid' => $id]);
        $this->renderPartial('_relgrid', [
            'gridDataProvider' => $model,
        ]);
    }

    public function ActionAddban()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $banmail = mb_strtolower($_GET['checked']);
            if (isset($banmail)) {
                $exists = Banlist::model()->findByAttributes(['value' => $banmail]);
                if (!isset($exists)) {
                    $model = new Banlist;
                    $model->value = $banmail;
                    if ($model->save()) {
                        Yii::app()->user->setFlash('success',
                            Yii::t('main-ui', 'You have successfully added the Email to Banlist!'));
                    }
                } else {
                    Yii::app()->user->setFlash('danger', Yii::t('main-ui', 'Email already in Banlist!'));
                }
            }
        }
    }

    public function actionViewsingle($id, $alert)
    {
        if ($alert) {
            Alerts::model()->deleteByPk($alert);
        }
        $model = $this->loadModel($id);
        if (Yii::app()->user->checkAccess('systemUser')) {
            $subs = new CActiveDataProvider('comments', [
                'criteria' => [
                    'condition' => 'rid=:rid AND `show`=0',
                    'order' => 'id DESC',
                    'params' => [':rid' => $id],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);
        } else {
            //$subs = $model->comms;
            $subs = new CActiveDataProvider('comments', [
                'criteria' => [
                    'condition' => 'rid=:rid',
                    'order' => 'id DESC',
                    'params' => [':rid' => $id],
                ],
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]);
        }
        $unit = Cunits::model()->findAllByAttributes(['name' => $model->cunits]);
        $history = $model->history;
        $files = explode(",", $model->image);
        $this->render('view', [
            'model' => $this->loadModel($id),
            'unit' => $unit,
            'files' => $files,
            'history' => $history,
            'subs' => $subs,
        ]);
    }

    public function actionAddcomment($id)
    {
        $model = $this->loadModel($id);
        $this->render('cform', [
            'model' => $model,
        ]);
    }

    public function actionToggle()
    {
        return [
            'toggle' => [
                'class' => 'bootstrap.actions.TbToggleAction',
                'modelName' => 'Request',
            ]
        ];
    }

    public function actionInJob($id)
    {
        $model = $this->loadModel($id);
        $manager = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
        $status = Status::model()->findByAttributes(['close' => 2]);
        $_POST['Request']['Managers_id'] = Yii::app()->user->name;
        $_POST['Request']['mfullname'] = $manager->fullname;
        $_POST['Request']['CUsers_id'] = $model->CUsers_id;
        $_POST['Request']['Status'] = $status->name;
        $_POST['Request']['service_id'] = $model->service_id;
        $_POST['Request']['Priority'] = $model->Priority;
        $_POST['Request']['Content'] = $model->Content ? $model->Content : Yii::t('main-ui', 'Not set');
        $model->attributes = $_POST['Request'];
        $model->save(false);
        //$this->AddHistory(Yii::t('main-ui', 'Manager is set to: ') . '<b>' . $manager->fullname . '</b>', $id);
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionInClose($id)
    {
        $model = $this->loadModel($id);
        $criteria = new CDbCriteria;
        $criteria->order = 'id DESC';
        $comment = Comments::model()->findByAttributes(['rid' => $id], $criteria);
        $_POST['Comments']['comment'] = $comment->comment;
        if ($model->CUsers_id !== Yii::app()->user->name) {
            $mngr = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
        } else {
            $mngr = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
        }
        $status = Status::model()->findByAttributes(['close' => 3]);
        $model = Request::model()->findByPk($id);
        $message = 'User ' . Yii::app()->user->name . ' updated ticket #' . $id . ' named "' . $model->Name . '"';
        Yii::log($message, 'updated', 'UPDATED');
        if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
            if (isset($model->gfullname)) {
                $_POST['Request']['gfullname'] = null;
            }
            $_POST['Request']['Managers_id'] = Yii::app()->user->name;
            $_POST['Request']['mfullname'] = $mngr->fullname;
        }
        $_POST['Request']['CUsers_id'] = $model->CUsers_id;
        $_POST['Request']['Status'] = $status->name;
        $_POST['Request']['slabel'] = $status->label;
        $_POST['Request']['service_id'] = $model->service_id;
        $_POST['Request']['Priority'] = $model->Priority;
        $_POST['Request']['Content'] = $model->Content ? $model->Content : Yii::t('main-ui', 'Not set');
        $_POST['Request']['timestampClose'] = null;
        $model->attributes = $_POST['Request'];

        $model->Comment = null;
        $model->save();

        $pRequests = Request::model()->findAllByAttributes(['pid' => $id]);
        if (!empty($pRequests)) {
            foreach ($pRequests as $pRequest) {
                // TODO: Костыль потому что в моделе используются глобальные массивы
                if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                    if (isset($model->gfullname)) {
                        $_POST['Request']['gfullname'] = null;
                    }
                    $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                    $_POST['Request']['mfullname'] = $mngr->fullname;
                }
                $_POST['Request']['CUsers_id'] = $pRequest->CUsers_id;
                $_POST['Request']['Status'] = $status->name;
                $_POST['Request']['slabel'] = $status->label;
                $_POST['Request']['Priority'] = $pRequest->Priority;
                $_POST['Request']['Content'] = $pRequest->Content;
                $_POST['Request']['timestampClose'] = null;
                $pRequest->attributes = $_POST['Request'];
                $pRequest->pid = $model->id;
                $pRequest->save();
            }
        }
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionAssign($id)
    {
        if (isset($_POST['users'])) {
            $model = $this->loadModel($id);
            $mfullname = CUsers::model()->findByAttributes(['Username' => $_POST['users']]);
            /** @var CUsers $user */
            $user = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
            $manager = CUsers::model()->findByAttributes(['Username' => $_POST['users']]);
            Request::model()->updateByPk($id,
                [
                    'Managers_id' => $_POST['users'],
                    'mfullname' => $mfullname->fullname,
                    'lastactivity' => date("Y-m-d H:i:s")
                ]);
            $status_temp = Status::model()->findByAttributes(['name' => $model->Status]);
            $this->AddHistory(Yii::t('main-ui', 'Manager is set to: ') . '<b>' . $mfullname->fullname . '</b>', $id);

            $message = $model->Name . "\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
            $url = Yii::app()->createUrl("request/view", ["id" => $model->id]);
            if (isset($user)) {
                $user->pushMessage($message, $url);
            }
            //if ($status_temp->notify_manager == 1) {
            if ($manager->sendmail == 1) {
                $manager_address = $manager->Email;
                //$message = Messages::model()->findByAttributes(array('name' => $status_temp->mmessage));
                $message = Messages::model()->findByAttributes(['name' => '{escalate}']);
                $subject = $message->subject;
                $this->Mailsend($manager_address, $subject, $manager, $message, $model);
            }
            //}
            if ($status_temp->notify_manager_sms == 1) {
                if ($manager->sendsms == 1) {
                    $managernum = $manager->Phone;
                    $sms = Smss::model()->findByAttributes(['name' => $status_temp->msms]);
                    $this->Smssend($managernum, $manager, $sms, $model);
                }
            }

            //if ($status_temp->notify_user == 1) {
            if ($user->sendmail == 1) {
                $user_address = $user->Email;
                //$message = Messages::model()->findByAttributes(array('name' => $status_temp->message));
                $message = Messages::model()->findByAttributes(['name' => '{escalate}']);
                $subject = $message->subject;
                $this->Mailsend($user_address, $subject, $manager, $message, $model);
            }
            //}

            // var_dump($status_temp);
            // var_dump("!@#!@#!@#!@#");
            if ($status_temp->notify_user_sms == 1) {
                if ($user->sendsms == 1) {
                    $usernum = $user->Phone;
                    $sms = Smss::model()->findByAttributes(['name' => $status_temp->sms]);
                    $this->Smssend($usernum, $manager, $sms, $model);
                }
            }
            $this->redirect(array('view', 'id' => $id));
        }
    }

    public function actionAssignGroup($id)
    {
        if (isset($_POST['groups_id'])) {
            $model = $this->loadModel($id);
            $group = Groups::model()->findByPk($_POST['groups_id']);
            Request::model()->updateByPk($id, [
                'groups_id' => $_POST['groups_id'],
                'gfullname' => $group->name,
                'mfullname' => null,
                'Managers_id' => null,
                'lastactivity' => date('Y-m-d H:i:s')
            ]);
            $this->AddHistory(Yii::t('main-ui', 'Group managers is set to: ') . '<b>' . $group->name . '</b>', $id);

            $group_users = explode(',', $group->users);
            $status_temp = Status::model()->findByAttributes(['name' => $model->Status]);

            foreach ($group_users as $user_id) {
                $manager = CUsers::model()->findByPk($user_id);
                $message = $model->Name . "\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
                $url = Yii::app()->createUrl('request/view', ['id' => $model->id]);
                $manager->pushMessage($message, $url);
                //if ($status_temp->notify_manager == 1) {
                if ($manager->sendmail == 1) {
                    $manager_address = $manager->Email;
                    //$message = Messages::model()->findByAttributes(array('name' => $status_temp->mmessage));
                    $message = Messages::model()->findByAttributes(['name' => '{escalate_group}']);
                    $subject = $message->subject;
                    $this->Mailsend($manager_address, $subject, $manager, $message, $model);
                }
                //}

                if ($status_temp->notify_manager_sms == 1) {
                    if ($manager->sendsms == 1) {
                        $managernum = $manager->Phone;
                        $sms = Smss::model()->findByAttributes(['name' => $status_temp->msms]);
                        $this->Smssend($managernum, $manager, $sms, $model);
                    }
                }
            }
            $this->redirect(['view', 'id' => $id]);
        }
    }

    public function AddHistory($action, $id, $user = null)
    {
        if ($user == null) {
            $cusers_id = CUsers::model()->findByPk(Yii::app()->user->id);
        } else {
            $cusers_id = CUsers::model()->findByAttributes(['Username' => $user]);
        }

        $history = new History();
        $history->datetime = date('d.m.Y H:i');
        $history->cusers_id = $cusers_id->fullname ? $cusers_id->fullname : 'system';
        $history->zid = $id;
        $history->action = $action;
        $history->save(false);
    }

    public function Mailsend($address, $subject, $manager, $message, $model)
    {
        $afiles = null;
        $umessage = $this->MessageGen($message->content, $manager, $model);
        $subject = $this->MessageGen($subject, $manager, $model);
        if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
            //$afiles = json_encode($afiles);
            Yii::app()->mailQueue->push($address, $subject, $umessage, $priority = 0, $from = '', $afiles, null);
        } else {
            SendMail::send($address, $subject, $umessage, $afiles, $model->getmailconfig);
        }
    }

    public function actionPrintForm($id)
    {
        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        $this->layout = 'printlayout';
        $model = $this->loadModel($id);
        $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
        $content = UnitTemplates::model()->findByPk($_POST['template_id']);
        $print = $this->messageGen($content->content, $manager, $model);
        $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', $content->format, 'mm',
            $content->page_width ? [$content->page_width, $content->page_height] : $content->page_format, true,
            'UTF-8');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Univef');
        $pdf->SetTitle($model->Name);
        $pdf->SetSubject($model->id);
        $pdf->SetKeywords($model->Name);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('freemono', '', 10, '', true);
        $pdf->writeHTML($print, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output($model->Name, 'I');
    }

    public function MessageGen($content, $manager, $model)
    {
        $comment = null;
        $criteria = new CDbCriteria;
        $criteria->order = 'id DESC';
        if (isset($_POST['Comments']['comment']) and !empty($_POST['Comments']['comment'])) {
            $fullname = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
            $comment = '<b>Добавлен новый комментарий</b><br>' . $fullname->fullname . ' [' . date('d.m.Y H:i') . '] : ' . $_POST['Comments']['comment'];
        } else {
            $last_comment = Comments::model()->findByAttributes(['rid' => $model->id], $criteria);
            if (isset($last_comment) and !empty($last_comment)) {
                $fullname = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
                $comment = '<b>Добавлен новый комментарий</b><br>' . $fullname->fullname . ' [' . date('d.m.Y H:i') . '] : ' . $last_comment->comment;
            }
        }
        $fields_list = null;
        if (Yii::app()->user->checkAccess('systemUser')) {
            $comments = Comments::model()->findAllByAttributes(['rid' => $model->id, 'show' => 0], $criteria);
        } else {
            $comments = Comments::model()->findAllByAttributes(['rid' => $model->id], $criteria);
        }
        if (!isset($model->Managers_id) and isset($model->groups_id)) {
            $group = Groups::model()->findByPk($model->groups_id);
            $gname = !empty($group->name) ? $group->name : 'Не задано';
            $gphone = !empty($group->phone) ? $group->phone : 'Не задано';
            $gemail = !empty($group->email) ? $group->email : 'Не задано';
        } elseif (isset($model->Managers_id)) {
            $gname = !empty($manager->fullname) ? $manager->fullname : 'Не задано';
            $gphone = !empty($manager->Phone) ? $manager->Phone : 'Не задано';
            $gemail = !empty($manager->Email) ? $manager->Email : 'Не задано';
        }

        if (isset($model->CUsers_id) and !empty($model->CUsers_id)) {
            $username = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
            $udepart = !empty($username->department) ? $username->department : 'Не задано';
            $uposition = !empty($username->position) ? $username->position : 'Не задано';
        }
        $comments_list = null;
        foreach ($comments as $comment) {
            $comments_list .= '<hr><h4>' . $comment->author . ' ' . $comment->timestamp . '</h4><br>' . $comment->comment;
        }
        $fields = $model->flds;
        foreach ($fields as $field) {
            if ($field->type == 'toggle') {
                $fields_list .= '<p>' . $field->name . ': ' . ($field->value == 1 ? 'Да' : 'Нет') . '</p><br>';
            } else {
                $fields_list .= '<p>' . $field->name . ': ' . $field->value . '</p><br>';
            }
        }
        $s_message = Yii::t('message', "$content", [
            '{id}' => $model->id,
            '{name}' => $model->Name,
            '{status}' => $model->Status,
            '{priority}' => $model->Priority,
            '{fullname}' => $model->fullname,
            '{phone}' => $model->phone ? $model->phone : 'Не задано',
            '{department}' => $udepart,
            '{position}' => $uposition,
            '{watchers}' => $model->watchers,
            '{groupname}' => isset($model->gfullname) ? $model->gfullname : 'Не задано',
            '{manager_name}' => $gname,
            '{manager_phone}' => $gphone,
            '{manager_mobile}' => isset($manager->mobile) ? $manager->mobile : 'Не задано',
            '{manager_email}' => $gemail,
            '{room}' => isset($model->room) ? $model->room : 'Не задано',
            '{category}' => $model->ZayavCategory_id,
            '{created}' => $model->Date,
            '{comment_message}' => $comment ? $comment : null,
            '{StartTime}' => $model->StartTime,
            '{fStartTime}' => $model->fStartTime,
            '{EndTime}' => $model->EndTime,
            '{fEndTime}' => $model->fEndTime,
            '{service_name}' => $model->service_name,
            '{address}' => $model->Address,
            '{unit}' => $model->cunits,
            '{company}' => $model->company,
            '{comment}' => $model->Comment,
            '{comments}' => $comments_list,
            '{fields}' => $fields_list,
            '{content}' => $model->Content,
            '{url}' => '<a href="' . Yii::app()->params['homeUrl'] . '/request/view/' . $model->id . '">№ ' . $model->id . '</a>',
        ]);

        return $s_message;
    }

    public function Smssend($usernum, $manager, $sms, $model)
    {
        $usmessage = $this->MessageGen($sms->content, $manager, $model);
        Yii::app()->sms->send_sms($usernum, $usmessage);
    }

    /**
     * @throws CException
     */
    public function actionAddchild($id)
    {
        Yii::app()->session->remove('fields');
        $model = new Request;
        $fields = new RequestFields;
        $this->performAjaxValidation($model);
        $merged_ids = false;
        $user = CUsers::model()->findByPk(Yii::app()->user->id);
        if (isset($user->company) and !empty($user->company)) {
            $company = Companies::model()->findByAttributes(['name' => $user->company]);
            if (isset($company) and !empty($company)) {
                $contracts = Contracts::model()->findAllByAttributes(['customer_id' => $company->id]);
                foreach ($contracts as $contract) {
                    if ($contract->stopservice == 1) {
                        $expiration = strtotime($contract->tildate);
                        $now = strtotime(date('Y-m-d'));
                        if ($now > $expiration) {
                            //Yii::app()->user->setFlash('danger', Yii::t('main-ui', '<strong>Warning!</strong> Contract has expired!') . ' ' .Yii::t('main-ui', 'Contract').' №' .$contract->number . ' - ' . $contract->name);
                            throw new CHttpException(404, Yii::t('main-ui',
                                    'Warning! Contract has expired! You can not create tickets. Prolongate contract: ') . ' №' . $contract->number . ' - ' . $contract->name);
                        }
                    }
                }
            }
        }

        if (isset($_POST['Request'])) {
            $model->attributes = $_POST['Request'];
            $model->pid = $id;
            $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
            if ($_POST['Request']['watchers'] == []) {
                $model->watchers = "";
            }
            
            if ($model->save()) {
                $child = Request::model()->countByAttributes(['pid' => $id]);
                $ch_label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: red; vertical-align: baseline; white-space: nowrap; border: 1px solid red; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . (int)$child . '</span>';
                Request::model()->updateByPk($id, ['child' => $ch_label]);
                Yii::app()->user->setFlash('info',
                    Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
                $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model->id . ' named "' . $model->Name . '"';
                Yii::log($message, 'created', 'CREATED');
                $this->redirect(['view', 'id' => $id]);
            }
        }
        $this->render('create', [
            'model' => $model,
            'fields' => $fields,
            'copy' => null,
            'merged_items' => $merged_ids
        ]);
    }

    /**
     * @throws CException
     */
    public function actionCreate()
    {
        Yii::app()->session->remove('fields');
        $model = new Request;
        $fields = new RequestFields;
        $this->performAjaxValidation($model);
        $merged_ids = false;
        $user = CUsers::model()->findByPk(Yii::app()->user->id);
        if (isset($user->company) and !empty($user->company)) {
            $company = Companies::model()->findByAttributes(['name' => $user->company]);
            if (isset($company) and !empty($company)) {
                $contracts = Contracts::model()->findAllByAttributes(['customer_id' => $company->id]);
                foreach ($contracts as $contract) {
                    if ($contract->stopservice == 1) {
                        $expiration = strtotime($contract->tildate);
                        $now = strtotime(date('Y-m-d'));
                        if ($now > $expiration) {
                            //Yii::app()->user->setFlash('danger', Yii::t('main-ui', '<strong>Warning!</strong> Contract has expired!') . ' ' .Yii::t('main-ui', 'Contract').' №' .$contract->number . ' - ' . $contract->name);
                            throw new CHttpException(404, Yii::t('main-ui',
                                    'Warning! Contract has expired! You can not create tickets. Prolongate contract: ') . ' №' . $contract->number . ' - ' . $contract->name);
                        }
                    }
                }
            }
        }

        if (isset($_POST['Request'])) {
            $model->attributes = $_POST['Request'];
            // var_dump($model->attributes);
            // die();
            $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
            if ($model->save()) {
                if (isset($_POST['merged-items']) and !empty($_POST['merged-items'])) {
                    $merge_all = explode(',', $_POST['merged-items']);

                    foreach ($merge_all as $merge_req_id) {
                        $req = Request::model()->findByPk($merge_req_id);
                        $req->updateByPk($merge_req_id, ['update_by' => '1', 'pid' => $model->id]);
                        $req->saveOldFields();
                        $req->replaceByValues($model,
                            ['StartTime', 'EndTime', 'Priority', 'mfullname', 'Status', 'slabel']);
                        $merged_requests = Request::model()->findAllByAttributes(['pid' => $merge_req_id]);
                        if (isset($merged_requests)) {
                            foreach ($merged_requests as $mreq) {
                                $linkedRequest = Request::model()->findByPk($mreq->id);
                                $linkedRequest->updateByPk($linkedRequest->id,
                                    ['update_by' => '1', 'pid' => $model->id]);
                                //$linkedRequest->saveOldFields();
                                $linkedRequest->replaceByValues($model,
                                    ['StartTime', 'EndTime', 'Priority', 'mfullname', 'Status', 'slabel']);
                            }
                        }
                    }
                    $child = Request::model()->countByAttributes(['pid' => $model->id]);
                    //$ch_label = '<span class="lb-danger">' . (int)$child . '</span>';
                    $ch_label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: red; vertical-align: baseline; white-space: nowrap; border: 1px solid red; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . (int)$child . '</span>';
                    Request::model()->updateByPk($model->id, ['child' => $ch_label]);
                }

                Yii::app()->user->setFlash('info',
                    Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
                $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model->id . ' named "' . $model->Name . '"';
                Yii::log($message, 'created', 'CREATED');
                $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        $this->render('create', [
            'model' => $model,
            'fields' => $fields,
            'copy' => null,
            'merged_items' => $merged_ids
        ]);
    }

    public function actionCopy($id)
    {
        $model = $this->loadModel($id);
        $fields = $model->flds;
        $model_new = new Request;
        $this->performAjaxValidation($model);
        if (isset($_POST['Request'])) {
            $model_new->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
            $model_new->attributes = $_POST['Request'];
            if ($model_new->save()) {
                Yii::app()->user->setFlash('info',
                    Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
                $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model_new->id . ' named "' . $model_new->Name . '"';
                Yii::log($message, 'created', 'CREATED');
                $this->redirect(['view', 'id' => $model_new->id]);
            }
        }
        $this->render('copy', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    /**
     * @param $user
     * @param $call
     */
    public function actionCreateFromCall($user, $call)
    {
        $model_new = new Request;
        $fields = $model_new->flds;
        $user = CUsers::model()->findByAttributes(['Username' => $user]);
        Calls::model()->updateByPk((int)$_GET['call'], ['shown' => 1]);
        $model_new->CUsers_id = $user->Username;
        $this->performAjaxValidation($model_new);
        if (isset($_POST['Request'])) {
            $model_new->attributes = $_POST['Request'];
            $model_new->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
            if ($model_new->save()) {
                Yii::app()->user->setFlash('info',
                    Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
                $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model_new->id . ' named "' . $model_new->Name . '"';
                Yii::log($message, 'created', 'CREATED');
                if (!empty($call) and !empty($_GET['call'])) {
                    $call_id = Calls::model()->findByPk((int)$_GET['call']);
                    if ($call_id !== null) {
                        Calls::model()->updateByPk($call_id->id, ['rid' => $model_new->id]);
                    }
                }
                $this->redirect(['view', 'id' => $model_new->id]);
            }
        }

        $this->render('ccal', [
            'model' => $model_new,
            'fields' => $fields,
        ]);
    }

    public function actionCreateMerge()
    {
        $model = new Request;
        $fields = new RequestFields;

        // Объединение в существующую заявку
        if (isset($_POST['merge-id']) and $_POST['merge-id'] != 0) {
            $request = Request::model()->findByPk($_POST['merge-id']);
            $merge_all = explode(',', $_POST['merge-all']);
            foreach ($merge_all as $merge_req_id) {
                if ($merge_req_id == $_POST['merge-id']) {
                    continue;
                }
                $linkedRequest = Request::model()->findByPk($merge_req_id);
                $linkedRequest->updateByPk($linkedRequest->id, ['update_by' => '1', 'pid' => $_POST['merge-id']]);
                $linkedRequest->saveOldFields();
                $linkedRequest->replaceByValues($request,
                    ['StartTime', 'EndTime', 'Priority', 'mfullname', 'Status', 'slabel']);
                $merged_requests = Request::model()->findAllByAttributes(['pid' => $merge_req_id]);
                if (isset($merged_requests)) {
                    foreach ($merged_requests as $mreq) {
                        $linkedRequest = Request::model()->findByPk($mreq->id);
                        $linkedRequest->updateByPk($linkedRequest->id,
                            ['update_by' => '1', 'pid' => $_POST['merge-id']]);
                        //$linkedRequest->saveOldFields();
                        $linkedRequest->replaceByValues($request,
                            ['StartTime', 'EndTime', 'Priority', 'mfullname', 'Status', 'slabel']);
                    }
                }
            }
            $child = Request::model()->countByAttributes(['pid' => $request->id]);
            //$ch_label = '<span class="lb-danger">' . (int)$child . '</span>';
            $ch_label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: red; vertical-align: baseline; white-space: nowrap; border: 1px solid red; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . (int)$child . '</span>';
            Request::model()->updateByPk($_POST['merge-id'],
                ['child' => $ch_label, 'lastactivity' => date("Y-m-d H:i:s")]);
            Yii::app()->user->setFlash('info',
                Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully merge requests.'));
            $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model->id . ' named "' . $model->Name . '"';
            Yii::log($message, 'created', 'CREATED');
            $this->redirect(['view', 'id' => $model->id]);
            //}
            // Объединение в новую
        } elseif (isset($_POST['merge-id']) and $_POST['merge-id'] == 0) {
            $this->redirect(['create', 'id' => $model->id, 'merged_ids' => $_POST['merge-all']]);
        } else {
            $this->performAjaxValidation($model);
            if (isset($_POST['Request'])) {
                $model->attributes = $_POST['Request'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('info',
                        Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
                    $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model->id . ' named "' . $model->Name . '"';
                    Yii::log($message, 'created', 'CREATED');
                    $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }


        if (isset($_POST['Request'])) {
            $model->attributes = $_POST['Request'];
            if ($model->save()) {
                Yii::app()->user->setFlash('info',
                    Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully create a new request.'));
                $message = 'User ' . Yii::app()->user->name . ' create new ticket #' . $model->id . ' named "' . $model->Name . '"';
                Yii::log($message, 'created', 'CREATED');
                $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $this->render('create', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'request-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionRelease()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        Request::model()->updateByPk($id, ['update_by' => null]);
    }

    /**
     * @param $id
     */
    public function actionAddsubs($id)
    {
        $afiles = null;
        if ($_POST['Comments']['comment'] !== '') {
            $fullname = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
            $model = new Comments;
            $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);
            $request = Request::model()->findByPk($id);
            $usermail = CUsers::model()->findByAttributes(['Username' => $request->CUsers_id]);
            $managermail = CUsers::model()->findByAttributes(['Username' => $request->Managers_id]);
            $uaddress = (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) ? $usermail->Email : [$usermail->Email];
            if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) {
                $maddress = isset($managermail) ? $managermail->Email : '';
            } else {
                $maddress = isset($managermail) ? [$managermail->Email] : '';
            }

            $template = Messages::model()->findByAttributes(['name' => '{comments}']);
            $subject = Email::MessageGen($template->subject, $managermail, $request);
            $subject = $subject ? $subject : '[Ticket #' . $request->id . '] ' . $request->Name . '';
            if (isset($template)) {
                //комментарии для пользователя
                $ucomments = Comments::model()->findAllByAttributes(['rid' => $id, 'show' => 0]);
                arsort($ucomments);
                $uctext = null;
                foreach ($ucomments as $comment) {
                    $uctext .= '<blockquote><b>' . $comment->author . ' [' . $comment->timestamp . '] :</b><br/>' . $comment->comment . '<br/></blockquote>';
                }
                $ucomments_list = '<blockquote>' . $uctext . '<br/>' . $request->Content . '</blockquote>';

                //комментарии для исполнителя
                $comments = Comments::model()->findAllByAttributes(['rid' => $id]);
                arsort($comments);
                $ctext = null;
                foreach ($comments as $comment) {
                    $ctext .= '<blockquote><b>' . $comment->author . ' [' . $comment->timestamp . '] :</b><br/>' . $comment->comment . '<br/></blockquote>';
                }
                $comments_list = '<blockquote>' . $ctext . '<br/>' . $request->Content . '</blockquote>';

                $ureply_text = Yii::t('message', "$template->content", [
                    '{author}' => $fullname->fullname,
                    '{date}' => date('d.m.Y H:i'),
                    '{comment}' => $_POST['Comments']['show'] !== 1 ? $_POST['Comments']['comment'] : null,
                    '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $id . '">№ ' . $id . '</a>',
                    '{comments_list}' => $ucomments_list,

                ]);

                $reply_text = Yii::t('message', "$template->content", [
                    '{author}' => $fullname->fullname,
                    '{date}' => date('d.m.Y H:i'),
                    '{comment}' => $_POST['Comments']['comment'],
                    '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $id . '">№ ' . $id . '</a>',
                    '{comments_list}' => $comments_list,

                ]);

                $umessage = $ureply_text;
                $message = $reply_text;
            } else {
                $reply_text = '<b>Добавлен новый комментарий</b><br>' . $fullname->fullname . ' [' . date('d.m.Y H:i') . ']  :<br/>' . $_POST['Comments']['comment'] . '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl . '/request/' . $id . '">№ ' . $id . '</a>';
                $ureply_text = '<b>Добавлен новый комментарий</b><br>' . $fullname->fullname . ' [' . date('d.m.Y H:i') . ']  :<br/>' . $_POST['Comments']['comment'] . '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl . '/request/' . $id . '">№ ' . $id . '</a>';

                //комментарии для пользователя
                $ucomments = Comments::model()->findAllByAttributes(['rid' => $id, 'show' => 0]);
                arsort($ucomments);
                $uctext = null;
                foreach ($ucomments as $comment) {
                    $uctext .= '<blockquote><b>' . $comment->author . ' [' . $comment->timestamp . '] :</b><br/>' . $comment->comment . '<br/></blockquote>';
                }
                $ureply_text .= '<blockquote>' . $uctext . '<br/>' . $request->Content . '</blockquote>';
                $umessage = $ureply_text;

                //комментарии для исполнителя
                $comments = Comments::model()->findAllByAttributes(['rid' => $id]);
                arsort($comments);
                $ctext = null;
                foreach ($comments as $comment) {
                    $ctext .= '<blockquote><b>' . $comment->author . ' [' . $comment->timestamp . '] :</b><br/>' . $comment->comment . '<br/></blockquote>';
                }
                $reply_text .= '<blockquote>' . $ctext . '<br/>' . $request->Content . '</blockquote>';
                $message = $reply_text;
            }

            $this->performAjaxValidation($model);
            $model->rid = $id;
            $model->timestamp = date('d.m.Y H:i:s');
            $model->author = $fullname->fullname;
            $model->channel = 'manual';
            $model->readership = $fullname->id;
            $model->comment = $_POST['Comments']['comment'];
            $model->add_temp = $_POST['Comments']['add_temp'];
            $model->files2 = $_POST['Comments']['files'];
            if (Yii::app()->user->checkAccess('systemUser')) {
                $model->show = 0;
            } else {
                $model->show = $_POST['Comments']['show'];
            }
            if (isset($_POST['Comments']['recipients']) and !empty($_POST['Comments']['recipients'])) {
                $model->recipients = implode(",", $_POST['Comments']['recipients']);
            }
            if ($model->save(false)) {
                $request->updateByPk($id, ['lastactivity' => date("Y-m-d H:i:s")]);
                $this->AddHistory(Yii::t('main-ui', 'Added new comment: ') . $_POST['Comments']['comment'], $id);
            }

            $show = $_POST['Comments']['show'];
            if (Yii::app()->params->use_rapid_msg == 1) {
                if (!isset($request->Managers_id) and isset($request->groups_id)) {
                    $groups = Groups::model()->findByPk($request->groups_id);
                    $managers = explode(",", $groups->users);
                    if (isset($managers)) {
                        foreach ($managers as $manager_id) {
                            $email = CUsers::model()->findByPk($manager_id);
                            if (Yii::app()->user->name !== $email->Username) {
                                $this->alert_send($email, $id,
                                    $request->Name . '<br/><b>Был добавлен комментарий</b>: ' . trim(strip_tags($_POST['Comments']['comment'])));
                            }
                        }
                    }
                }
                if (isset($managermail) and Yii::app()->user->checkAccess('systemUser')) {
                    $this->alert_send($managermail, $id,
                        $request->Name . '<br/><b>Был добавлен комментарий</b>: ' . trim(strip_tags($_POST['Comments']['comment'])));
                } else {
                    if ($show == 0) {
                        $this->alert_send($usermail, $id,
                            $request->Name . '<br/><b>Был добавлен комментарий</b>: ' . trim(strip_tags($_POST['Comments']['comment'])));
                    }
                }
            }

            if (!isset($request->Managers_id) and isset($request->groups_id)) {
                $groups = Groups::model()->findByPk($request->groups_id);
                if ($groups->users) {
                    $managers = explode(",", $groups->users);
                    if (isset($managers)) {
                        foreach ($managers as $manager_id) {
                            $email = CUsers::model()->findByPk($manager_id);
                            if (Yii::app()->user->name !== $email->Username) {
                                $pmessage = "[Ticket #" . $request->id . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($_POST['Comments']['comment']));
                                $url = "/request/" . $request->id;
                                if (method_exists($email,'pushMessage')) {
                                    $email->pushMessage($pmessage, $url);
                                }
                                
                            }
                        }
                    }
                }
                
            }
            if (isset($managermail) and Yii::app()->user->checkAccess('systemUser')) {
                $pmessage = "[Ticket #" . $request->id . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($_POST['Comments']['comment']));
                $url = "/request/" . $request->id;
                $managermail->pushMessage($pmessage, $url);
            } else {
                if ($show == 0 and isset($usermail)) {
                    $pmessage = "[Ticket #" . $request->id . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($_POST['Comments']['comment']));
                    $url = "/request/" . $request->id;
                    $usermail->pushMessage($pmessage, $url);
                }
            }

            //$filelist = [];
            /********************************/
            /** @var Comments $model */
            $attachments = $model->getFiles2();
            foreach ($attachments as $file) {
                $afiles[] = Yii::getPathOfAlias('webroot') . '/uploads/' . $file;
            }
            if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                $afiles = json_encode($afiles);
            }

            if (!isset($_POST['Comments']['status']) or empty($_POST['Comments']['status']) or $request->Status == $_POST['Comments']['status']) {
                $status_temp = Status::model()->findByAttributes(array('name' => $request->Status));
            } else {
                $status_temp = Status::model()->findByAttributes(array('name' => $_POST['Comments']['status']));
            }

            // Отправка уведомления пользователю незарегистрированному в системе,
            // если заявка по EMail и не стоит переключатель Скрыть от клиента.
            if ($request->channel == 'Email' and $request->CUsers_id == null and $show == 0) {
                $user_address = $request->fullname;
                if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                    Yii::app()->mailQueue->push($user_address, $subject, $umessage, $priority = 0, $from = '', $afiles,
                        $request->getmailconfig);
                } else {
                    SendMail::send($user_address, $subject, $umessage, $afiles, $request->getmailconfig);
                }
            }

            // Отправка уведомления пользователю незарегистрированному в системе,
            // если заявка c Портала и не стоит переключатель Скрыть от клиента.
            if ($request->channel == 'Portal' and $request->CUsers_id == null and $show == 0) {
                $user_address = $request->fullname;
                if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                    Yii::app()->mailQueue->push($user_address, $subject, $umessage, $priority = 0, $from = '', $afiles,
                        $request->getmailconfig);
                } else {
                    SendMail::send($user_address, $subject, $umessage, $afiles, $request->getmailconfig);
                }
            }
            // Отправка уведомления пользователю незарегистрированному в системе,
            // если заявка из Виджета и не стоит переключатель Скрыть от клиента.
            if ($request->channel == 'Widget' and $request->CUsers_id == null and $show == 0) {
                $user_address = $request->fullname;
                if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                    Yii::app()->mailQueue->push($user_address, $subject, $umessage, $priority = 0, $from = '', $afiles,
                        $request->getmailconfig);
                } else {
                    SendMail::send($user_address, $subject, $umessage, $afiles, $request->getmailconfig);
                }
            }

            // Отправка уведомления пользователю
            // Если статус не менялся шлем обычное письмо с комментарием
            if (!isset($_POST['Comments']['status']) or empty($_POST['Comments']['status']) or $request->Status == $_POST['Comments']['status']) {
                // Проверяем хочет ли пользователь получать уведомления
                if (isset($usermail) and $usermail->sendmail == 1 and $show == 0) {
                    if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                        if (Yii::app()->user->name !== $usermail->Username) {
                            Yii::app()->mailQueue->push($uaddress, $subject, $umessage, 0, null, $afiles, null);
                        }
                    } else {
                        if (Yii::app()->user->name !== $usermail->Username) {
                            SendMail::send($uaddress, $subject, $umessage, $afiles, $request->getmailconfig);
                        }
                    }
                }
            } else {
                // Если статус изменился
                // Проверка у текущего статуса заявки уведомлять ли пользователя
                // Если не уведомлять шлем обычное письмо
                if ($status_temp->notify_user !== 1) {
                    // Проверяем хочет ли пользователь получать уведомления
                    if (isset($usermail) and $usermail->sendmail == 1 and $show == 0) {
                        if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                            if (Yii::app()->user->name !== $usermail->Username) {
                                Yii::app()->mailQueue->push($uaddress, $subject, $umessage, $priority = 0, $from = '',
                                    $afiles, $request->getmailconfig);
                            }
                        } else {
                            if (Yii::app()->user->name !== $usermail->Username) {
                                SendMail::send($uaddress, $subject, $umessage, $afiles, $request->getmailconfig);
                            }
                        }
                    }
                }
            }


            // Отправка уведомления исполнителю
            // Если статус не менялся шлем обычное письмо с комментарием
            if (!isset($_POST['Comments']['status']) or empty($_POST['Comments']['status']) or $request->Status == $_POST['Comments']['status']) {
                if (!isset($request->Managers_id) and isset($request->groups_id)) {
                    $groups = Groups::model()->findByPk($request->groups_id);
                    if ($groups && $groups->send && $groups->email) { //если это группа и у группы уведомления на мыло группы
                        $manager_address = $groups->email;
                        if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                            Yii::app()->mailQueue->push($manager_address, $subject, $message, $priority = 0,
                                $from = '', $afiles, $request->getmailconfig);
                        } else {
                            SendMail::send($manager_address, $subject, $message, $afiles, $request->getmailconfig);
                        }
                    } else { //если группа, но уведомления каждому исполнителю
                        $managers = explode(",", $groups->users);
                        if (isset($managers)) {
                            foreach ($managers as $manager_id) {
                                $email = CUsers::model()->findByPk($manager_id);
                                if (Yii::app()->user->name !== $email->Username and $email->sendmail == 1) {
                                    if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                                        if (Yii::app()->user->name !== $email->Username) {
                                            Yii::app()->mailQueue->push($email->Email, $subject, $message,
                                                $priority = 0,
                                                $from = '', $afiles, $request->getmailconfig);
                                        }
                                    } else {
                                        if (Yii::app()->user->name !== $email->Username) {
                                            SendMail::send($email->Email, $subject, $message, $afiles,
                                                $request->getmailconfig);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // Проверяем хочет ли пользователь получать уведомления
                if (isset($managermail) and $managermail->sendmail == 1) {
                    if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                        if (Yii::app()->user->name !== $managermail->Username) {
                            Yii::app()->mailQueue->push($maddress, $subject, $message, $priority = 0, $from = '',
                                $afiles,
                                $request->getmailconfig);
                        }
                    } else {
                        if (Yii::app()->user->name !== $managermail->Username) {
                            SendMail::send($maddress, $subject, $message, $afiles, $request->getmailconfig);
                        }
                    }
                    //Email::Pushc($managermail->push_id, $subject, $message);
                }
            } else {
                // Если статус изменился
                // Проверка у текущего статуса заявки уведомлять ли исполнителя
                // Если не уведомлять шлем обычное письмо
                if ($status_temp->notify_manager != 1) {
                    // Проверяем хочет ли пользователь получать уведомления
                    if (isset($managermail) and $managermail->sendmail == 1) {
                        if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                            Yii::app()->mailQueue->push($maddress, $subject, $message, $priority = 0, $from = '',
                                $afiles, $request->getmailconfig);
                        } else {
                            SendMail::send($maddress, $subject, $message, $afiles, $request->getmailconfig);
                        }
                        //Email::Pushc($managermail->push_id, $subject, $message);
                    }
                }
            }


            // Отправка уведомления наблюдателям
            $watchers = explode(",", $request->watchers);
            if ($watchers[0] !== '') {
                // Если статус не менялся шлем обычное письмо с коментарием
                if (!isset($_POST['Comments']['status']) or empty($_POST['Comments']['status']) or $request->Status == $_POST['Comments']['status']) {
                    foreach ($watchers as $watcher) {
                        $email = CUsers::model()->findByAttributes(['fullname' => $watcher]);
                        // Проверяем хочет ли пользователь получать уведомления
                        if ($email->sendmail == 1) {
                            if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                                Yii::app()->mailQueue->push($email->Email, $subject, $message, $priority = 0,
                                    $from = '', $afiles, $request->getmailconfig);
                            } else {
                                SendMail::send($email->Email, $subject, $message, $afiles, $request->getmailconfig);
                            }
                        }
                    }
                } else {
                    // Если статус изменился
                    // Проверка у текущего статуса заявки уведомлять ли наблюдателей
                    // Если не уведомлять шлем обычное письмо
                    if ($status_temp->notify_group != 1) {
                        foreach ($watchers as $watcher) {
                            $email = CUsers::model()->findByAttributes(['fullname' => $watcher]);
                            // Проверяем хочет ли пользователь получать уведомления
                            if ($email->sendmail == 1) {
                                if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                                    Yii::app()->mailQueue->push($email->Email, $subject, $message, $priority = 0,
                                        $from = '', $afiles, $request->getmailconfig);
                                } else {
                                    SendMail::send($email->Email, $subject, $message, $afiles, $request->getmailconfig);
                                }
                            }
                        }
                    }
                }
            }

            // --------------------------------------------------------------------------------------
            // Смена статуса из формы комментария
            if (isset($_POST['Comments']['status']) and !empty($_POST['Comments']['status']) and $request->Status !== $_POST['Comments']['status']) {
                if (isset($_POST['Request']['pendingTime']) and !empty($_POST['Request']['pendingTime'])) {
                    Request::model()->updateByPk($request->id, [
                        'EndTime' => $_POST['Request']['pendingTime'] . ' ' . $_POST['Request']['pTime'],
                        'timestampEnd' => Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss',
                            $_POST['Request']['pendingTime'] . ' ' . $_POST['Request']['pTime'] . ':00')
                    ]);
                    $this->AddHistory(Yii::t('main-ui',
                            'End time is set to: ') . '<b>' . $_POST['Request']['pendingTime'] . ' ' . $_POST['Request']['pTime'] . '</b>',
                        $request->id);
                }
                // TODO: Костыль потому что в моделе используются глобальные массивы
                if (!Yii::app()->user->checkAccess('systemUser')) {
                    $mngr = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
                }
                if (isset($model->gfullname)) {
                    $_POST['Request']['gfullname'] = null;
                }

                if ($status_temp->close == 2 or $status_temp->close == 3) {
                    if (!Yii::app()->user->checkAccess('systemUser') and Yii::app()->user->name !== $request->CUsers_id) {
                        $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                        $_POST['Request']['mfullname'] = $mngr->fullname;
                    }
                }

                $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                $_POST['Request']['Status'] = $_POST['Comments']['status'];
                $_POST['Request']['service_id'] = $request->service_id;
                $_POST['Request']['Priority'] = $request->Priority;
                $_POST['Request']['Content'] = $request->Content;
                $request->attributes = $_POST['Request'];

                $request->Comment = null;
                $request->save(false);

                $pRequests = Request::model()->findAllByAttributes(['pid' => $request->id]);
                if (!empty($pRequests)) {
                    foreach ($pRequests as $pRequest) {
                        if (isset($_POST['Request']['pendingTime']) and !empty($_POST['Request']['pendingTime'])) {
                            Request::model()->updateByPk($pRequest->id, [
                                'EndTime' => $_POST['Request']['pendingTime'] . ' ' . $_POST['Request']['pTime'],
                                'timestampEnd' => Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss',
                                    $_POST['Request']['pendingTime'] . ' ' . $_POST['Request']['pTime'] . ':00')
                            ]);
                            $this->AddHistory(Yii::t('main-ui',
                                    'End time is set to: ') . '<b>' . $_POST['Request']['pendingTime'] . ' ' . $_POST['Request']['pTime'] . '</b>',
                                $pRequest->id);
                        }
                        // TODO: Костыль потому что в моделе используются глобальные массивы
                        if (isset($model->gfullname)) {
                            $_POST['Request']['gfullname'] = null;
                        }
                        if ($status_temp->close == 2 or $status_temp->close == 3) {
                            if (!Yii::app()->user->checkAccess('systemUser') and Yii::app()->user->name !== $request->CUsers_id) {
                                $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                                $_POST['Request']['mfullname'] = $mngr->fullname;
                            }
                        }
                        $_POST['Request']['CUsers_id'] = $pRequest->CUsers_id;
                        $_POST['Request']['Status'] = $_POST['Comments']['status'];
                        $_POST['Request']['service_id'] = $pRequest->service_id;
                        $_POST['Request']['Priority'] = $pRequest->Priority;
                        $_POST['Request']['Content'] = $pRequest->Content;
                        $pRequest->attributes = $_POST['Request'];
                        $pRequest->pid = $request->id;
                        $pRequest->save(false);
                    }
                }
            }

            // --------------------------------------------------------------------------------------
            // Если включен параметр автоматически принимать в работу при первом комментарии
            if (isset(Yii::app()->params['autoaccept']) and (Yii::app()->params['autoaccept'] == 1) and ($request->Status == $_POST['Comments']['status']) and (count($request->comms) == 1) and Yii::app()->user->checkAccess('systemManager')) {
                $mngr = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
                $gstatus = Status::model()->findByAttributes(['close' => 2]);
                $_POST['Request'] = null;
                if (isset($model->gfullname)) {
                    $_POST['Request']['gfullname'] = null;
                }
                if (Yii::app()->user->name !== $request->CUsers_id) {
                    $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                    $_POST['Request']['mfullname'] = $mngr->fullname;
                }
                $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                $_POST['Request']['Status'] = $gstatus->name;
                $_POST['Request']['slabel'] = $gstatus->label;
                $_POST['Request']['closed'] = 1;
                $_POST['Request']['service_id'] = $request->service_id;
                $_POST['Request']['Priority'] = $request->Priority;
                $_POST['Request']['Content'] = $request->Content;
                $request->attributes = $_POST['Request'];

                $request->Comment = null;
                $request->save();

                $pRequests = Request::model()->findAllByAttributes(['pid' => $request->id]);
                if (!empty($pRequests)) {
                    foreach ($pRequests as $pRequest) {
                        // TODO: Костыль потому что в моделе используются глобальные массивы
                        if (isset($model->gfullname)) {
                            $_POST['Request']['gfullname'] = null;
                        }
                        if (Yii::app()->user->name !== $request->CUsers_id) {
                            $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                            $_POST['Request']['mfullname'] = $mngr->fullname;
                        }
                        $_POST['Request']['CUsers_id'] = $pRequest->CUsers_id;
                        $_POST['Request']['Status'] = $gstatus->name;
                        $_POST['Request']['slabel'] = $gstatus->label;
                        $_POST['Request']['closed'] = 1;
                        $_POST['Request']['service_id'] = $pRequest->service_id;
                        $_POST['Request']['Priority'] = $pRequest->Priority;
                        $_POST['Request']['Content'] = $pRequest->Content;
                        $pRequest->attributes = $_POST['Request'];
                        $pRequest->pid = $request->id;
                        $pRequest->save();
                    }
                }
            }

            // Рассылка дополнительным получателям если таковые есть
            if (
                isset($_POST['Comments']['recipients']) and !empty($_POST['Comments']['recipients'])
                //and
                //(!isset($_POST['Comments']['status']) or empty($_POST['Comments']['status']))
            ) {
                foreach ($_POST['Comments']['recipients'] as $recipientName) {
                    $recipient = CUsers::model()->findByAttributes(['fullname' => $recipientName]);
                    if (!empty($recipient)) {
                        if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                            Yii::app()->mailQueue->push($recipient->Email, $subject, $message, $priority = 0,
                                $from = '', $afiles, null);
                        } else {
                            SendMail::send($recipient->Email, $subject, $message, $afiles, $request->getmailconfig);
                        }
                    }
                }
            }

            if (isset($_POST['rating']) && !empty($_POST['rating'])) {
                Request::model()->updateByPk($id, ['rating' => $_POST['rating']]);
                $this->AddHistory(Yii::t('main-ui', 'Request rated to: ') . '<b>' . $_POST['rating'] . '</b>', $id);
            }
        } else {
            Yii::app()->user->setFlash('warning',
                Yii::t('main-ui', '<strong>Warning!</strong> You`re trying to add an empty comment.'));
            if (isset($_POST['rating']) && !empty($_POST['rating'])) {
                Request::model()->updateByPk($id, ['rating' => $_POST['rating']]);
                $this->AddHistory(Yii::t('main-ui', 'Request rated to: ') . '<b>' . $_POST['rating'] . '</b>', $id);
            }
            $this->redirect(array('view', 'id' => $id), true);
        }

        if ($_GET['nonredirect']) {
            return;
        }

        if (isset($_POST['event']) && !empty($_POST['event']) && isset($_POST['id']) && !empty($_POST['id'])) {
            if (is_numeric($_POST['reaction'])) {
                $this->redirect([$_POST['event'], 'id' => $_POST['id'], 'reaction' => $_POST['reaction']]);
            }
            $this->redirect([$_POST['event'], 'id' => $_POST['id']]);
        }

        $this->redirect(array('view', 'id' => $id));
    }

    public function Alert_send($user, $id, $message)
    {
        $alert = new Alerts();
        $alert->user = $user->Username;
        $alert->name = $id;
        $alert->message = $message;
        $alert->save();
    }

    public function actionDeletesub($id)
    {
        $model = Comments::model()->findByPk($id);

        $files = explode(",", $model->files);
        foreach ((array)$files as $file) {
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id . '/' . $file;
            if (is_file($documentPath)) {
                unlink($documentPath);
            }
        }

        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id)) {
            rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->r->id . '/' . $model->id);
        }

        $this->AddHistory(Yii::t('main-ui', 'Deleted comment: ') . '<b>' . $model->comment . '</b>', $model->rid);
        $model->delete();
    }

    public function actionUpdate($id)
    {
        Yii::app()->session->remove('fields');
        $model = $this->loadModel($id);
        $fields = $model->flds;
        if (Yii::app()->user->checkAccess('systemManager') and Yii::app()->params['monopoly'] == 1 and $model->CUsers_id !== Yii::app()->user->name) {
            if (($model->Managers_id == null) and ($model->update_by == null)) {
                Request::model()->updateByPk($id, ['update_by' => Yii::app()->user->name]);
                Yii::app()->user->setFlash('danger', Yii::t('main-ui',
                    'You edit the request in exclusive mode, other users do not have access to the request until you save your changes!'));
            }
        }
        //$subs = $model->comms;
        $subs2 = new Comments;
        if (Yii::app()->user->checkAccess('systemUser')) {
            $subs = Comments::model()->findAllByAttributes(['rid' => $id, 'show' => 0]);
        } else {
            $subs = $model->comms;
        }
        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model);
        $path = Yii::getPathOfAlias('webroot') . '/media/' . $id;
        if (is_dir($path)) {
            $files = $this->myscandir($path);
        }
        if (isset($_POST['Request'])) {
            $model->attributes = $_POST['Request'];
            $model->Content = $_POST['Request']['Content'] ? $_POST['Request']['Content'] : Yii::t('main-ui',
                'Not set');
            if ($model->validate()) {
                if ($model->save()) {
                    $message = 'User ' . Yii::app()->user->name . ' updated ticket #' . $model->id . ' named "' . $model->Name . '"';
                    $pRequests = Request::model()->findAllByAttributes(['pid' => $model->id]);
                    if (!empty($pRequests)) {
                        foreach ($pRequests as $pRequest) {
                            if (isset($_POST['Request']['Status']) and !empty($_POST['Request']['Status'])) {
                                $pRequest->Status = $_POST['Request']['Status'];
                            }

                            if (isset($_POST['Request']['Priority']) and !empty($_POST['Request']['Priority'])) {
                                $pRequest->Priority = $_POST['Request']['Priority'];
                            }

                            $pRequest->pid = $model->id;
                            $pRequest->save();
                        }
                    }
                    Yii::log($message, 'updated', 'UPDATED');
                    Request::model()->updateByPk($id, array('update_by' => null));
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
        }
        if ($model->update_by == null or Yii::app()->user->name == $model->update_by) {
            $update_path = 'update';
        } else {
            $update_path = 'update_fail';
        }
        $this->render($update_path, [
            'model' => $model,
            'subs' => $subs,
            'subs2' => $subs2,
            'files' => $files ? $files : null,
            'fields' => $fields,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = $_GET['id'];
        }
        $model = $this->loadModel($id);
        print_r($model);

        //----
        $allFiles = [];
        $result = [];
        preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->Content, $result);
        $result2 = [];
        preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->Content, $result2);
        $result3 = [];
        preg_match_all('#src="/uploads/([^"]+)"#i', $model->Content, $result3);
        $result4 = [];
        preg_match_all('#href="/uploads/([^"]+)"#i', $model->Content, $result4);
        if (!empty($result[0][0])) {
            $allFiles = array_merge($allFiles, $result[1]);
        }
        if (!empty($result2[0][0])) {
            $allFiles = array_merge($allFiles, $result2[1]);
        }
        if (!empty($result3[0][0])) {
            $allFiles = array_merge($allFiles, $result3[1]);
        }
        if (!empty($result4[0][0])) {
            $allFiles = array_merge($allFiles, $result4[1]);
        }
        if (!empty($allFiles)) {
            foreach ($allFiles as $file) {
                $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                if (is_file($documentPath)) {
                    unlink($documentPath);
                }
                $documentPath = Yii::getPathOfAlias('webroot') . '/uploads/' . $file;
                if (is_file($documentPath)) {
                    unlink($documentPath);
                }
            }
        }

        if (!empty($model->comms)) {
            foreach ($model->comms as $comm) {
                /** @var Comments */
                $comm->delete();
            }
        }

        //----

        $submodel = Request::model()->findAllByAttributes(['pid' => $id]);
        if (isset($submodel)) {
            foreach ($submodel as $delitem) {
                if (!empty($delitem->comms)) {
                    foreach ($delitem->comms as $comm) {
                        /** @var Comments */
                        $comm->delete();
                    }
                }
                //----
                $allFiles = [];
                $result = [];
                preg_match_all('#src="/media/redactor/([^"]+)"#i', $delitem->Content, $result);
                $result2 = [];
                preg_match_all('#href="/media/redactor/([^"]+)"#i', $delitem->Content, $result2);
                $result3 = [];
                preg_match_all('#src="/uploads/([^"]+)"#i', $delitem->Content, $result3);
                $result4 = [];
                preg_match_all('#href="/uploads/([^"]+)"#i', $delitem->Content, $result4);
                if (!empty($result[0][0])) {
                    $allFiles = array_merge($allFiles, $result[1]);
                }
                if (!empty($result2[0][0])) {
                    $allFiles = array_merge($allFiles, $result2[1]);
                }
                if (!empty($result3[0][0])) {
                    $allFiles = array_merge($allFiles, $result3[1]);
                }
                if (!empty($result4[0][0])) {
                    $allFiles = array_merge($allFiles, $result4[1]);
                }
                if (!empty($allFiles)) {
                    foreach ($allFiles as $file) {
                        $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                        if (is_file($documentPath)) {
                            unlink($documentPath);
                        }
                        $documentPath = Yii::getPathOfAlias('webroot') . '/uploads/' . $file;
                        if (is_file($documentPath)) {
                            unlink($documentPath);
                        }
                    }
                }
                //----
                $files = explode(",", $delitem->image);
                foreach ($files as $file) {
                    $os_type = DetectOS::getOS();
                    $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
                    $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->id . '/' . $file;
                    if (is_file($documentPath)) {
                        unlink($documentPath);
                    }
                }
                if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id)) {
                    rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id);
                }
                $this->loadModel($delitem->id)->delete();
                $message = 'User ' . Yii::app()->user->name . ' delete ticket #' . $delitem->id . ' named "' . $delitem->Name . '"';
                Yii::log($message, 'deleted', 'DELETED');
            }
        }
        $files = explode(",", $model->image);
        foreach ($files as $file) {
            $os_type = DetectOS::getOS();
            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
            $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->id . '/' . $file;
            if (is_file($documentPath)) {
                unlink($documentPath);
            }
        }
        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id)) {
            rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id);
        }

        if (Yii::app()->request->isPostRequest or Yii::app()->request->getIsAjaxRequest()) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            $message = 'User ' . Yii::app()->user->name . ' delete ticket #' . $model->id . ' named "' . $model->Name . '"';
            Yii::log($message, 'deleted', 'DELETED');

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
            }
        } else {
            throw new CHttpException(400, 'УПС! Неверный запрос, что-то вы делаете не так.');
        }
    }

    public function actionDeleteFile($id, $file)
    {
        //$filename = iconv("UTF-8", "CP1251", $file); //in Windows systems
        $os_type = DetectOS::getOS();
        $filename = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file; //in Unix systems
        $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $id . '/' . $filename;
        if (is_file($documentPath)) {
            unlink($documentPath);
        }
        $path = Yii::getPathOfAlias('webroot') . '/media/' . $id;
        $files = $this->myscandir($path);
        foreach ($files as $item) {
            if (!is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $id . '/' . $item)) {
                $files2[] = $item;
            }
        }
        $value = implode(",", $files2);
        $value = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $value) : $value;
        $this->AddHistory(Yii::t('main-ui', 'Deleted file: ') . '<b>' . $file . '</b>', $id);
        Request::model()->updateByPk($id, ['image' => $value]);
    }

    public function myscandir($dir, $sort = 0)
    {
        $list = scandir($dir, $sort);
        if (!$list) {
            return false;
        }
        if ($sort == 0) {
            unset($list[0], $list[1]);
        } else {
            unset($list[count($list) - 1], $list[count($list) - 1]);
        }
        return $list;
    }

    public function actionBatchDelete()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $checkedIDs = $_GET['checked'];
            foreach ($checkedIDs as $id) {
                echo $id;
                $model = $this->loadModel($id);

                if (!empty($model->comms)) {
                    foreach ($model->comms as $comm) {
                        /** @var Comments */
                        $comm->delete();
                    }
                }

                //----
                $allFiles = [];
                $result = [];
                preg_match_all('#src="/media/redactor/([^"]+)"#i', $model->Content, $result);
                $result2 = [];
                preg_match_all('#href="/media/redactor/([^"]+)"#i', $model->Content, $result2);
                $result3 = [];
                preg_match_all('#src="/uploads/([^"]+)"#i', $model->Content, $result3);
                $result4 = [];
                preg_match_all('#href="/uploads/([^"]+)"#i', $model->Content, $result4);
                if (!empty($result[0][0])) {
                    $allFiles = array_merge($allFiles, $result[1]);
                }
                if (!empty($result2[0][0])) {
                    $allFiles = array_merge($allFiles, $result2[1]);
                }
                if (!empty($result3[0][0])) {
                    $allFiles = array_merge($allFiles, $result3[1]);
                }
                if (!empty($result4[0][0])) {
                    $allFiles = array_merge($allFiles, $result4[1]);
                }
                if (!empty($allFiles)) {
                    foreach ($allFiles as $file) {
                        $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                        if (is_file($documentPath)) {
                            unlink($documentPath);
                        }
                        $documentPath = Yii::getPathOfAlias('webroot') . '/uploads/' . $file;
                        if (is_file($documentPath)) {
                            unlink($documentPath);
                        }
                    }
                }
                //----

                $submodel = Request::model()->findAllByAttributes(['pid' => $id]);
                if (isset($submodel)) {
                    foreach ($submodel as $delitem) {
                        if (!empty($delitem->comms)) {
                            foreach ($delitem->comms as $comm) {
                                /** @var Comments */
                                $comm->delete();
                            }
                        }
                        //----
                        $allFiles = [];
                        $result = [];
                        preg_match_all('#src="/media/redactor/([^"]+)"#i', $delitem->Content, $result);
                        $result2 = [];
                        preg_match_all('#href="/media/redactor/([^"]+)"#i', $delitem->Content, $result2);
                        if (!empty($result[0][0])) {
                            $allFiles = array_merge($allFiles, $result[1]);
                        }
                        if (!empty($result2[0][0])) {
                            $allFiles = array_merge($allFiles, $result2[1]);
                        }
                        if (!empty($allFiles)) {
                            foreach ($allFiles as $file) {
                                $documentPath = Yii::getPathOfAlias('webroot') . '/media/redactor/' . $file;
                                if (is_file($documentPath)) {
                                    unlink($documentPath);
                                }
                            }
                        }
                        //----

                        $files = explode(",", $delitem->image);
                        foreach ($files as $file) {
                            $os_type = DetectOS::getOS();
                            $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
                            $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->id . '/' . $file;
                            if (is_file($documentPath)) {
                                unlink($documentPath);
                            }
                        }
                        if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id)) {
                            rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id);
                        }
                        $this->loadModel($delitem->id)->delete();
                        $message = 'User ' . Yii::app()->user->name . ' delete ticket #' . $delitem->id . ' named "' . $delitem->Name . '"';
                        Yii::log($message, 'deleted', 'DELETED');
                    }
                }
                $files = explode(",", $model->image);
                foreach ($files as $file) {
                    $os_type = DetectOS::getOS();
                    $file = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
                    $documentPath = Yii::getPathOfAlias('webroot') . '/media/' . $model->id . '/' . $file;
                    if (is_file($documentPath)) {
                        unlink($documentPath);
                    }
                }
                if (is_dir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id)) {
                    rmdir(Yii::getPathOfAlias('webroot') . '/media/' . $model->id);
                }
                $this->loadModel($id)->delete();
                $message = 'User ' . Yii::app()->user->name . ' delete ticket #' . $model->id . ' named "' . $model->Name . '"';
                Yii::log($message, 'deleted', 'DELETED');
            }
        }
    }

    public function actionBatchUpdateWithComment()
    {
        $r_ids = explode(',', $_POST['r_ids']);
        $post = $_POST;
        $_GET['nonredirect'] = true;
        foreach ($r_ids as $r_id) {
            $this->actionAddsubs($r_id);
            if (isset($_POST['rating'])) {
                Request::model()->updateByPk($r_id, ['rating' => $_POST['rating']]);
                $this->AddHistory(Yii::t('main-ui', 'Request rated to: ') . '<b>' . $_POST['rating'] . '</b>', $r_id);
            }
            unset($_POST);
            $_POST = $post;
        }
        $_GET['checked'] = $r_ids;
        $this->actionBatchUpdate();
    }

    public function actionBatchUpdate()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $status = Status::model()->findByAttributes(['close' => '3']);
            $checkedIDs = $_GET['checked'];
            foreach ($checkedIDs as $id) {
                $model = Request::model()->findByPk($id);
                $message = 'User ' . Yii::app()->user->name . ' updated ticket #' . $id . ' named "' . $model->Name . '"';
                Yii::log($message, 'updated', 'UPDATED');
                $mngr = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
                if (isset($model->gfullname)) {
                    $_POST['Request']['gfullname'] = null;
                }
                if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                    $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                    $_POST['Request']['mfullname'] = $mngr->fullname;
                }
                $_POST['Request']['CUsers_id'] = $model->CUsers_id;
                $_POST['Request']['Status'] = $status->name;
                $_POST['Request']['slabel'] = $status->label;
                $_POST['Request']['service_id'] = $model->service_id;
                $_POST['Request']['Priority'] = $model->Priority;
                $_POST['Request']['Content'] = $model->Content;
                $_POST['Request']['timestampClose'] = null;
                $model->attributes = $_POST['Request'];

                $model->Comment = null;
                $model->save();

                $pRequests = Request::model()->findAllByAttributes(['pid' => $id]);
                if (!empty($pRequests)) {
                    foreach ($pRequests as $pRequest) {
                        // TODO: Костыль потому что в моделе используются глобальные массивы
                        if (isset($model->gfullname)) {
                            $_POST['Request']['gfullname'] = null;
                        }
                        if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                            $_POST['Request']['Managers_id'] = Yii::app()->user->name;
                            $_POST['Request']['mfullname'] = $mngr->fullname;
                        }
                        $_POST['Request']['CUsers_id'] = $pRequest->CUsers_id;
                        $_POST['Request']['Status'] = $status->name;
                        $_POST['Request']['slabel'] = $status->label;
                        $_POST['Request']['Priority'] = $pRequest->Priority;
                        $_POST['Request']['Content'] = $pRequest->Content;
                        $_POST['Request']['timestampClose'] = null;
                        $pRequest->attributes = $_POST['Request'];
                        $pRequest->pid = $model->id;
                        $pRequest->save();
                    }
                }
            }
        }
    }

    public function actionMerge($id, $all)
    {
        $model = new Request();
        if (!empty($id) and $id != 0) {
            $modelM = $this->loadModel($id);
            $model->attributes = $modelM->attributes;
            $fields = $modelM->flds;
        } else {
            $fields = $model->flds;
        }

        $model->Content = CHtml::decode($model->Content);
        $this->renderpartial('_merge_form', [
            'model' => $model,
            'fields' => $fields,
        ], false, true);
    }

    public function actionMergeList()
    {
        if (isset($_GET['checked'])) {
            $checkedIDs = $_GET['checked'];
            $model = [];
            $mergeAll = [];
            $list = [0 => 'Объединить в новую заявку'];
            foreach ($checkedIDs as $id) {
                $rq = Request::model()->findByPk($id);
                $model[] = $rq;
                $mergeAll[] = $id;
                $list[$rq->id] = $rq->Name;
                //Request::model()->updateByPk($id, array('update_by'=>1));
            }
            //echo CHtml::activeLabelEx($model, $mod);

            echo CHtml::DropDownList('merge-id', 'merge-list', $list, ['class' => 'span12', 'id' => 'merge-list']);
            echo CHtml::hiddenField('merge-all', implode(',', $mergeAll));
        }
    }

    public function actionSetStatusWithComment()
    {
        $r_ids = explode(',', $_POST['r_ids']);
        $post = $_POST;
        $_GET['nonredirect'] = true;
        foreach ($r_ids as $r_id) {
            $this->actionAddsubs($r_id);
            if (isset($_POST['rating'])) {
                Request::model()->updateByPk($r_id, ['rating' => $_POST['rating']]);
                $this->AddHistory(Yii::t('main-ui', 'Request rated to: ') . '<b>' . $_POST['rating'] . '</b>', $r_id);
            }
            unset($_POST);
            $_POST = $post;
        }
        $_GET['checked'] = $r_ids;
        $_GET['status'] = $_POST['status'];
        $this->actionSetStatus();
    }

    public function actionSetStatus()
    {
        if (isset($_GET['checked']) and (isset($_GET['status']) or isset($_POST['status']))) {
            foreach ($_GET['checked'] as $requestId) {
                $request = Request::model()->findByPk($requestId);

                // TODO: Костыль потому что в моделе используются глобальные массивы
                if (isset($request->gfullname)) {
                    $_POST['Request']['gfullname'] = null;
                }
                //$_POST['Request']['Managers_id'] = $_GET['user'];
                //$_POST['Request']['mfullname'] = $mngr->fullname;
                $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                $_POST['Request']['Status'] = isset($_GET['status']) ? $_GET['status'] : $_POST['status'];
                $_POST['Request']['service_id'] = $request->service_id;
                $_POST['Request']['Priority'] = $request->Priority;
                $_POST['Request']['Content'] = $request->Content;

                if ($request->service_rl) {
                    if ($request->service_rl->matchings) {
                        $matchings = explode(',', $request->service_rl->matchings);
                        if ($matchings) {
                            $_POST['Request']['matchings'] = $matchings;
                        }
                    }
                }

                $request->attributes = $_POST['Request'];

                if ($request->save()) {
                    echo 'Ok' . $requestId . '<br>';

                    $pRequests = Request::model()->findAllByAttributes(['pid' => $request->id]);
                    if (!empty($pRequests)) {
                        foreach ($pRequests as $pRequest) {
                            if (isset($_POST['Request']['Status']) and !empty($_POST['Request']['Status'])) {
                                $pRequest->Status = $_POST['Request']['Status'];
                            }
                            $pRequest->pid = $request->id;
                            $pRequest->save();
                        }
                    }

                    unset($_POST);
                }
            }
        }
    }

    public function actionSort()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $items = json_encode($_POST['lists']);
            $items = explode('&', $items);
            $i = 0;
            foreach ($items as $item) {
                $i = $i + 1;
                $item_id = preg_replace('/[^0-9]/', '', $item);
                Status::model()->updateByPk($item_id, ['sort_id' => $i]);
            }
            unset($_POST);
        }
    }

    public function actionCheckUpdates()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $last_id = (int)$_GET['id'];
            $last_cid = (int)$_GET['cid'];
            $new_tickets = Yii::app()->db->createCommand('SELECT * FROM `request` `t` WHERE `t`.`id`>' . $last_id . ' ORDER BY `id` ASC')->query();
            $new_comments = Yii::app()->db->createCommand('SELECT * FROM `comments` `t` WHERE `t`.`id`>' . $last_cid . ' ORDER BY `id` ASC')->query();
            if (count($new_tickets)) {
                echo('new');
            }
            if (count($new_comments)) {
                echo('new');
            }
        }
    }

    public function actionMove()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $items = $_POST['items'];
            $status_id = $_POST['tasklist_id'];
            $add = $_POST['add_or_remove'];
            if ($add == 'add') {
                $items = explode('&', $items);
                $i = count($items);
                foreach ($items as $item) {
                    if (count($items) > 1 and strcasecmp($item, 'input-list[]=') !== 1 and !empty($item)) {
                        $i = $i - 1;
                        $item = preg_replace('/[^0-9]/', '', json_encode($item));
                        $status = Status::model()->findByPk($status_id);
                        $role = Roles::model()->findByAttributes(array('value' => strtolower(Yii::app()->user->role)));
                        $request = Request::model()->findByPk($item);
                        $statuses = $role->status_rl;
                        $stat_bool = false;
                        foreach ($statuses as $item2) {
                            if ((int)$status->id == (int)$item2->id) {
                                $stat_bool = 1;
                            }
                        }
                        if (isset($status) and !empty($status) and ($request->Status !== $status->name) and ($stat_bool == 1)) {
                            // TODO: Костыль потому что в моделе используются глобальные массивы
                            if (isset($request->gfullname)) {
                                $_POST['Request']['gfullname'] = null;
                            }
                            $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                            $_POST['Request']['Status'] = $status->name;
                            $_POST['Request']['service_id'] = $request->service_id;
                            $_POST['Request']['Priority'] = $request->Priority;
                            $_POST['Request']['Content'] = $request->Content;
                            $_POST['Request']['sort_id'] = $i;
                            $request->attributes = $_POST['Request'];

                            if ($request->save(false)) {
                                $pRequests = Request::model()->findAllByAttributes(['pid' => $request->id]);
                                if (!empty($pRequests)) {
                                    foreach ($pRequests as $pRequest) {
                                        if (isset($_POST['Request']['Status']) and !empty($_POST['Request']['Status'])) {
                                            $pRequest->Status = $_POST['Request']['Status'];
                                        }
                                        $pRequest->pid = $request->id;
                                        $pRequest->save(false);
                                    }
                                }

                                unset($_POST);
                            }
                        } elseif ($stat_bool !== 1) {
                            echo("false");
                        }
                    } elseif (strcasecmp($item, 'input-list[]=') !== 1 and !empty($item)) {
                        $item = preg_replace('/[^0-9]/', '', json_encode($item));
                        $status = Status::model()->findByPk($status_id);
                        $role = Roles::model()->findByAttributes(array('value' => strtolower(Yii::app()->user->role)));
                        $request = Request::model()->findByPk($item);
                        $statuses = $role->status_rl;
                        $stat_bool = false;
                        foreach ($statuses as $item3) {
                            if ($status->id == $item3->id) {
                                $stat_bool = 1;
                            }
                        }
                        if (isset($status) and !empty($status) and ($request->Status !== $status->name) and ($stat_bool == 1)) {
                            // TODO: Костыль потому что в моделе используются глобальные массивы
                            if (isset($request->gfullname)) {
                                $_POST['Request']['gfullname'] = null;
                            }
                            $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                            $_POST['Request']['Status'] = $status->name;
                            $_POST['Request']['service_id'] = $request->service_id;
                            $_POST['Request']['Priority'] = $request->Priority;
                            $_POST['Request']['Content'] = $request->Content;
                            $request->attributes = $_POST['Request'];

                            if ($request->save(false)) {
                                $pRequests = Request::model()->findAllByAttributes(['pid' => $request->id]);
                                if (!empty($pRequests)) {
                                    foreach ($pRequests as $pRequest) {
                                        if (isset($_POST['Request']['Status']) and !empty($_POST['Request']['Status'])) {
                                            $pRequest->Status = $_POST['Request']['Status'];
                                        }
                                        $pRequest->pid = $request->id;
                                        $pRequest->save(false);
                                    }
                                }

                                unset($_POST);
                            }
                        } elseif ($stat_bool !== 1) {
                            echo("false");
                        }
                    }
                }
            } else {
                $items = explode('&', $items);
                $i = count($items);
                foreach ($items as $item) {
                    if (count($items) > 1 and strcasecmp($item, 'input-list[]=') !== 1 and !empty($item)) {
                        $i = $i - 1;
                        $item = preg_replace('/[^0-9]/', '', json_encode($item));
                        Request::model()->updateByPk($item, ['sort_id' => $i]);
                    }
                }
            }
            unset($_POST);
        }
    }

    public function actionSetStatusOne()
    {
        if (isset($_GET['checked']) and isset($_GET['status'])) {
            $stat_bool = false;
            $requestId = $_GET['checked'];
            $request = Request::model()->findByPk($requestId);
            $status = Status::model()->findByPk($_GET['status']);
            $role = Roles::model()->findByAttributes(['value' => strtolower(Yii::app()->user->role)]);
            $statuses = $role->status_rl;
            foreach ($statuses as $item) {
                if ($status->id == $item->id) {
                    $stat_bool = true;
                }
            }
            if (isset($status) and !empty($status) and ($request->Status !== $status->name) and ($stat_bool == true)) {
                // TODO: Костыль потому что в моделе используются глобальные массивы
                if (isset($request->gfullname)) {
                    $_POST['Request']['gfullname'] = null;
                }
                $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                $_POST['Request']['Status'] = $status->name;
                $_POST['Request']['service_id'] = $request->service_id;
                $_POST['Request']['Priority'] = $request->Priority;
                $_POST['Request']['Content'] = $request->Content;
                $request->attributes = $_POST['Request'];

                if ($request->save()) {
                    $pRequests = Request::model()->findAllByAttributes(['pid' => $request->id]);
                    if (!empty($pRequests)) {
                        foreach ($pRequests as $pRequest) {
                            if (isset($_POST['Request']['Status']) and !empty($_POST['Request']['Status'])) {
                                $pRequest->Status = $_POST['Request']['Status'];
                            }
                            $pRequest->pid = $request->id;
                            $pRequest->save();
                        }
                    }

                    unset($_POST);
                }
            }
        }
    }

    public function actionSetUser()
    {
        if (isset($_GET['checked'])) {
            foreach ($_GET['checked'] as $requestId) {
                $model = $this->loadModel($requestId);
                $mfullname = CUsers::model()->findByAttributes(['Username' => $_GET['user']]);
                $user = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
                $manager = CUsers::model()->findByAttributes(['Username' => $_GET['user']]);
                Request::model()->updateByPk($requestId,
                    [
                        'Managers_id' => $_GET['user'],
                        'mfullname' => $mfullname->fullname,
                        'lastactivity' => date('Y-m-d H:i:s')
                    ]);
                $status_temp = Status::model()->findByAttributes(['name' => $model->Status]);
                $this->AddHistory(Yii::t('main-ui', 'Manager is set to: ') . '<b>' . $mfullname->fullname . '</b>',
                    $requestId);
                $message = $model->Name . "\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
                $url = Yii::app()->createUrl('request/view', ['id' => $model->id]);
                if (isset($user)) {
                    $user->pushMessage($message, $url);
                }
                if ($status_temp->notify_manager == 1) {
                    if ($manager->sendmail == 1) {
                        $manager_address = $manager->Email;
                        $message = Messages::model()->findByAttributes(['name' => $status_temp->mmessage]);
                        $subject = $message->subject;
                        $this->Mailsend($manager_address, $subject, $manager, $message, $model);
                    }
                }

                if ($status_temp->notify_manager_sms == 1) {
                    if ($manager->sendsms == 1) {
                        $managernum = $manager->Phone;
                        $sms = Smss::model()->findByAttributes(['name' => $status_temp->msms]);
                        $this->Smssend($managernum, $manager, $sms, $model);
                    }
                }

                if ($status_temp->notify_user == 1) {
                    if ($user->sendmail == 1) {
                        $user_address = $user->Email;
                        $message = Messages::model()->findByAttributes(['name' => $status_temp->message]);
                        $subject = $message->subject;
                        $this->Mailsend($user_address, $subject, $manager, $message, $model);
                    }
                }

                if ($status_temp->notify_user_sms == 1) {
                    if ($user->sendsms == 1) {
                        $usernum = $user->Phone;
                        $sms = Smss::model()->findByAttributes(['name' => $status_temp->sms]);
                        $this->Smssend($usernum, $manager, $sms, $model);
                    }
                }
            }
        }
    }

    public function actionSetGroup()
    {
        if (isset($_GET['checked'])) {
            foreach ($_GET['checked'] as $requestId) {
                $model = $this->loadModel($requestId);
                $group = Groups::model()->findByPk($_GET['group']);
                Request::model()->updateByPk($requestId, [
                    'groups_id' => $_GET['group'],
                    'gfullname' => $group->name,
                    'mfullname' => null,
                    'Managers_id' => null,
                    'lastactivity' => date('Y-m-d H:i:s')
                ]);
                $this->AddHistory(Yii::t('main-ui', 'Group managers is set to: ') . '<b>' . $group->name . '</b>',
                    $requestId);

                $group_users = explode(',', $group->users);
                $status_temp = Status::model()->findByAttributes(['name' => $model->Status]);

                foreach ($group_users as $user_id) {
                    $manager = CUsers::model()->findByPk($user_id);
                    $message = $model->Name . "\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
                    $url = Yii::app()->createUrl('request/view', ['id' => $model->id]);
                    $manager->pushMessage($message, $url);
                    if ($status_temp->notify_manager == 1) {
                        if ($manager->sendmail == 1) {
                            $manager_address = $manager->Email;
                            $message = Messages::model()->findByAttributes(['name' => $status_temp->mmessage]);
                            $subject = $message->subject;
                            $this->Mailsend($manager_address, $subject, $manager, $message, $model);
                        }
                    }

                    if ($status_temp->notify_manager_sms == 1) {
                        if ($manager->sendsms == 1) {
                            $managernum = $manager->Phone;
                            $sms = Smss::model()->findByAttributes(['name' => $status_temp->msms]);
                            $this->Smssend($managernum, $manager, $sms, $model);
                        }
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function actionIndex()
    {
        if (isset($_GET['savefilter']) and $_GET['savefilter'] === 'save') {
            Yii::app()->session['requestSaveFilter'] = Yii::app()->session['tempRequestSaveFilter'];
            Yii::app()->session['sortFilter'] = Yii::app()->session['tempSortFilter'];
            exit;
        } elseif (isset($_GET['savefilter']) and $_GET['savefilter'] === 'clear') {
            unset(Yii::app()->session['requestSaveFilter']);
            unset(Yii::app()->session['tempRequestSaveFilter']);
            unset(Yii::app()->session['tempSortFilter']);
            unset(Yii::app()->session['sortFilter']);

            exit;
        }

        if (isset($_GET['stoptimer']) and $_GET['stoptimer'] === 'start') {
            Yii::app()->session['requestStopTimer'] = 1;
            exit;
        } elseif (isset($_GET['stoptimer']) and $_GET['stoptimer'] === 'stop') {
            unset(Yii::app()->session['requestStopTimer']);
            exit;
        }

        if (isset($_GET['lastactivity']) and $_GET['lastactivity'] === 'start') {
            Yii::app()->session['requestlastactivity'] = 1;
            exit;
        } elseif (isset($_GET['lastactivity']) and $_GET['lastactivity'] === 'stop') {
            unset(Yii::app()->session['requestlastactivity']);
            exit;
        }

        if (isset($_GET['fixheader']) and $_GET['fixheader'] === 'start') {
            Yii::app()->session['requestFixHeader'] = 1;
            exit;
        } elseif (isset($_GET['fixheader']) and $_GET['fixheader'] === 'stop') {
            unset(Yii::app()->session['requestFixHeader']);
            exit;
        }

        if (isset($_GET['responsive']) and $_GET['responsive'] === 'start') {
            Yii::app()->session['requestResponsive'] = 1;
            exit;
        } elseif (isset($_GET['responsive']) and $_GET['responsive'] === 'stop') {
            unset(Yii::app()->session['requestResponsive']);
            exit;
        }

        unset(Yii::app()->session['customReport']);
        if (isset($_GET['pageCount'])) {
            Yii::app()->session['requestPageCount'] = $_GET['pageCount'];
        }

        if (Yii::app()->user->checkAccess('canViewFieldsRequestList')) {
            $model = new RequestFullFields('search');

            $model->unsetAttributes(); // clear any default values

            if (isset($_GET['RequestFullFields'])) {
                $model->attributes = $_GET['RequestFullFields'];
                $model->setFullAttributes($_GET['RequestFullFields']);
                Yii::app()->session['tempRequestSaveFilter'] = $_GET['RequestFullFields'];
                Yii::app()->session['tempSortFilter'] = $_GET['sort'];
                if (isset($_GET['RequestFullFields']['slabel'])) {
                    Yii::app()->session['tempRequestSaveFilter']['Status'] = $_GET['RequestFullFields']['slabel'];
                    unset(Yii::app()->session['tempRequestSaveFilter']['slabel']);
                }
            } elseif (isset(Yii::app()->session['requestSaveFilter'])) {
                $model->attributes = Yii::app()->session['requestSaveFilter'];
                $_GET['RequestFullFields'] = Yii::app()->session['requestSaveFilter'];
                $_GET['sort'] = Yii::app()->session['sortFilter'];
            }

            $this->render('adminFullFields', [
                'model' => $model,
            ]);
        } else {
            $model = new Request('search');

            $model->unsetAttributes(); // clear any default values

            if (isset($_GET['Request'])) {
                $model->attributes = $_GET['Request'];
                Yii::app()->session['tempRequestSaveFilter'] = $_GET['Request'];
                Yii::app()->session['tempSortFilter'] = $_GET['sort'];
                if (isset($_GET['Request']['slabel'])) {
                    Yii::app()->session['tempRequestSaveFilter']['Status'] = $_GET['Request']['slabel'];
                    unset(Yii::app()->session['tempRequestSaveFilter']['slabel']);
                }
            } elseif (isset(Yii::app()->session['requestSaveFilter'])) {
                $model->attributes = Yii::app()->session['requestSaveFilter'];
                $_GET['Request'] = Yii::app()->session['requestSaveFilter'];
                $_GET['sort'] = Yii::app()->session['sortFilter'];
            }

            $this->render('admin', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdName($id)
    {
        Request::model()->updateByPk($id,
            [$_POST['name'] => $_POST['value'], 'lastactivity' => date("Y-m-d H:i:s")]);
        $this->AddHistory(Yii::t('main-ui', 'Name of the ticket is set to: ') . '<b>' . $_POST['value'] . '</b>', $id);
    }

    public function actionUpdCategory($id)
    {
        Request::model()->updateByPk($id,
            [$_POST['name'] => $_POST['value'], 'lastactivity' => date("Y-m-d H:i:s")]);
        $this->AddHistory(Yii::t('main-ui', 'Ticket category is set to: ') . '<b>' . $_POST['value'] . '</b>', $id);
    }

    public function actionUpdStartTime($id)
    {
        if (isset($_POST) and $_POST['value'] !== '') {
            if ($_POST['name'] == 'EndTime') {
                $model = Request::model()->findByPk($id);
                $aatr = $model->getAttributeLabel($_POST['name']);

                if (!empty($model->timestampClose)) {
//                    $newEndDate = new DateTime($_POST['value']);
//                    $oldEndDate = new DateTime($model->EndTime);
//                    $oldTimestampClose = new DateTime($model->timestampClose);
//                    $diffSec = $newEndDate->getTimestamp() - $oldEndDate->getTimestamp();
//
//                    if ($diffSec > 0) {
//                        $oldTimestampClose->modify('+ ' . $diffSec . ' seconds');
//                    } else {
//                        $oldTimestampClose->modify('- ' . abs($diffSec) . ' seconds');
//                    }
//                    $newTimestampClose = $oldTimestampClose->format('Y-m-d H:i:s');
//                    Request::model()->updateByPk($id,
//                        array(
//                            $_POST['name'] => $_POST['value'],
//                            'timestampClose' => $newTimestampClose,
//                            'lastactivity' => date("Y-m-d H:i:s")
//                        ));
//                    $this->AddHistory($aatr . Yii::t('main-ui', ' is set to: ') . '<b>' . $_POST['value'] . '</b>',
//                        $id);
                } else {
                    $newEndDate = strtotime($_POST['value']);
                    Request::model()->updateByPk($id, [
                        $_POST['name'] => $_POST['value'],
                        'timestampEnd' => date("Y-m-d H:i:s", $newEndDate),
                        'lastactivity' => date("Y-m-d H:i:s")
                    ]);
                    $this->AddHistory($aatr . Yii::t('main-ui', ' is set to: ') . '<b>' . $_POST['value'] . '</b>',
                        $id);
                }
            } else {
                if ($_POST['name'] == 'StartTime') {
                    $model = Request::model()->findByPk($id);
                    $aatr = $model->getAttributeLabel($_POST['name']);

                    $newEndDate = strtotime($_POST['value']);
                    Request::model()->updateByPk($id, [
                        $_POST['name'] => $_POST['value'],
                        'timestampStart' => date("Y-m-d H:i:s", $newEndDate),
                        'lastactivity' => date("Y-m-d H:i:s")
                    ]);
                    $this->AddHistory($aatr . Yii::t('main-ui', ' is set to: ') . '<b>' . $_POST['value'] . '</b>',
                        $id);
                } else {
                    $model = Request::model()->findByPk($id);
                    $aatr = $model->getAttributeLabel($_POST['name']);
                    Request::model()->updateByPk($id,
                        [$_POST['name'] => $_POST['value'], 'lastactivity' => date("Y-m-d H:i:s")]);
                    $this->AddHistory($aatr . Yii::t('main-ui', ' is set to: ') . '<b>' . $_POST['value'] . '</b>',
                        $id);
                }
            }
            //send E-mail
            if ($model->channel !== 'Telegram' and $model->channel !== 'Viber') {
                $changer = CUsers::model()->findByAttributes(['id' => Yii::app()->user->id]);
                $subject = '[Ticket #' . $model->id . '] ' . $model->Name . '';
                $message = Yii::t('main-ui',
                        'According to your ticket, there have been changes:') . '<br/>' . Yii::t('main-ui',
                        'User ') . $changer->fullname . Yii::t('main-ui', ' changes ') . $aatr . Yii::t('main-ui',
                        ' to: ') . $_POST['value'] . '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl . '/request/' . $model->id . '">№ ' . $model->id . '</a>';
                if ($model->CUsers_id !== null) {
                    $user = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
                    if ($user->sendmail == 1) {
                        $email = $user->Email;
                    } else {
                        $email = null;
                    }
                } else {
                    $email = $model->fullname;
                }
                if (!empty($email)) {
                    if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                        Yii::app()->mailQueue->push($email, $subject, $message, $priority = 0, $from = '', null, null);
                    } else {
                        SendMail::send($email, $subject, $message, null, $model->getmailconfig);
                    }
                }
                $watchers = explode(",", $model->watchers);
                if ($watchers[0] !== '') {
                    foreach ($watchers as $watcher) {
                        $user = CUsers::model()->findByAttributes(['fullname' => $watcher]);
                        if ($user->sendmail == 1) {
                            if (isset(Yii::app()->params['smqueue']) and Yii::app()->params['smqueue'] == 1) { //проверка включена ли очередь
                                Yii::app()->mailQueue->push($user->Email, $subject, $message, $priority = 0, $from = '',
                                    null, null);
                            } else {
                                SendMail::send($user->Email, $subject, $message, null, $model->getmailconfig);
                            }
                        }
                    }
                }
            }
            //end sending Email
        } else {
            return false;
        }
    }

    public function actionupdWatchers($id)
    {
        if (isset($_POST['value'])) {
            $value = $_POST['value'];
            $watchers = [];
            foreach ($value as $item) {
                $exist = CUsers::model()->findByAttributes(['fullname' => $item]);
                if ($exist) {
                    $watchers[] = $item;
                }
            }
            $values = implode(",", $watchers);
        } else {
            $values = '';
        }
        Request::model()->updateByPk($id, ['watchers' => $values, 'lastactivity' => date('Y-m-d H:i:s')]);
        $this->AddHistory(Yii::t('main-ui', 'Watcher is set to: ') . '<b>' . $values . '</b>', $id);
    }

    public function actionupdUser($id)
    {
        $value = CUsers::model()->findByAttributes(['Username' => $_POST['value']]);
        $company = Companies::model()->findByAttributes(['name' => $value->company]);
        $depart = Depart::model()->findByAttributes(['name' => $value->department, 'company' => $company->name]);
        $req = Request::model()->findByPk($id);
        Request::model()->updateByPk($id, [
            'CUsers_id' => $value->Username,
            'fullname' => $value->fullname,
            'company' => $value->company,
            'company_id' => $company->id,
            'depart' => $depart->name,
            'depart_id' => $depart->id,
            'phone' => $value->Phone,
            'room' => $value->room,
            'Address' => $company->faddress,
            'lastactivity' => date('Y-m-d H:i:s')
        ]);
        CUsers::model()->updateByPk($value->id, [
            'tbot' => $req->tchat_id,
            'vbot' => $req->viber_id,
            'msbot' => $req->msbot_id,
            'wbot' => $req->wbot_id,
        ]);
        $this->AddHistory(Yii::t('main-ui', 'User is set to: ') . '<b>' . $value->fullname . '</b>', $id);
    }

    public function actionupdUser2()
    {
        $value = CUsers::model()->findByPk($_POST['user'][0]);
        $company = Companies::model()->findByAttributes(['name' => $value->company]);
        $depart = Depart::model()->findByAttributes(['name' => $value->department, 'company' => $company->name]);

        $id = $_POST['model'];
        $req = Request::model()->findByPk($id);
        //Changing Email
        if ($req->channel == "Email" and empty($req->CUsers_id)) {
            CUsers::model()->updateByPk($value->id, ['Email' => $req->fullname]);
        }
        //Changing Telegram chat_id
        if (isset($req->tchat_id) and !empty($req->tchat_id)) {
            $old = CUsers::model()->findByAttributes(['tbot' => $req->tchat_id]);
            if (isset($old)) {
                CUsers::model()->updateByPk($value->id, ['tbot' => null]);
                CUsers::model()->updateByPk($value->id, ['tbot' => $req->tchat_id]);
            } else {
                CUsers::model()->updateByPk($value->id, ['tbot' => $req->tchat_id]);
            }
        }
        //Changing Viber chat_id
        if (isset($req->viber_id) and !empty($req->viber_id)) {
            $old = CUsers::model()->findByAttributes(['vbot' => $req->viber_id]);
            if (isset($old)) {
                CUsers::model()->updateByPk($value->id, ['vbot' => null]);
                CUsers::model()->updateByPk($value->id, ['vbot' => $req->viber_id]);
            } else {
                CUsers::model()->updateByPk($value->id, ['vbot' => $req->viber_id]);
            }
        }
        //Changing MSBot chat_id
        if (isset($req->msbot_id) and !empty($req->msbot_id)) {
            $old = CUsers::model()->findByAttributes(['msbot' => $req->msbot_id]);
            if (isset($old)) {
                CUsers::model()->updateByPk($value->id, ['msbot' => null]);
                CUsers::model()->updateByPk($value->id, ['msbot' => $req->msbot_id]);
            } else {
                CUsers::model()->updateByPk($value->id, ['msbot' => $req->msbot_id]);
            }
        }
        //Changing Whatsapp chat_id
        if (isset($req->wbot_id) and !empty($req->wbot_id)) {
            $old = CUsers::model()->findByAttributes(['wbot' => $req->wbot_id]);
            if (isset($old)) {
                CUsers::model()->updateByPk($value->id, ['wbot' => null]);
                CUsers::model()->updateByPk($value->id, ['wbot' => $req->wbot_id]);
            } else {
                CUsers::model()->updateByPk($value->id, ['wbot' => $req->wbot_id]);
            }
        }
        //Update ticket user
        Request::model()->updateByPk($id, [
            'CUsers_id' => $value->Username,
            'fullname' => $value->fullname,
            'company' => $value->company,
            'company_id' => $company->id,
            'depart' => $depart->name,
            'depart_id' => $depart->id,
            'phone' => $value->Phone,
            'room' => $value->room,
            'Address' => $company->faddress,
            'lastactivity' => date('Y-m-d H:i:s')
        ]);


        $this->AddHistory(Yii::t('main-ui', 'User is set to: ') . '<b>' . $value->fullname . '</b>', $id);
    }

    public function actionupdUnits($id)
    {
        if (isset($_POST['value'])) {
            $value = $_POST['value'];
            $units = array();
            foreach ($value as $item) {
                $exist = Cunits::model()->findByAttributes(['name' => $item]);
                if ($exist) {
                    $units[] = $item;
                }
            }
            $values = implode(",", $units);
            $hist = implode(", ", $units);
        } else {
            $values = '';
        }
        Request::model()->updateByPk($id, array('cunits' => $values, 'lastactivity' => date('Y-m-d H:i:s')));
        $this->AddHistory(Yii::t('main-ui', 'Changed units: ') . '<b>' . $hist . '</b>', $id);
    }

    public function actionupdTcategory($id)
    {
        if (isset($_POST['value'])) {
            $value = $_POST['value'];
            // $tc = array();
            // foreach ($value as $item) {
            //     $exist = Tcategory::model()->findByAttributes(['name' => $item]);
            //     if ($exist) {
            //         $tc[] = $item;
            //     }
            // }
            // $values = implode(",", $tc);
            // $hist = implode(", ", $tc);
        } else {
            $values = '';
        }
        // if (isset($_POST['value'])) {
        //     $value = $_POST['value'];
        //     // $exist = Tcategory::model()->findByAttributes(['name' => $value]);
        // } else {
        //     $value = '';
        // }
        Request::model()->updateByPk($id, array('tcategory' => $value, 'lastactivity' => date('Y-m-d H:i:s')));
        $this->AddHistory(Yii::t('main-ui', 'Changed tcategory: ') . '<b>' . $value . '</b>', $id);
    }

    public function actionSelectObject()
    {
        $username = CUsers::model()->findByPk(Yii::app()->user->id);
        $data = Cunits::model()->findAllByAttributes(
            array(
                'type' => $_POST['Request']['Type'],
                'user' => $username->Username,
            ));

        $data = CHtml::listData($data, 'name', 'name');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionSelectPriority()
    {
        if (Yii::app()->user->checkAccess('canSelectDeadline')) {
            $priority = null;
            $priority = Service::model()->findByPk($_POST['Request']['service_id']);
            $sla = Sla::model()->findByAttributes(['name' => $priority->sla]);
            $options = null;
            $data = Zpriority::model()->findAllByAttributes(['name' => $priority->priority]);
            $data2 = Zpriority::model()->findAll();
            $data3 = array_merge($data, $data2);
            $data = CHtml::listData($data3, 'name', 'name');
            $workingTime = new WorkingTimeComponent($sla, $data);
            $currentDateTime = date('Y-m-d H:i');
            $sltime = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm',
                $workingTime->getSolution($currentDateTime));
            $endTime = $sltime;
            foreach ($data as $value => $name) {
                $options .= CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
            }
            echo CJSON::encode([
                'options' => $options,
                'manager' => $priority->manager,
                'fid' => $priority->fieldset,
                'content' => $priority->content,
                'description' => $priority->description,
                'watcher' => explode(',', $priority->watcher),
                'endtime' => $endTime,
                'csrf' => Yii::app()->request->csrfToken,
            ]);
        } else {
            $priority = null;
            $priority = Service::model()->findByPk($_POST['Request']['service_id']);
            $options = null;
            $data = Zpriority::model()->findAllByAttributes(['name' => $priority->priority]);
            $data2 = Zpriority::model()->findAll();
            $data3 = array_merge($data, $data2);
            $data = CHtml::listData($data3, 'name', 'name');
            foreach ($data as $value => $name) {
                $options .= CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
            }
            echo CJSON::encode([
                'options' => $options,
                'fid' => $priority->fieldset,
                'content' => $priority->content,
                'description' => $priority->description,
                'watcher' => explode(',', $priority->watcher),
                'csrf' => Yii::app()->request->csrfToken,
            ]);
        }
    }

    public function actionSelectSLA()
    {
        $service = Service::model()->findByPk($_POST['service_id']);
        $sla = Sla::model()->findAllByPk(explode(',',  $service->sla));
        $res = [];
        foreach($sla as $s){
            $res[$s['id']] = $s['name'];
        }
        echo CJSON::encode($res);
        // echo CJSON::encode([
        //     'options' => $options,
        //     'fid' => $priority->fieldset,
        //     'content' => $priority->content,
        //     'description' => $priority->description,
        //     'watcher' => explode(',', $priority->watcher),
        //     'csrf' => Yii::app()->request->csrfToken,
        // ]);
    }

    public function actionGetSLA()
    {
        $sla = Sla::model()->findByPk($_POST['sla']);
        echo CJSON::encode(['id'=>$sla['id'], 'name'=>$sla['name']]);
        // echo CJSON::encode([
        //     'options' => $options,
        //     'fid' => $priority->fieldset,
        //     'content' => $priority->content,
        //     'description' => $priority->description,
        //     'watcher' => explode(',', $priority->watcher),
        //     'csrf' => Yii::app()->request->csrfToken,
        // ]);
    }



    public function actionSelectTemplate($id)
    {
        $data = ReplyTemplates::model()->findByPk($_POST['Comments']['theme']);
        $model = $this->loadModel($id);
        $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
        $content = $this->MessageGen($data->content, $manager, $model);
        echo CJSON::encode([
            'content' => $content,
        ]);
    }

    public function actionSelectKB()
    {
        $data = Knowledge::model()->findByPk($_POST['Comments']['kbtheme']);
        $content = $data->content;
        echo CJSON::encode([
            'content' => $content,
        ]);
    }

    public function actionSplit()
    {
        if (Yii::app()->request->getIsAjaxRequest()) {
            $id = (int)$_GET['id'];
            $pid = (int)$_GET['pid'];
            $request = Request::model()->findByPk($id);
            $request->updateByPk($id, ['update_by' => null, 'pid' => 0]);
            $request->restoreOldFields();
            $count = Request::model()->countByAttributes(['pid' => $pid]);
            //$ch_label = '<span class="lb-danger">' . (int)$count . '</span>';
            $ch_label = '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: red; vertical-align: baseline; white-space: nowrap; border: 1px solid red; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . (int)$child . '</span>';
            if ((int)$count > 0) {
                Request::model()->updateByPk($pid, ['child' => $ch_label, 'lastactivity' => date("Y-m-d H:i:s")]);
            } else {
                Request::model()->updateByPk($pid, ['child' => null, 'lastactivity' => date("Y-m-d H:i:s")]);
            }
            echo (int)$count;
        }
    }

    public function actionSetFields()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $criteria = new CDbCriteria(['order' => 'sid ASC']);
        $fields = FieldsetsFields::model()->findAllByAttributes(['fid' => $id], $criteria);
        $this->renderPartial('_ajaxform', ['fields' => $fields]);
    }


    public function actionSetFields2()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $criteria = new CDbCriteria(['order' => 'sid ASC']);
        $priority = Service::model()->findByPk($id);
        $fields = FieldsetsFields::model()->findAllByAttributes(['fid' => $priority->fieldset], $criteria);
        $this->renderPartial('_ajaxform', ['fields' => $fields]);
    }

    public function actionSettemplate()
    {
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $priority = Service::model()->findByPk($id);
        echo CJSON::encode([
            'content' => $priority->content,
            'description' => $priority->description,
            'watcher' => explode(',', $priority->watcher),
        ]);
    }

    //Generate message content by some templates

    public function actionSelectAdmObject()
    {
        if (isset($_POST['pid'])) {
            $user = $_POST['pid'];
        } else {
            $user = $_POST['Request']['CUsers_id'];
            echo $user;
        }
        if (Yii::app()->user->checkAccess('unitByUserRequest')) {
            $data = Cunits::model()->findAllByAttributes(
                [
                    'user' => $user,
                ]);
        } else {
            $data = Cunits::model()->findAll();
        }

        $data = CHtml::listData($data, 'name', 'name');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }
    }

    public function actionSelectUser()
    {
        if (isset($_POST['id'])) {
            $data0 = ['0' => '+++ ' . Yii::t('main-ui', 'Create user') . ' +++'];
            $data1 = CUsers::model()->findAllByPk((int)$_POST['id'][0]);
            $data2 = CHtml::listData(CUsers::model()->findAllByAttributes(['active' => 1]), 'Username',
                'fullname');
            $data = CHtml::listData($data1, 'Username', 'fullname');
            $data3 = array_merge($data, $data0, $data2);
            foreach ($data3 as $value => $name) {
                echo CHtml::tag('option',
                    ['value' => $value], CHtml::encode($name), true);
            }
        }
    }

    // Добавление записи в историю

    public function actionSelectFObject()
    {
        if (Yii::app()->params['t_filter'] == 'company') {
            $filter = 'company';
            $post = 'company';
        } else {
            $filter = 'department';
            $post = 'depart';
        }
        $data = CUsers::model()->findAllByAttributes(
            [
                $filter => $_POST['Request'][$post],
                'active' => 1
            ]);
        $data1 = ['' => Yii::t('main-ui', 'Select item')];
        $data2 = CHtml::listData($data, 'Username', 'fullname');
        $data = array_merge($data1, $data2);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                ['value' => $value, 'seleceted' => 'seleceted'], CHtml::encode($name), true);
        }
    }

    public function actionSelectFObject2()
    {
        if (Yii::app()->params['t_filter'] == 'company') {
            $filter = 'company_name';
            $post = 'company';
        }
        $data = Service::model()->findAllByAttributes(
            [
                $filter => $_POST['Request'][$post],
            ]);
        $data = CHtml::listData($data, 'id', 'name');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                ['value' => $value], CHtml::encode($name), true);
        }
    }

    public function actionSelectDepart()
    {
        if ($_POST['Request']['depart']) {
            $criteria = new CDbCriteria(['order' => 'fullname']);
            $criteria->condition = 'role != "systemAdmin"';
            $data = CUsers::model()->findAllByAttributes(
                [
                    'department' => $_POST['Request']['depart'],
                    'active' => 1
                ], $criteria);
        } else {
            $criteria = new CDbCriteria(['order' => 'fullname']);
            $criteria->condition = 'role != "systemAdmin"';
            $data = CUsers::model()->findAllByAttributes(['active' => 1], $criteria);
        }


        $data = CHtml::listData($data, 'Username', 'fullname');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                ['value' => $value], CHtml::encode($name), true);
        }
    }

    public function actionUpdateAdmObject()
    {
        $data = Cunits::model()->findAllByAttributes(
            [
                'type' => $_POST['Request']['Type'],
                'user' => $_POST['Request']['CUsers_id'],
            ]);

        $data = CHtml::listData($data, 'name', 'name');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                ['value' => $value], CHtml::encode($name), true);
        }
    }

    public function actionSelectService()
    {
        $model = new Request();
        if (isset($_POST['Request']['company'])) {
            echo CHtml::activeLabelEx($model, 'service_id');
            echo CHtml::activeDropDownList($model, 'service_id', [],
                ['id' => 'ss', 'style' => 'width:100%']);
        }
    }

    public function actionRating()
    {
        Request::model()->updateByPk((int)$_GET['id'], ['rating' => (int)$_GET['star_rating']]);
        $this->AddHistory(Yii::t('main-ui', 'Request rated to: ') . '<b>' . (int)$_GET['star_rating'] . '</b>',
            (int)$_GET['id'], Yii::app()->user->name);
    }

    public function actionRatingFromMail($id, $star_rating, $key)
    {
        if ((isset($id) and is_numeric($id)) and
            (isset($star_rating) and is_numeric($star_rating)) and
            (isset($key) and !empty($key))
        ) {
            $model = Request::model()->findByPk($id);
            if (isset($model->key) and !empty($model->key)) {
                if ($model->key == $key) {
                    Request::model()->updateByPk((int)$id,
                        ['rating' => (int)$star_rating, 'key' => null, 'lastactivity' => date("Y-m-d H:i:s")]);
                    $this->AddHistory(Yii::t('main-ui', 'Request rated to: ') . '<b>' . (int)$star_rating . '</b>', $id,
                        $model->CUsers_id);
                    echo Yii::t('main-ui', 'Thank you! Your rate adopted.');
                }
            } else {
                echo Yii::t('main-ui', 'You have already rated this ticket.');
            }
        }
    }

    public function actionReopenFromMail($id, $key)
    {
        if ((isset($id) and is_numeric($id)) and
            (isset($key) and !empty($key))
        ) {
            $model = Request::model()->findByPk($id);
            if (isset($model->key) and !empty($model->key)) {
                if ($model->key == $key) {
                    $status = Status::model()->findByAttributes(['enabled' => 1, 'close' => 9]);
                    if (isset($status)) {
                        $request = $model;
                        $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                        $_POST['Request']['Status'] = $status->name;
                        $_POST['Request']['service_id'] = $request->service_id;
                        $_POST['Request']['Priority'] = $request->Priority;
                        $_POST['Request']['Content'] = $request->Content;
                        $request->attributes = $_POST['Request'];

                        if ($request->save()) {
                            Request::model()->updateByPk((int)$id, [
                                'rating' => null,
                                'lastactivity' => date('Y-m-d H:i:s'),
                                'timestampfEnd' => null,
                                'fEndTime' => null,
                                'closed' => null,
                                //'Status' => $status->name,
                                'slabel' => $status->label,
                                'reopened' => 1
                            ]);
                            $this->AddHistory(Yii::t('main-ui', 'Ticket status is set to: ') . $status->label, $id,
                                $model->CUsers_id);
                        }
                    } else {
                        $request = $model;
                        $_POST['Request']['CUsers_id'] = $request->CUsers_id;
                        $_POST['Request']['Status'] = 'Открыта';
                        $_POST['Request']['service_id'] = $request->service_id;
                        $_POST['Request']['Priority'] = $request->Priority;
                        $_POST['Request']['Content'] = $request->Content;
                        $request->attributes = $_POST['Request'];

                        if ($request->save()) {
                            Request::model()->updateByPk((int)$id, [
                                'rating' => null,
                                'lastactivity' => date("Y-m-d H:i:s"),
                                'timestampfEnd' => null,
                                'fEndTime' => null,
                                'closed' => null,
                                //'Status' => 'Открыта',
                                'slabel' => '<span class="label label-success">Открыта повторно</span>',
                                'reopened' => 1
                            ]);
                            $this->AddHistory(Yii::t('main-ui',
                                    'Ticket status is set to: ') . '<span class="label label-success">Открыта повторно</span>',
                                $id, $model->CUsers_id);
                        }
                    }
                    unset($_POST);
//                    Yii::app()->user->setFlash('info',
//                        Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully reopen this ticket.'));
//                    $this->redirect(array('view', 'id' => $model->id));
                    echo Yii::t('main-ui', '<strong>Congratulations!</strong> You successfully reopen this ticket.');
                }
            } else {
//                Yii::app()->user->setFlash('danger',
//                    Yii::t('main-ui', '<strong>Error!</strong> You can`t reopen this ticket.'));
//                $this->redirect(array('view', 'id' => $model->id));
                echo Yii::t('main-ui', '<strong>Error!</strong> You can`t reopen this ticket.');
            }
        }
    }

    public function actionGetservices()
    {
        if (isset($_POST['user']) and ($_POST['user'] !== 0)) {
            $return = [];
            /** @var CUsers $user */
            $user = CUsers::model()->findByAttributes(['Username' => $_POST['user']]);
            if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
                $services = $user->getServicesArray($_POST['category_id']);
            } else {
                $services = $user->getServicesArray();
            }
            foreach ($services as $key => $service) {
                $return[] = ['id' => $key, 'text' => $service];
            }

            usort($return, function ($a, $b) {
                return strcmp($a["text"], $b["text"]);
            });
            echo CJSON::encode($return);
        }
    }
}
