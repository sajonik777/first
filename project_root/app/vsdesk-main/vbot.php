<?php

$yii = __DIR__ . '/protected/vendors/yii/yii.php';
$config = __DIR__ . '/protected/config/main.php';
require_once($yii);
Yii::createWebApplication($config);

ini_set('display_errors', 'Off');
error_reporting(0);

if ('' == ini_get('date.timezone')) {
    date_default_timezone_set(Yii::app()->params['timezone']);
}

require_once __DIR__ . '/protected/vendors/viber/vendor/autoload.php';

use Viber\Bot;
use Viber\Api\Sender;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (1 == Yii::app()->params['VBotEnabled']) {
    $apiKey = Yii::app()->params['VBotToken'];
    $botSender = new Sender([
        'name' => 'Univef service desk bot',
        //'avatar' => 'https://developers.viber.com/images/favicon.ico',
    ]);
//    $log = new Logger('bot');
    $log = null;
//    $log->pushHandler(new StreamHandler(__DIR__ . '/protected/runtime/vbot.log'));
    $bot = null;
    try {
        $bot = new Bot(['token' => $apiKey]);
        $bot
            ->onConversation(function ($event) use ($bot, $botSender, $log) {
//                $log->info('onConversation ' . var_export($event, true));
                return (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setText(Yii::app()->params['VBotMsg']);
            })
            ->onText('|k\d+|is', function ($event) use ($bot, $botSender, $log) {
                $caseNumber = (int)preg_replace('|[^0-9]|s', '', $event->getMessage()->getText());
//                $log->info('rating ' . $caseNumber);
                $client = $bot->getClient();
                $receiverId = $event->getSender()->getId();
                $model = Request::model()->findByAttributes(['viber_id' => $receiverId]);
                if ($model) {
                    $msg = 'Спасибо за оценку! Заявка закрыта.';
                } else {
                    $msg = 'Извините, заявка была удалена или закрыта.';
                }
                $user = $model->fullname;
                setRating($model, $caseNumber, $user);
                $client->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText($msg)
                );
            })
            ->onText('|close-btn|s', function ($event) use ($bot, $botSender, $log) {
//                $log->info('click on close');
                $chat_id = $event->getSender()->getId();
                $exist = Request::model()->findByAttributes(['viber_id' => $chat_id]);
                $manager = CUsers::model()->findByAttributes(['vbot' => $chat_id]);
                    if(isset($manager)){
                        $role = CUsers::getRole($manager->Username);
                        if($role == 'systemManager'){
                            exit;       
                        }
                    } 
                closeRequest($event, $exist, $log);
                $buttons = [];
                for ($i = 1; $i <= 5; $i++) {
                    $buttons[] =
                        (new \Viber\Api\Keyboard\Button())
                            ->setColumns(1)
                            ->setActionType('reply')
                            ->setActionBody('k' . $i)
                            ->setText($i);
                }
                $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setReceiver($event->getSender()->getId())
                    ->setText('Спасибо, вы успешно изменили обращение, оцените работу исполнителя, чтобы закрыть заявку')
                    ->setKeyboard(
                        (new \Viber\Api\Keyboard())
                            ->setButtons($buttons)
                    ));

            })
            ->onText('/getme', function ($event) use ($bot, $botSender, $log) {
                $bot->getClient()->sendMessage((new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setReceiver($event->getSender()->getId())
                    ->setText("Ваш id чата ".$event->getSender()->getId(). " вставьте его в справочник Пользователи, если вы исполнитель и хотите получать уведомления в Viber"));

            })
            ->onText('|.*|s', function ($event) use ($bot, $botSender, $log) {
                $chat_id = $event->getSender()->getId();
                $exist = Request::model()->findByAttributes(['viber_id' => $chat_id]);
                $manager = CUsers::model()->findByAttributes(['vbot' => $chat_id]);
                    if(isset($manager)){
                        $role = CUsers::getRole($manager->Username);
                        if($role == 'systemManager'){
                            exit;       
                        }
                    } 
                if (empty($exist)) {
                    $id = createNewRequest($event);
                    $msg = "Ваше обращение принято! № заявки: {$id}";
                } else {
                    createNewComment($event, $exist);
                    $msg = "Ваш комментарий к заявке №{$exist->id} принят!";
                }
//                $log->info('onText ' . var_export($event, true));
                $bot->getClient()->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($event->getSender()->getId())
                        ->setText($msg)->setKeyboard(
                            (new \Viber\Api\Keyboard())
                                ->setButtons([
                                    (new \Viber\Api\Keyboard\Button())
                                        ->setActionType('reply')
                                        ->setActionBody('close-btn')
                                        ->setText('Завершить заявку')
                                ])
                        )
                );
            })
            ->onPicture(function ($event) use ($bot, $botSender, $log) {
                $chat_id = $event->getSender()->getId();
                $exist = Request::model()->findByAttributes(['viber_id' => $chat_id]);
                $manager = CUsers::model()->findByAttributes(['vbot' => $chat_id]);
                    if(isset($manager)){
                        $role = CUsers::getRole($manager->Username);
                        if($role == 'systemManager'){
                            exit;       
                        }
                    } 

                if (null === $exist) {
                    $id = createNewRequest($event);
                    $msg = "Ваше обращение принято! № заявки: {$id}";
                } else {
                    createNewComment($event, $exist);
                    $msg = "Ваш комментарий к заявке №{$exist->id} принят!";
                }
//                $log->info('onText ' . var_export($event, true));
                $bot->getClient()->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($event->getSender()->getId())
                        ->setText($msg)->setKeyboard(
                            (new \Viber\Api\Keyboard())
                                ->setButtons([
                                    (new \Viber\Api\Keyboard\Button())
                                        ->setActionType('reply')
                                        ->setActionBody('close-btn')
                                        ->setText('Завершить заявку')
                                ])
                        )
                );
            })
            ->run();
    } catch (Exception $e) {
//        $log->warning('Exception: ' . $e->getMessage());
//        if ($bot) {
//            $log->warning('Actual sign: ' . $bot->getSignHeaderValue());
//            $log->warning('Actual body: ' . $bot->getInputBody());
//        }
    }
}

/**
 * @param \Viber\Api\Event\Message $event
 * @param $model
 * @return mixed
 */
function closeRequest(\Viber\Api\Event\Message $event, &$model, $log)
{
    $fullname = $event->getSender()->getName();
    $user = CUsers::model()->findByAttributes(array('vbot' => $event->getSender()->getId()));
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
    Request::model()->updateByPk($model->id, array('rating' => $rating, 'viber_id' => null));
    $history = new History();
    $history->datetime = date('d.m.Y H:i');
    $history->cusers_id = $user;
    $history->zid = $model->id;
    $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>'.$rating.'</b>';
    $history->save(false);
}

/**
 * @param \Viber\Api\Event\Message $event
 * @param $exist
 * @return bool|integer
 */
function createNewComment(\Viber\Api\Event\Message $event, &$exist)
{
    $message = $event->getMessage();
    $model = new Comments;
    $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);
    $fullname = $event->getSender()->getName();
    $user = CUsers::model()->findByAttributes(array('vbot' => $event->getSender()->getId()));
    $content = null;
    if ($message instanceof \Viber\Api\Message\Text) {
        $content = $message->getText();
    } elseif ($message instanceof \Viber\Api\Message\Picture) {
        $file = $message->getMedia();
        $content = "<img src='$file' >";
    }
    $managermail = CUsers::model()->findByAttributes(['Username' => $exist->Managers_id]);
    $subject = '[Ticket #' . $exist->id . '] ' . $exist->Name . '';
    $flname = $user ? $user->fullname : $fullname;
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
    $model->channel = 'viber';
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
                SendMail::send($maddress, $subject, $message, null);
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

/**
 * @param \Viber\Api\Event\Message $event
 * @return bool|integer
 */
function createNewRequest(\Viber\Api\Event\Message $event)
{
    $message = $event->getMessage();
    $chat_id = $event->getSender()->getId();
    $name = 'Viber ticket';
    $content = null;
    if ($message instanceof \Viber\Api\Message\Text) {
        $content = $message->getText();
    } elseif ($message instanceof \Viber\Api\Message\Picture) {
        $file = $message->getMedia();
        $content = "<img src='$file' >";
    }
    $fullname = $event->getSender()->getName();
    $user = CUsers::model()->findByAttributes(array('vbot' => $chat_id));

    $status = Status::model()->findByAttributes(['close' => 1]);

    $model = new PortalRequest;
    $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
    $model->Name = $name;
    $model->channel = 'Viber';
    $model->channel_icon = 'fa-brands fa-viber';
    $model->ZayavCategory_id = 'Viber ticket';
    $model->Content = $content;
    $model->creator = 'Viber bot';
    $model->fullname = $user ? $user->fullname : $fullname;
    if(isset($user) AND !empty($user)){
        $model->CUsers_id = $user->Username;
        $model->company = $user->company;
        $company = Companies::model()->findByAttributes(['name' => $user->company]);
        $model->company_id = $company->id ? $company->id: NULL;
        $depart = Depart::model()->findByAttributes(['name' => $user->department, 'company' => $user->company]);
        $model->depart_id = $depart->id;
    }
    $model->Priority = Yii::app()->params['zdpriority'];
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
    $model->viber_id = $chat_id;
    if ($model->save(false)) {
        return $model->id;
    }

    return false;
}
