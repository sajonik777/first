<?php

$config = __DIR__ . '/protected/config/main.php';
require_once(__DIR__ . '/protected/vendors/yii/yii.php');
Yii::createWebApplication($config);

ini_set('display_errors', 'Off');
//ini_set('display_errors', 'On');
error_reporting(0);
//error_reporting(1);

if (1 != Yii::app()->params['MSBotEnabled']) {
    exit;
}

if ('' == ini_get('date.timezone')) {
    date_default_timezone_set(Yii::app()->params['timezone']);
}

$microsoftBot = new MicrosoftBotFramework(Yii::app()->params['MSBotAppId'], Yii::app()->params['MSBotAppPassword']);

$requestActivity = $microsoftBot->receiveMessage();

if ($requestActivity) {

    $buttons = null;
    $title = null;

    // Определяем какой тип сообщения мы получили
    switch ($requestActivity['type']) {

        case 'message':
            $chat_id = $requestActivity['conversation']['id'];
            $exist = Request::model()->findByAttributes(['msbot_id' => $chat_id]);

            if (!empty($exist) && isset($requestActivity['text']) && mb_strtolower(trim($requestActivity['text'])) === 'закрыть') {

                $title = $message = 'Спасибо, вы успешно изменили обращение, оцените работу исполнителя, чтобы закрыть заявку.';
                $buttons = [];
                for ($i = 1; $i <= 5; $i++) {
                    $buttons[] = [
                        'title' => "$i",
                        'type' => 'imBack',
                        'value' => "$i",
                    ];
                }
                closeRequest($requestActivity, $exist);
                exit;

            } elseif (empty($exist)) {

                $id = createNewRequest($requestActivity, $microsoftBot);
                $title = $message = "Ваше обращение принято! № заявки: {$id}";
                $buttons = [
                    [
                        'title' => 'Закрыть заявку',
                        'type' => 'imBack',
                        'value' => 'Закрыть',
                    ],
                ];

            } else {

                if (isset($requestActivity['text']) && in_array(trim($requestActivity['text']),
                        ['1', '2', '3', '4', '5'])) {
                    $caseNumber = (int)trim($requestActivity['text']);
                    setRating($exist, $caseNumber, $exist->fullname);
                    $message = 'Спасибо за оценку! Заявка закрыта.';
                } else {
                    createNewComment($requestActivity, $exist, $microsoftBot);
                    $message = "Ваш комментарий к заявке №{$exist->id} принят!";
                }

            }

            break;

        default:
            $message = 'Неизвестный тип сообщения';
            break;
    }

    $microsoftBot->replyToMessage($message, $title, $buttons);
}

/**
 * @param array $requestActivity
 * @param $microsoftBot MicrosoftBotFramework
 * @return bool|integer
 * @throws CException
 */
function createNewRequest($requestActivity, &$microsoftBot)
{
    $channel = ucfirst($requestActivity['channelId']);
    if ($channel === 'Facebook') {
        $icon = 'fa-brands fa-facebook';
    } elseif ($channel === 'Skype') {
        $icon = 'fa-brands fa-skype';
    } elseif ($channel === 'Slack') {
        $icon = 'fa-brands fa-slack';
    } elseif ($channel === 'Webchat') {
        $icon = 'fa-solid fa-comment-sms';
    } else {
        $icon = 'fa-solid fa-ticket';
    }
    $content = isset($requestActivity['text']) ? $requestActivity['text'] : '';
    if (isset($requestActivity['attachments'])) {
        if ($channel === 'Skype') {
            $content .= saveAttachments($requestActivity['attachments'], $microsoftBot);
        } else {
            $content .= getAttachments($requestActivity['attachments']);
        }
    }
    $chat_id = $requestActivity['conversation']['id'];
    $name = "{$channel} ticket";
    $fullname = $requestActivity['from']['name'];
    $user = CUsers::model()->findByAttributes(array('msbot' => $chat_id));

    $status = Status::model()->findByAttributes(['close' => 1]);

    $model = new PortalRequest;
    $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
    $model->Name = $name;
    $model->channel = $channel;
    $model->channel_icon = $icon;
    $model->ZayavCategory_id = "{$channel} ticket";
    $model->Content = $content;
    $model->creator = "{$channel} bot";
    $model->fullname = $user ? $user->fullname : $fullname;
    $model->Priority = Yii::app()->params['zdpriority'];
    if(isset($user) AND !empty($user)){
        $model->CUsers_id = $user->Username;
        $model->company = $user->company;
        $company = Companies::model()->findByAttributes(['name' => $user->company]);
        $model->company_id = $company->id ? $company->id: NULL;
        $depart = Depart::model()->findByAttributes(['name' => $user->department, 'company' => $user->company]);
        $model->depart_id = $depart->id;
    }
    if (1 == Yii::app()->params['zdtype']) {
        $model->Managers_id = Yii::app()->params['zdmanager'];
        $manager = CUsers::model()->findByAttributes(['Username' => Yii::app()->params['zdmanager']]);
    } else {
        $model->gfullname = Yii::app()->params['zdmanager'];
        $group = Groups::model()->findByAttributes(['name' => Yii::app()->params['zdmanager']]);
        $model->groups_id = $group->id;
    }
    $model->Type = '';
    $model->mfullname = $manager->fullname ?: null;
    $model->Date = date('d.m.Y H:i');
    $model->timestamp = date('Y-m-d H:i:s');
    $model->cunits = '';
    $model->Status = $status->name;
    $model->slabel = $status->label;
    $model->msbot_id = $chat_id;
    $model->msbot_params = json_encode($requestActivity);
    if ($model->save(false)) {
        return $model->id;
    }

    return false;
}

/**
 * @param array $requestActivity
 * @param Request $exist
 * @param $microsoftBot MicrosoftBotFramework
 * @return bool
 * @throws CException
 */
function createNewComment($requestActivity, &$exist, &$microsoftBot)
{
    $channel = ucfirst($requestActivity['channelId']);
    $content = isset($requestActivity['text']) ? $requestActivity['text'] : '';
    if (isset($requestActivity['attachments'])) {
        if (isset($requestActivity['attachments'])) {
            if ($channel === 'Skype') {
                $content .= saveAttachments($requestActivity['attachments'], $microsoftBot);
            } else {
                $content .= getAttachments($requestActivity['attachments']);
            }
        }
    }

    $fullname = $requestActivity['from']['name'];
    $user = CUsers::model()->findByAttributes(array('msbot' => $requestActivity['conversation']['id']));
    $flname = $user ? $user->fullname : $fullname;

    $model = new Comments;
    $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);

    $managermail = CUsers::model()->findByAttributes(['Username' => $exist->Managers_id]);
    $subject = '[Ticket #' . $exist->id . '] ' . $exist->Name . '';
    $maddress = isset($managermail) ? [$managermail->Email] : '';

    $template = Messages::model()->findByAttributes(array('name'=>'{comments}'));
    if(isset($template)) {
        $comments = Comments::model()->findAllByAttributes(array('rid' => $exist->id));
        arsort($comments);
        $ctext = null;
        foreach ($comments as $comment) {
            $ctext .= '<blockquote>' . $comment->author . ' [' . $comment->timestamp . '] :<br/>' . $comment->comment . '<br/></blockquote>';
        }
        $comments_list = '<blockquote>' . $ctext . '<br/>' . $exist->Content . '</blockquote>';

        $reply_text = Yii::t('message', "$template->content", array(
            '{author}' => $flname,
            '{date}' => date('d.m.Y H:i'),
            '{comment}' => $content,
            '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $exist->id. '">№ ' . $exist->id. '</a>',
            '{comments_list}' => $comments_list,
        ));
    } else {
        $reply_text = '<b>Добавлен новый комментарий</b><br>' . $flname . ' [' . date('d.m.Y H:i') . ']  :<br/>' . $content . '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl . '/request/' . $exist->id . '">№ ' . $exist->id . '</a>';
        $comments = Comments::model()->findAllByAttributes(['rid' => $exist->id]);
        arsort($comments);
        $ctext = null;
        foreach ($comments as $comment) {
            $ctext .= '<blockquote>' . $comment->author . ' [' . $comment->timestamp . '] :<br/>' . $comment->comment . '<br/></blockquote>';
        }
        $reply_text .= '<blockquote>' . $ctext . '<br/>' . $exist->Content . '</blockquote>';

    }

    $message = $reply_text;
    $model->rid = $exist->id;
    $model->channel = $requestActivity['channelId'];
    $model->timestamp = date('d.m.Y H:i:s');
    $model->author = $flname;
    $model->comment = $content;

    if ($model->save(false)) {
        $exist->updateByPk($exist->id, ['lastactivity' => date('Y-m-d H:i:s')]);
        $history = new History();
        $history->datetime = date('d.m.Y H:i');
        $history->cusers_id = $flname;
        $history->zid = $exist->id;
        $history->action = Yii::t('main-ui', 'Added new comment: ') . '<b>' . $content . '</b>';
        $history->save(false);
        if (!isset($exist->Managers_id) && isset($exist->groups_id)) {
            $groups = Groups::model()->findByPk($exist->groups_id);
            $managers = explode(",", $groups->users);
            if (isset($managers)) {
                foreach ($managers as $manager_id) {
                    $email = CUsers::model()->findByPk($manager_id);
                    SendMail::send($email->Email, $subject, $message, null);
                    $pmessage = "[Ticket #" . $exist->id . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($content));
                    $url = Yii::app()->params['homeUrl'] . "/request/" . $exist->id;
                    $email->pushMessage($pmessage, $url);
                    if (Yii::app()->params->use_rapid_msg == 1) {
                        $amessage = $exist->Name . '<br/><b>Был добавлен комментарий</b>: ' . trim(strip_tags($content));
                        $alert = new Alerts();
                        $alert->user = $email->Username;
                        $alert->name = $exist->id;
                        $alert->message = $amessage;
                        $alert->save();
                    }
                }
            }
        } elseif (isset($exist->Managers_id)) {
            SendMail::send($maddress, $subject, $message, null);
            $pmessage = "[Ticket #" . $exist->id . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($content));
            $url = Yii::app()->params['homeUrl'] . "/request/" . $exist->id;
            $managermail->pushMessage($pmessage, $url);
            if (Yii::app()->params->use_rapid_msg == 1) {
                $amessage = $exist->Name . '<br/><b>Был добавлен комментарий</b>: ' . trim(strip_tags($content));
                $alert = new Alerts();
                $alert->user = $managermail->Username;
                $alert->name = $exist->id;
                $alert->message = $amessage;
                $alert->save();
            }
        }
        if ($exist->watchers !== null) {
            $watchers = explode(',', $exist->watchers);
            foreach ($watchers as $key => $watcher) {
                $witem = CUsers::model()->findByAttributes(['fullname' => $watcher]);
                if ($witem->sendmail == 1 and $witem->Email !== null) {
                    $wmail[] = $witem->Email;
                }
            }
            SendMail::send($wmail, $subject, $message, null);
        }

        return $model->id;
    }

    return false;
}

/**
 * @param array $attachments
 * @return string
 */
function getAttachments($attachments)
{
    $return = '';
    foreach ($attachments as $attachment) {
        if ($attachment['contentType'] == 'image/png' || $attachment['contentType'] == 'image/jpeg') {
            $return .= "<img src='{$attachment['contentUrl']}'><br>";
        } else {
            $return .= "<a href='{$attachment['contentUrl']}'>{$attachment['name']}</a><br>";
        }
    }

    return $return;
}

/**
 * @param array $attachments
 * @param $microsoftBot MicrosoftBotFramework
 * @return string
 */
function saveAttachments($attachments, &$microsoftBot)
{
    define('ROOT_PATH', dirname(__FILE__));
    $path = ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    $url = Yii::app()->params['homeUrl'];
    $return = '';
    foreach ($attachments as $attachment) {
        $flname = $attachment['name'];
        $fnameArr = explode('.', $flname);
        $fname = uniqid('', false) . '.' . end($fnameArr);
        $microsoftBot->saveAttachment($attachment['contentUrl'], $path . $fname);
        $fullUrl = $url . '/uploads/' . $fname;
        if ($attachment['contentType'] == 'image/png' || $attachment['contentType'] == 'image/jpeg') {
            $return .= "<img src='{$fullUrl}'><br>";
        } else {
            $return .= "<a href='{$fullUrl}'>{$attachment['name']}</a><br>";
        }
    }

    return $return;
}

/**
 * @param array $requestActivity
 * @param $model
 * @return mixed
 * @throws Exception
 */
function closeRequest($requestActivity, &$model)
{
    $fullname = $requestActivity['from']['name'];
    $user = CUsers::model()->findByAttributes(array('msbot' => $requestActivity['conversation']['id']));
    $flname = $user ? $user->fullname : $fullname;
    $status = Status::model()->findByAttributes(['close' => 3]);
    $message = 'User ' . $flname . ' updated ticket #' . $model->id . ' named "' . $model->Name . '"';
    Yii::log($message, 'updated', 'UPDATED');
    unset($_POST['Requestt']);
    if (!empty($model->gfullname)) {
        $_POST['Requestt']['gfullname'] = $model->gfullname;
    }
    if (!empty($model->Managers_id)) {
        $_POST['Requestt']['Managers_id'] = $model->Managers_id;
        $_POST['Requestt']['mfullname'] = $model->mfullname;
    }
    $_POST['Requestt']['Name'] = $model->Name;
    $_POST['Requestt']['CUsers_id'] = $model->CUsers_id;
    $_POST['Requestt']['Status'] = $status->name;
    $_POST['Requestt']['slabel'] = $status->label;
    $_POST['Requestt']['service_id'] = $model->service_id;
    $_POST['Requestt']['Priority'] = $model->Priority;
    $_POST['Requestt']['Content'] = $model->Content;
    $_POST['Requestt']['fEndTime'] = date('d.m.Y H:i');
    $_POST['Requestt']['timestampfEnd'] = date('Y-m-d H:i:s');

    list($c_d, $c_h, $c_i, $c_s) = explode(':', $model->correct_timestamp);
    $correct_s = 0;
    if ($c_d != 0) {
        $correct_s += $c_d * 24 * 60 * 60;
    }
    if ($c_h != 0) {
        $correct_s += $c_h * 60 * 60;
    }
    if ($c_i != 0) {
        $correct_s += $c_i * 60;
    }
    if ($c_s != 0) {
        $correct_s += $c_s;
    }

    $start = new DateTime($model->Date);
    $end = new DateTime(date('Y-m-d H:i:s'));
    $lead_time = $end->diff($start);

    list($lt_d, $lt_h, $lt_i, $lt_s) = explode(':', $lead_time->format('%d:%h:%i:%s'));
    $leadtime_s = 0;
    if ($lt_d != 0) {
        $leadtime_s += $lt_d * 24 * 60 * 60;
    }
    if ($lt_h != 0) {
        $leadtime_s += $lt_h * 60 * 60;
    }
    if ($lt_i != 0) {
        $leadtime_s += $lt_i * 60;
    }
    if ($lt_s != 0) {
        $leadtime_s += $lt_s;
    }

    if ($correct_s < $leadtime_s) {
        $end->modify('-' . $correct_s . ' seconds');
        $lead_time = $end->diff($start);
        $_POST['Requestt']['lead_time'] = $lead_time->format('%h:%i:%s');
    } else {
        $_POST['Requestt']['lead_time'] = $lead_time->format('%h:%i:%s');
    }

    $_POST['Requestt']['timestampfStart'] = date('Y-m-d H:i:s');

    $_POST['Requestt']['closed'] = 3;//этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
    if ($model->fStartTime == null) {
        $_POST['Requestt']['fStartTime'] = date('d.m.Y H:i');
        $history = new History();
        $history->datetime = date("d.m.Y H:i");
        $history->cusers_id = $flname;
        $history->zid = $model->id;
        $history->action = Yii::t('main-ui', 'Fact start time is set to: ') . '<b>' . date('d.m.Y H:i') . '</b>';
        $history->save(false);
    }
    $history = new History();
    $history->datetime = date("d.m.Y H:i");
    $history->cusers_id = $flname;
    $history->zid = $model->id;
    $history->action = Yii::t('main-ui', 'Fact end time is set to: ') . '<b>' . date('d.m.Y H:i') . '</b>';
    $history->save(false);

    $model->attributes = $_POST['Requestt'];
    $model->Comment = null;

    return $model->save(false);
}

/**
 * @param $model
 * @param $rating
 * @param $user
 */
function setRating(&$model, $rating, $user)
{
    Request::model()->updateByPk($model->id, ['rating' => $rating, 'msbot_id' => null, 'msbot_params' => null]);
    $history = new History();
    $history->datetime = date('d.m.Y H:i');
    $history->cusers_id = $user;
    $history->zid = $model->id;
    $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>' . $rating . '</b>';
    $history->save(false);
}
