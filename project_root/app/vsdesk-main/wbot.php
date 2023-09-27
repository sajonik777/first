<?php

$yii = dirname(__FILE__) . '/protected/vendors/yii/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

require_once $yii;

Yii::createWebApplication($config);

ini_set('display_errors', 'Off');
error_reporting(0);

if (ini_get('date.timezone') == '') {
    date_default_timezone_set(Yii::app()->params['timezone']);
}

if (0 === (int)Yii::app()->params['WBotEnabled']) {
    return;
}

$webhookBody = json_decode(file_get_contents('php://input'), true);
if (!$webhookBody || !is_array($webhookBody) || empty($webhookBody)) {
    return;
}

if (!isset($webhookBody['messages']) && empty($webhookBody['messages'])) {
    return;
}

require_once __DIR__ . '/protected/vendors/whatsapp/chatapi.class.php';

$api = new ChatApi(
    Yii::app()->params['WBotToken'],
    Yii::app()->params['WBotApiUrl']
);

foreach ($webhookBody['messages'] as $message) {
    // Игнорируем свои сообщения
    if ($message['fromMe']) {
        continue;
    }

    $body = $message['body'];
    $chatId = $message['chatId'];
    $exist = Request::model()->findByAttributes(['wbot_id' => $chatId]);
    $manager = CUsers::model()->findByAttributes(['wbot' => $chatId]);
    if(isset($manager) AND !empty($manager)){
        $role = CUsers::getRole($manager->Username);
        if($role == 'systemManager'){
            exit;       
        }
    } 

    $api->sendReadChat($chatId);
    if (trim($body) === '/getme') {
            $title = "Ваш id чата ".$chatId. " вставьте его в справочник Пользователи, если вы исполнитель и хотите получать уведомления в WhatsApp";
            $api->sendMessage($chatId, $title);
            exit;
        }

    if (!empty($exist) && mb_strtolower(trim($body)) === 'закрыть') {
        $title = 'Спасибо, вы успешно изменили обращение, оцените работу исполнителя по шкале от 1 до 5, чтобы закрыть заявку.';
        closeRequest($message, $exist);
    } elseif (empty($exist)) {
        $id = createNewRequest($message, $api);
        $title = "Ваше обращение принято! № заявки: {$id}";
    } else {
        if (in_array(trim($body), ['1', '2', '3', '4', '5'])) {
            $caseNumber = (int)trim($body);
            setRating($exist, $caseNumber, $exist->fullname);
            $title = 'Спасибо за оценку! Заявка закрыта.';
        } else {
            createNewComment($message, $exist, $api);
            $title = "Ваш комментарий к заявке №{$exist->id} принят!";
        }
    }

    $api->sendMessage($chatId, $title);
}

/**
 * @param array $message
 * @param ChatApi $api
 * @return bool|integer
 * @throws CException
 */
function createNewRequest(array $message, &$api)
{
    if ($message['type'] == 'image') {
        $content = "<img src='" . $message['body'] . "' >";
    } elseif ($message['type'] == 'document'){
        $content = "<a href='" . $message['body'] . "' >".$message['body'] ."</a>";
    } elseif ($message['type'] == 'ptt'){
        $content = '<audio preload="auto" controls><source src="'.$message['body'].'" type="audio/ogg"></audio>';
    } else {
        $content = isset($message['body']) ? $message['body'] : '';
    }

    $chat_id = $message['chatId'];
    $name = 'Whatsapp ticket';
    $fullname = $message['senderName'];
    $user = CUsers::model()->findByAttributes(['wbot' => $chat_id]);

    $status = Status::model()->findByAttributes(['close' => 1]);

    $model = new PortalRequest;
    $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
    $model->Name = $name;
    $model->channel = 'Whatsapp';
    $model->channel_icon = 'fa-brands fa-whatsapp';
    $model->ZayavCategory_id = 'Whatsapp ticket';
    $model->Content = $content;
    $model->creator = 'Whatsapp bot';
    $model->fullname = $user ? $user->fullname : $fullname;
    $model->Priority = Yii::app()->params['zdpriority'];
    if (isset($user) and !empty($user)) {
        $model->CUsers_id = $user->Username;
        $model->company = $user->company;
        $company = Companies::model()->findByAttributes(['name' => $user->company]);
        $model->company_id = $company->id ? $company->id : null;
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
    $model->wbot_id = $chat_id;
    if ($model->save(false)) {
        return $model->id;
    }

    return false;
}

/**
 * @param array $message
 * @param Request $model
 * @return bool
 * @throws Exception
 */
function closeRequest(array $message, &$model): bool
{
    $chat_id = $message['chatId'];
    $fullname = $message['senderName'];
    $user = CUsers::model()->findByAttributes(['wbot' => $chat_id]);
    $flname = $user ? $user->fullname : $fullname;
    $status = Status::model()->findByAttributes(['close' => 3]);
    Yii::log('User ' . $flname . ' updated ticket #' . $model->id . ' named "' . $model->Name . '"', 'updated',
        'UPDATED');
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
 * @param Request $model
 * @param int $rating
 * @param string $user
 */
function setRating(&$model, $rating, $user): void
{
    Request::model()->updateByPk($model->id, ['rating' => $rating, 'wbot_id' => null]);
    $history = new History();
    $history->datetime = date('d.m.Y H:i');
    $history->cusers_id = $user;
    $history->zid = $model->id;
    $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>' . $rating . '</b>';
    $history->save(false);
}

/**
 * @param array $messageW
 * @param Request $exist
 * @return bool|integer
 */
function createNewComment($messageW, &$exist)
{
    $model = new Comments;
    $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);
    $fullname = $messageW['senderName'];
    $chat_id = $messageW['chatId'];
    $user = CUsers::model()->findByAttributes(['wbot' => $chat_id]);
    if ($messageW['type'] == 'image') {
        $content = "<img src='" . $messageW['body'] . "' >";
    } elseif ($messageW['type'] == 'document'){
        $content = "<a href='" . $messageW['body'] . "' >Скачать вложение</a>";
    } elseif ($messageW['type'] == 'ptt'){
        $content = '<audio preload="auto" controls><source src="'.$messageW['body'].'" type="audio/ogg"></audio>';
    } else {
        $content = isset($messageW['body']) ? $messageW['body'] : '';
    }

    $managermail = CUsers::model()->findByAttributes(['Username' => $exist->Managers_id]);
    $subject = '[Ticket #' . $exist->id . '] ' . $exist->Name . '';
    $flname = $user ? $user->fullname : $fullname;
    $maddress = isset($managermail) ? [$managermail->Email] : '';

    $template = Messages::model()->findByAttributes(['name' => '{comments}']);
    if (isset($template)) {
        $comments = Comments::model()->findAllByAttributes(['rid' => $exist->id]);
        arsort($comments);
        $ctext = null;
        foreach ($comments as $comment) {
            $ctext .= '<blockquote>' . $comment->author . ' [' . $comment->timestamp . '] :<br/>' . $comment->comment . '<br/></blockquote>';
        }
        $comments_list = '<blockquote>' . $ctext . '<br/>' . $exist->Content . '</blockquote>';

        $reply_text = Yii::t('message', "$template->content", [
            '{author}' => $flname,
            '{date}' => date('d.m.Y H:i'),
            '{comment}' => $content,
            '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $exist->id . '">№ ' . $exist->id . '</a>',
            '{comments_list}' => $comments_list,
        ]);
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
    $model->channel = 'whatsapp';
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
        if (!isset($exist->Managers_id) and isset($exist->groups_id)) {
            $groups = Groups::model()->findByPk($exist->groups_id);
            $managers = explode(",", $groups->users);
            if (isset($managers)) {
                foreach ($managers as $manager_id) {
                    $email = CUsers::model()->findByPk($manager_id);
                    if($email->sendmail == 1) {
                        SendMail::send($email->Email, $subject, $message, null);
                    }
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
            $email = CUsers::model()->findByPk($exist->Managers_id);
            if($email->sendmail == 1) {
                SendMail::send($maddress, $subject, $message, null, null);
            }
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
