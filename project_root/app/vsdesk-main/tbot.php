<?php

    //YII setup
    $yii=dirname(__FILE__).'/protected/vendors/yii/yii.php';
    $config=dirname(__FILE__).'/protected/config/main.php';
    require_once($yii);
    Yii::createWebApplication($config);

    //display_errors off
    ini_set('display_errors', 'Off');
    error_reporting(0);

    //settings for timezone
if (ini_get('date.timezone') == '') {
    date_default_timezone_set(Yii::app()->params['timezone']);
}

    require __DIR__ . '/protected/vendors/telegram/autoload.php';

    use Telegram\Bot\Api;

if (Yii::app()->params['TBotEnabled'] == 1) {
    $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
    $result = $telegram->getWebhookUpdates(); //get Message data on update
        
    $text = $result["message"]["text"]; //Текст сообщения
    $photo = $result["message"]["photo"]; //Картинка в сообщении
    $doc = $result["message"]["document"]; //Картинка в сообщении
    $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
    $name = $result["message"]["from"]["username"]; //Юзернейм пользователя
    $keyboard = [["Техподдержка"],["Документация"],["Сайт"]]; //Клавиатура
    $fullname = $result["message"]["from"]["first_name"].' '.$result["message"]["from"]["last_name"]; //полное имя
    
    //$telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => implode(',',$result["message"])]);
    $manager = CUsers::model()->findByAttributes(['tbot' => $chat_id]);
    if(isset($manager)){
        $role = CUsers::getRole($manager->Username);
        if($role == 'systemManager'){
            exit;       
        }
    } 
    
    
    if ($text or $photo or $doc) {
        if ($text == "/start") {
            $reply = Yii::app()->params['TBotMsg'];
           //$reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
        } elseif ($text == "/getme") {
            $reply = "Ваш id чата ".$chat_id. " вставьте его в справочник Пользователи, если вы исполнитель и хотите получать уведомления в Telegram";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]); 
        } elseif ($text == "/help") {
            $reply = "https://help.univef.ru";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        } elseif ($text == "Техподдержка") {
            $reply = "https://support.univef.ru";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        } elseif ($text == "Документация") {
            $reply = "https://help.univef.ru";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        } elseif ($text == "Сайт") {
            $reply = "https://univef.ru";
            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply ]);
        } elseif ($text == "5") {
            //5 баллов оценка
            $model = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (isset($model)) {
                Request::model()->updateByPk($model->id, array('rating' => '5', 'tchat_id' => null));
                $history = new History();
                $history->datetime = date("d.m.Y H:i");
                $history->cusers_id = $fullname;
                $history->zid = $model->id;
                $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>5</b>';
                $history->save(false);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Спасибо за оценку! Заявка закрыта.']);
            } else {
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Извините, заявка была удалена или закрыта.']);
            }
        } elseif ($text == "4") {
            //4 балла оценка
            $model = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (isset($model)) {
                Request::model()->updateByPk($model->id, array('rating' => '4', 'tchat_id' => null));
                $history = new History();
                $history->datetime = date("d.m.Y H:i");
                $history->cusers_id = $fullname;
                $history->zid = $model->id;
                $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>4</b>';
                $history->save(false);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Спасибо за оценку! Заявка закрыта.']);
            } else {
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Извините, заявка была удалена или закрыта.']);
            }
        } elseif ($text == "3") {
            //3 балла оценка
            $model = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (isset($model)) {
                Request::model()->updateByPk($model->id, array('rating' => '3', 'tchat_id' => null));
                $history = new History();
                $history->datetime = date("d.m.Y H:i");
                $history->cusers_id = $fullname;
                $history->zid = $model->id;
                $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>3</b>';
                $history->save(false);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Спасибо за оценку! Заявка закрыта.']);
            } else {
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Извините, заявка была удалена или закрыта.']);
            }
        } elseif ($text == "2") {
            //2 балла оценка
            $model = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (isset($model)) {
                Request::model()->updateByPk($model->id, array('rating' => '2', 'tchat_id' => null));
                $history = new History();
                $history->datetime = date("d.m.Y H:i");
                $history->cusers_id = $fullname;
                $history->zid = $model->id;
                $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>2</b>';
                $history->save(false);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Спасибо за оценку! Заявка закрыта.']);
            } else {
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Извините, заявка была удалена или закрыта.']);
            }
        } elseif ($text == "1") {
            //1 балл оценка
            $model = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (isset($model)) {
                Request::model()->updateByPk($model->id, array('rating' => '1', 'tchat_id' => null));
                $history = new History();
                $history->datetime = date("d.m.Y H:i");
                $history->cusers_id = $fullname;
                $history->zid = $model->id;
                $history->action = Yii::t('main-ui', 'Request rated to: ') . '<b>1</b>';
                $history->save(false);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Спасибо за оценку! Заявка закрыта.']);
            } else {
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Извините, заявка была удалена или закрыта.']);
            }
        } elseif ($text == "Завершить заявку") {
            //here closing the ticket function
            $fullname = $result["message"]["from"]["first_name"].' '.$result["message"]["from"]["last_name"];
            $user = CUsers::model()->findByAttributes(array('tbot' => $chat_id));
            $model = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (isset($model)) {
                //$mngr = CUsers::model()->findByAttributes(array('Username' => $model->Managers_id));
                $status = Status::model()->findByAttributes(['close' => 3]);
                $message = 'User ' . $fullname . ' updated ticket #' . $model->id . ' named "' . $model->Name . '"';
                Yii::log($message, 'updated', 'UPDATED');
                unset($_POST['Requestt']);
                if (isset($model->gfullname)) {
                    $_POST['Requestt']['gfullname'] = $model->gfullname;
                }
                if (isset($model->Managers_id)) {
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
                $_POST['Requestt']['fEndTime'] = date("d.m.Y H:i");
                $_POST['Requestt']['timestampfEnd'] = date("Y-m-d H:i:s");


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
                    //$this->lead_time = $lead_time->format("%d:%h:%i:%s");

                    list($lt_d, $lt_h, $lt_i, $lt_s) = explode(':', $lead_time->format("%d:%h:%i:%s"));
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
                        $_POST['Requestt']['lead_time'] = $lead_time->format("%h:%i:%s");
                    } else {
                        $_POST['Requestt']['lead_time'] = $lead_time->format("%h:%i:%s");
                    }

                    $_POST['Requestt']['timestampfStart'] = date("Y-m-d H:i:s");


                    $_POST['Requestt']['closed'] = 3;//этот параметр означает закрытие заявки, дальше его небудет обрабатывать CRON и сверять дедлайны
                    if ($model->fStartTime == null) {
                        $_POST['Requestt']['fStartTime'] = date("d.m.Y H:i");
                        $history = new History();
                        $history->datetime = date("d.m.Y H:i");
                        $history->cusers_id = $user ? $user->fullname : $fullname;
                        $history->zid = $model->id;
                        $history->action = Yii::t('main-ui', 'Fact start time is set to: ') . '<b>' . date("d.m.Y H:i") . '</b>';
                        $history->save(false);
                    }
                        $history = new History();
                        $history->datetime = date("d.m.Y H:i");
                        $history->cusers_id = $user ? $user->fullname : $fullname;
                        $history->zid = $model->id;
                        $history->action = Yii::t('main-ui', 'Fact end time is set to: ') . '<b>' . date("d.m.Y H:i") . '</b>';
                        $history->save(false);

                    
                $model->attributes = $_POST['Requestt'];
                $model->Comment = null;
                if ($model->save(false)) {
                    $reply = "Спасибо, вы успешно изменили обращение, оцените работу исполнителя, чтобы закрыть заявку";
                    $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => [["5"],["4"],["3"],["2"],["1"]], 'resize_keyboard' => true, 'one_time_keyboard' => true]);
                    $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply, 'reply_markup'=>$reply_markup]);
                }
            } else {
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => 'Извините, заявка была удалена или закрыта.']);
            }
        } else {
            $exist = Request::model()->findByAttributes(array('tchat_id' => $chat_id));
            if (!isset($exist)) {
                $name = 'Telegram ticket';
                $content = null;
                if ($text) {
                    $content = $result["message"]["text"];
                } elseif ($photo) {
                    if(isset($result["message"]["photo"][3])){
                        $file = $result["message"]["photo"][3]["file_id"];
                    }
                    if(!isset($result["message"]["photo"][3]) AND isset($result["message"]["photo"][2])){
                        $file = $result["message"]["photo"][2]["file_id"];
                    }
                    if(!isset($result["message"]["photo"][3]) AND !isset($result["message"]["photo"][2]) AND isset($result["message"]["photo"][1]) ){
                        $file = $result["message"]["photo"][1]["file_id"];
                    }
                    if(!isset($result["message"]["photo"][3]) AND !isset($result["message"]["photo"][2]) AND !isset($result["message"]["photo"][1]) ){
                        $file = $result["message"]["photo"][0]["file_id"];
                    }
                    $fl = $telegram->getFile(['file_id' => $file]);
                    $content = "<img src='https://api.telegram.org/file/bot".Yii::app()->params['TBotToken']."/".$fl['file_path']."' />";
                } elseif ($doc) {
                    $file = $result["message"]["document"]["file_name"];
                    $url = $result["message"]["document"]["file_id"];
                    $fl = $telegram->getFile(['file_id' => $url]);
                    $content = "<a href='https://api.telegram.org/file/bot".Yii::app()->params['TBotToken']."/".$fl['file_path']."'>Скачать вложение ".$file . "</a>";
                }
                $fullname = $result["message"]["from"]["first_name"].' '.$result["message"]["from"]["last_name"];
                    
                $status = Status::model()->findByAttributes(array('close' => 1));
                $user = CUsers::model()->findByAttributes(array('tbot' => $chat_id));
        
                $model = new PortalRequest;
                $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
                $model->Name = $name;
                $model->channel = 'Telegram';
                $model->channel_icon = 'fa-brands fa-telegram';
                $model->ZayavCategory_id = 'Telegram ticket';
                $model->Content = $content;
                $model->creator = $user ? $user->fullname : 'Telegram bot';
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
                if (Yii::app()->params['zdtype'] == 1) {
                    $model->Managers_id = Yii::app()->params['zdmanager'];
                    $manager = CUsers::model()->findByAttributes(array('Username' => Yii::app()->params['zdmanager']));
                } else {
                    $model->gfullname = Yii::app()->params['zdmanager'];
                    $group = Groups::model()->findByAttributes(array('name' => Yii::app()->params['zdmanager']));
                    $model->groups_id = $group->id;
                }
                $model->Type = '';
                $model->mfullname = $manager->fullname ? $manager->fullname : null;
                $model->Date = date("d.m.Y H:i");
                $model->timestamp = date('Y-m-d H:i:s');
                $model->cunits = '';
                $model->Status = $status->name;
                $model->slabel = $status->label;
                $model->tchat_id = $chat_id;
                if ($model->save(false)) {
                    $reply = "Ваше обращение принято! № заявки: ".$model->id;
                    $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => [["Завершить заявку"]], 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                    $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply, 'reply_markup'=>$reply_markup]);
                }
            } else {
                $model = new Comments;
                $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);
                $fullname = $result["message"]["from"]["first_name"].' '.$result["message"]["from"]["last_name"];
                $user = CUsers::model()->findByAttributes(array('tbot' => $chat_id));
                $content = null;
                if ($text) {
                    $content = $result["message"]["text"];
                } elseif ($photo) {
                    if(isset($result["message"]["photo"][3])){
                        $file = $result["message"]["photo"][3]["file_id"];
                    }
                    if(!isset($result["message"]["photo"][3]) AND isset($result["message"]["photo"][2])){
                        $file = $result["message"]["photo"][2]["file_id"];
                    }
                    if(!isset($result["message"]["photo"][3]) AND !isset($result["message"]["photo"][2]) AND isset($result["message"]["photo"][1]) ){
                        $file = $result["message"]["photo"][1]["file_id"];
                    }
                    if(!isset($result["message"]["photo"][3]) AND !isset($result["message"]["photo"][2]) AND !isset($result["message"]["photo"][1]) ){
                        $file = $result["message"]["photo"][0]["file_id"];
                    }
                    $fl = $telegram->getFile(['file_id' => $file]);
                    $content = "<img src='https://api.telegram.org/file/bot".Yii::app()->params['TBotToken']."/".$fl['file_path']."' />";
                } elseif ($doc) {
                    $file = $result["message"]["document"]["file_name"];
                    $url = $result["message"]["document"]["file_id"];
                    $fl = $telegram->getFile(['file_id' => $url]);
                    $content = "<a href='https://api.telegram.org/file/bot".Yii::app()->params['TBotToken']."/".$fl['file_path']."'>Скачать вложение ".$file . "</a>";
                }
                $managermail = CUsers::model()->findByAttributes(array('Username' => $exist->Managers_id));
                $subject = '[Ticket #' . $exist->id . '] ' . $exist->Name . '';
                $maddress = isset($managermail) ? array($managermail->Email) : '';
                $ffname = $user ? $user->fullname : $fullname;

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
                        '{author}' => $ffname,
                        '{date}' => date('d.m.Y H:i'),
                        '{comment}' => $content,
                        '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $exist->id. '">№ ' . $exist->id. '</a>',
                        '{comments_list}' => $comments_list,
                    ));
                } else {
                    $reply_text = '<b>Добавлен новый комментарий</b><br>'. $ffname . ' [' . date('d.m.Y H:i') . ']  :<br/>'  . $content. '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl.'/request/'.$exist->id.'">№ '.$exist->id.'</a>';
                    $comments = Comments::model()->findAllByAttributes(array('rid' => $exist->id));
                    arsort($comments);
                    $ctext = null;
                    foreach ($comments as $comment) {
                        $ctext .= '<blockquote>' . $comment->author . ' [' . $comment->timestamp . '] :<br/>' . $comment->comment . '<br/></blockquote>';
                    }
                    $reply_text .= '<blockquote>' . $ctext . '<br/>' . $exist->Content . '</blockquote>';
                }

                $message = $reply_text;
                $model->rid = $exist->id;
                $model->channel = 'telegram';
                $model->timestamp = date('d.m.Y H:i:s');
                $model->author = $user ? $user->fullname : $fullname;
                //$model->readership = $fullname->id;
                $model->comment = $content;
                if ($model->save(false)) {
                    $exist->updateByPk($exist->id, array('lastactivity'=>date("Y-m-d H:i:s")));
                        $history = new History();
                        $history->datetime = date("d.m.Y H:i");
                        $history->cusers_id = $user ? $user->fullname : $fullname;
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
                                $pmessage = "[Ticket #".$exist->id."]\r\nБыл добавлен комментарий:\r\n ". trim(strip_tags($content));
                                $url = Yii::app()->params['homeUrl'] . "/request/".$exist->id;
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
                        $pmessage = "[Ticket #".$exist->id."]\r\nБыл добавлен комментарий:\r\n ". trim(strip_tags($content));
                        $url = Yii::app()->params['homeUrl'] . "/request/".$exist->id;
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
                        //var_dump($watchers);
                        foreach ($watchers as $key => $watcher) {
                            $witem = CUsers::model()->findByAttributes(array('fullname'=>$watcher));
                            if ($witem->sendmail == 1 and $witem->Email !== null) {
                                $wmail[] = $witem->Email;
                            }
                        }
                        SendMail::send($wmail, $subject, $message, null);
                    }
                    $reply = "Ваш комментарий к заявке №".$exist->id ." принят!";
                    $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => [["Завершить заявку"]], 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                    $telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply, 'reply_markup'=>$reply_markup]);
                }
            }
        }
    } else {
        if(isset($chat_id) AND !empty($chat_id))
        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => "Отправьте текстовое сообщение." ]);
    }
}
