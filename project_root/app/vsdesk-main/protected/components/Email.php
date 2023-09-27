<?php

require __DIR__ . '/../vendors/phpmailer/vendor/autoload.php';
require __DIR__ . '/../vendors/telegram/autoload.php';
require_once __DIR__ . '/../vendors/viber/vendor/autoload.php';
require_once __DIR__ . '/../vendors/whatsapp/chatapi.class.php';

use Telegram\Bot\Api;
use Viber\Bot;
use Viber\Api\Sender;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class EMail
 */
class EMail
{
    /**
     * @var CUsers|null
     */
    public static $_user;

    /**
     * EMail constructor.
     */
    public function __construct()
    {
        if (ini_get('date.timezone') == '') {
            date_default_timezone_set(Yii::app()->params['timezone']);
        }
    }

    /**
     * @param $id
     * @param $key
     * @param $afiles
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    static function prepare($id, $key, $afiles)
    {
        if ($key == 2 or $key == 3) {
            $model = Request::model()->findByPk($id);
            $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
            $address = $manager->Email;
            if ($key == 2) {
                $status_temp = Status::model()->findByAttributes(['close' => '4']);
            } elseif ($key == 3) {
                $status_temp = Status::model()->findByAttributes(['close' => '5']);
            }

            if ($status_temp->notify_manager == 1) {
                if (isset($manager) and $manager->sendmail == 1) {
                    $manager_address = $address;

                    $message = Messages::model()->findByAttributes(['name' => $status_temp->mwmessage]);
                    $subject = $message->subject;

                    Email::Mailsend($manager_address, $subject, $manager, $message, $model, $afiles);
                }
            }
            if ($status_temp->notify_manager_sms == 1) {
                if (isset($manager) and $manager->sendsms == 1) {
                    $managernum = $manager->mobile ? $manager->mobile : $manager->Phone;
                    $sms = Smss::model()->findByAttributes(['name' => $status_temp->mwsms]);
                    Email::Smssend($managernum, $manager, $sms, $model);
                }
            }
            return;
        }
        $model = Request::model()->findByPk($id);
        $service_temp = Service::model()->findByPk($model->service_id);
        $user = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
        $watchers = explode(',', $model->watchers);
        if (($service_temp and $service_temp->gtype == 1)) {
            $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
            $address = $manager->Email;
        } else {
            $address = array();
            $manager = array();
            if ($service_temp and $model->Managers_id == null) { //если задан сервис и не задан конечный исполнитель
                $manager = array('fullname' => $service_temp->group, 'Phone' => '', 'Email' => '');
                //$groups = Groups::model()->findByAttributes(array('name' => $service_temp->group));
                $groups = Groups::model()->findByPk($model->groups_id); //заменено для тестирования в связи с ошибкой переназначенных групп
                $managers = explode(',', $groups->users);
            } elseif ($service_temp and $model->Managers_id !== null) { //если задан сервис и задан конечный исполнитель
                $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
                $address = $manager->Email;
            } else { //если не задан сервис
                if ($model->Managers_id == null and $model->groups_id == null) { //и не задан ни исполнитель, ни группа
                    if (Yii::app()->params['zdtype'] == 1) {
                        $manager = CUsers::model()->findByAttributes(['Username' => Yii::app()->params['zdmanager']]);
                        $address = $manager->Email;
                    } else {
                        $groups = Groups::model()->findByAttributes(['name' => Yii::app()->params['zdmanager']]);
                        $managers = explode(',', $groups->users);
                    }
                } elseif ($model->Managers_id == null and $model->groups_id !== null) { //если н задан исполнитель, но задана группа
                    $groups = Groups::model()->findByPk($model->groups_id);
                    $managers = explode(',', $groups->users);
                } elseif ($model->Managers_id !== null) { //если задан конечный исполнитель
                    $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
                    $address = $manager->Email;
                }

            }
        }
        $status_temp = Status::model()->findByAttributes(['name' => $model->Status]);

        // Отправка уведомлений согласователям
        if ($status_temp->close == 7 and !empty($service_temp) and $status_temp->notify_matching == 1) {

            $message = Messages::model()->findByAttributes(['name' => $status_temp->matching_message]);

            /** @var $model Request */
            foreach ($model->getMatchingIds() as $userId) {

                $params = [':request_id' => $model->id];
                $iteration = (int)yii::app()->db
                    ->createCommand('select max(iteration) from request_matching_reaction where request_id = :request_id')
                    ->queryScalar($params);

                if ($iteration === 1) {
                    $email = CUsers::model()->findByPk($userId);
                    Email::Mailsend($email->Email, $message->subject, $manager, $message, $model, $afiles);
                } else {
                    $params = [':request_id' => $model->id, ':user_id' => $userId];
                    $sql = 'select * from request_matching_reaction where request_id = :request_id AND user_id = :user_id ORDER BY id DESC LIMIT 1';
                    $rmrOld = RequestMatchingReaction::model()->findBySql($sql, $params);

                    if ($rmrOld && $rmrOld->checked == 0) {
                        $email = CUsers::model()->findByPk($userId);
                        Email::Mailsend($email->Email, $message->subject, $manager, $message, $model, $afiles);
                    }
                }

            }

        }

        if ($status_temp->notify_group == 1) {
            if ($watchers[0] !== '') {
                $waddress = [];
                foreach ($watchers as $watcher) {
                    $email = CUsers::model()->findByAttributes(['fullname' => $watcher]);
                    if ($email->sendmail == 1) {
                        $waddress[] = $email->Email;
                    }
                }
                $message = Messages::model()->findByAttributes(['name' => $status_temp->gmessage]);
                $subject = $message->subject;
                Email::Mailsend($waddress, $subject, $manager, $message, $model, $afiles);
            }
        }

        //Проверка в модели Status у текущего статуса заявки уведомлять ли пользователя
        if ($status_temp->notify_user == 1) {
            //Если у пользователя в профиле установлен переключатель Уведомлять по email
            if (isset($user) and $user->sendmail == 1) {
                $user_address = $user->Email;
                $message = Messages::model()->findByAttributes(['name' => $status_temp->message]);
                $subject = $message->subject;
                Email::Mailsend($user_address, $subject, $manager, $message, $model, $afiles);
            }
            if ($model->channel === 'Email' and $model->CUsers_id == null) {
                $user_address = $model->fullname;
                $message = Messages::model()->findByAttributes(['name' => $status_temp->message]);
                $subject = $message->subject;
                Email::Mailsend($user_address, $subject, $manager, $message, $model, $afiles);
            }
            if ($model->ZayavCategory_id == Yii::t('main-ui', 'Portal ticket') and $model->CUsers_id == null) {
                $user_address = $model->fullname;
                $message = Messages::model()->findByAttributes(['name' => $status_temp->message]);
                $subject = $message->subject;
                Email::Mailsend($user_address, $subject, $manager, $message, $model, $afiles);
            }
            if ($model->ZayavCategory_id == Yii::t('main-ui', 'Widget ticket') and $model->CUsers_id == null) {
                $user_address = $model->fullname;
                $message = Messages::model()->findByAttributes(['name' => $status_temp->message]);
                $subject = $message->subject;
                Email::Mailsend($user_address, $subject, $manager, $message, $model, $afiles);
            }
        }
        if ($status_temp->notify_user_sms == 1) {
            //Если у пользователя в профиле установлен переключатель Уведомлять по SMS
            if (isset($user) and $user->sendsms == 1) {
                $usernum = $user->mobile ? $user->mobile : $user->Phone;
                $sms = Smss::model()->findByAttributes(array('name' => $status_temp->sms));
                Email::Smssend($usernum, $manager, $sms, $model);
            }
        }
        //Проверка в модели Status у текущего статуса заявки уведомлять ли исполнителя
        if ($status_temp->notify_manager == 1) {
            //Если у пользователя в профиле установлен переключатель Уведомлять по email
            if (($service_temp and $service_temp->gtype == 1)) {
                if (isset($manager) and $manager->sendmail == 1) {
                    $manager_address = $address;
                    $message = Messages::model()->findByAttributes(['name' => $status_temp->mmessage]);
                    $subject = $message->subject;
                    Email::Mailsend($manager_address, $subject, $manager, $message, $model, $afiles);
                }
            } else {
                if (isset($managers)) {

                    $group = Groups::model()->findByPk($model->groups_id);
                    // Проверяем уведомлять на единый email группы или каждого частника
                    if ($group && $group->send && $group->email) {
                        $manager_addres = $group->email;
                        $message = Messages::model()->findByAttributes(['name' => $status_temp->mmessage]);
                        $subject = $message->subject;
                        Email::Mailsend($manager_addres, $subject, $manager, $message, $model, $afiles);
                    } else {
                        foreach ($managers as $manager) {
                            $email = CUsers::model()->findByPk($manager);
                            if ($email->sendmail == 1) {
                                $address[] = $email->Email;
                            }
                        }

                        $manager_address = $address;
                        $message = Messages::model()->findByAttributes(['name' => $status_temp->mmessage]);
                        $subject = $message->subject;
                        Email::Mailsend($manager_address, $subject, $manager, $message, $model, $afiles);
                    }
                } elseif (isset($manager) and $manager->sendmail == 1) {
                    $manager_address = $address;
                    $message = Messages::model()->findByAttributes(['name' => $status_temp->mmessage]);
                    $subject = $message->subject;
                    Email::Mailsend($manager_address, $subject, $manager, $message, $model, $afiles);
                }
            }
        }
        if ($status_temp->notify_manager_sms == 1) {
            if (isset($manager) and $manager->sendsms == 1) {
                $managernum = $manager->mobile ? $manager->mobile : $manager->Phone;
                $sms = Smss::model()->findByAttributes(array('name' => $status_temp->msms));
                Email::Smssend($managernum, $manager, $sms, $model);
            } elseif (isset($managers)) {
                foreach ($managers as $manager) {
                    $phone = CUsers::model()->findByPk($manager);
                    if ($phone->sendsms == 1) {
                        $sms = Smss::model()->findByAttributes(array('name' => $status_temp->msms));
                        Email::Smssend($phone->mobile ? $phone->mobile : $phone->Phone, $phone, $sms, $model);
                    }
                }
            }
        }

        /* GOOGLE PUSH */
        if (true) {
            $is_console = PHP_SAPI === 'cli'; //if is console app return bool
            if ($key == 1 and isset($service_temp) and $service_temp->gtype == 1) {
                if (!$is_console) {
                    if (Yii::app()->user->name !== $manager->Username) {
                        self::sendGooglePush($manager->Username, $id);
                    }
                } else {
                    self::sendGooglePush($manager->Username, $id);
                }
            } else {
                if (!$is_console) {
                    if (Yii::app()->user->name !== $user->Username) {
                        self::sendGooglePush($user->Username, $id);
                    }
                } else {
                    if ($user) {
                        self::sendGooglePush($user->Username, $id);
                    }
                }
                if (isset($managers)) {
                    foreach ($managers as $manager_id) {
                        $email = CUsers::model()->findByPk($manager_id);
                        if ($is_console) {
                            self::sendGooglePush($email->Username, $id);
                        } else {
                            if (Yii::app()->user->name !== $email->Username) {
                                self::sendGooglePush($email->Username, $id);
                            }
                        }
                    }
                } elseif (!$is_console and $key == 1 and Yii::app()->user->checkAccess('liteformRequest')) {
                    $manager = CUsers::model()->findByAttributes(['Username' => Yii::app()->params['zdmanager']]);
                    self::sendGooglePush($manager->Username, $id);
                } else {
                    if ($is_console) {
                        self::sendGooglePush($manager->Username, $id);
                    } else {
                        if (Yii::app()->user->name !== $manager->Username) {
                            self::sendGooglePush($manager->Username, $id);
                        }
                    }
                }
            }
        }
        /* GOOGLE PUSH end */

        /* TELEGRAM SEND */
        if (isset($model->tchat_id) and !empty($model->tchat_id) and Yii::app()->params['TBotEnabled'] == 1) {
            $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
            if ($status_temp->notify_user_sms == 1) {
                $message = Smss::model()->findByAttributes(['name' => $status_temp->sms]);
                $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
                $umessage = Email::MessageGen($message->content, $manager, $model);
                if ($status_temp->close !== '3') {
                    $telegram->sendMessage([
                        'chat_id' => $model->tchat_id,
                        'parse_mode' => 'HTML',
                        'text' => mb_strimwidth(strip_tags($umessage, '<i><a><b><code><pre><strong><em>'), 0, 4000, "...")
                    ]);
                } else {
                    $reply_markup = $telegram->replyKeyboardMarkup([
                        'keyboard' => [['5'], ['4'], ['3'], ['2'], ['1']],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);
                    $telegram->sendMessage([
                        'chat_id' => $model->tchat_id,
                        'parse_mode' => 'HTML',
                        'text' => strip_tags($umessage, '<i><a><b><code><pre><strong><em>'),
                        'reply_markup' => $reply_markup
                    ]);
                }
            }
        }
        /* END TELEGRAM SEND */

        /* VIBER SEND */
        if (isset($model->viber_id) and !empty($model->viber_id) and Yii::app()->params['VBotEnabled'] == 1) {
            $apiKey = Yii::app()->params['VBotToken'];
            $bot = new Bot(['token' => $apiKey]);
            $botSender = new Sender([
                'name' => 'Univef service desk bot',
            ]);
            if ($status_temp->notify_user_sms == 1) {
                $message = Smss::model()->findByAttributes(['name' => $status_temp->sms]);
                $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
                $umessage = Email::MessageGen($message->content, $manager, $model);
                if ($status_temp->close !== '3') {
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($model->viber_id)
                            ->setText(strip_tags($umessage, '<i><a><b><code><pre><strong><em>'))
                    );
                } else {
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
                        ->setReceiver($model->viber_id)
                        ->setText(strip_tags($umessage, '<i><a><b><code><pre><strong><em>'))
                        ->setKeyboard(
                            (new \Viber\Api\Keyboard())
                                ->setButtons($buttons)
                        ));
                }
            }
        }
        /* END VIBER SEND */

        /* MSBOT SEND */
        if (isset($model->msbot_id) and !empty($model->msbot_id) and Yii::app()->params['MSBotEnabled'] == 1) {

            $microsoftBot = new MicrosoftBotFramework(Yii::app()->params['MSBotAppId'],
                Yii::app()->params['MSBotAppPassword']);

            if ($status_temp->notify_user_sms == 1) {
                $message = Smss::model()->findByAttributes(['name' => $status_temp->sms]);
                $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
                $umessage = Email::MessageGen($message->content, $manager, $model);
                if ($status_temp->close !== '3') {
                    $microsoftBot->sendMessage(strip_tags($umessage), json_decode($model->msbot_params, true));
                } else {
                    $buttons = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $buttons[] = [
                            'title' => "$i",
                            'type' => 'imBack',
                            'value' => "$i",
                        ];
                    }
                    $microsoftBot->sendMessage(strip_tags($umessage), json_decode($model->msbot_params, true),
                        $buttons);
                }
            }
        }
        /* END MSBOT SEND */

        /* WBOT SEND */
        if (isset($model->wbot_id) and !empty($model->wbot_id) and Yii::app()->params['WBotEnabled'] == 1) {
            $api = new ChatApi(
                Yii::app()->params['WBotToken'],
                Yii::app()->params['WBotApiUrl']
            );
            if ($status_temp->notify_user_sms == 1) {
                $message = Smss::model()->findByAttributes(['name' => $status_temp->sms]);
                $manager = CUsers::model()->findByAttributes(['Username' => $model->Managers_id]);
                $umessage = Email::MessageGen($message->content, $manager, $model);
                //if ($status_temp->close !== '3') {
                    $api->sendMessage($model->wbot_id, strip_tags($umessage, '<i><a><b><code><pre><strong><em>'));
                //}
            }
        }
        /* END WBOT SEND */

        /* SLACK SEND */
        if (Yii::app()->params['SlackEnabled'] == 1) {
            $message = Yii::app()->params['SlackTemplate'];
            $umessage = Email::MessageGen($message, $manager, $model);
            if ($status_temp->close == '1') {
                $attachments = [];
                $attachments[] = [
                    'title' => '[Ticket #' . $id . ']',
                    'title_link' => Yii::app()->params['homeUrl'] . '/request/' . $id,
                    'text' => $umessage,
                ];
                if (isset($afiles)) {
                    foreach ($afiles as $file) {
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $fname = $file;
                        if (is_dir($fname) or !file_exists($fname)) {
                            continue;
                        }
                        $mime = finfo_file($finfo, $fname);
                        $image = explode('/', $mime);
                        if ($image[0] === 'image') {
                            $dir = substr(strrchr($file, '/'), 1);
                            $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                            $attachments[] = [
                                'title' => $fileObj->name,
                                'title_link' => Yii::app()->params['homeUrl'] . '/uploads/' . $dir,
                                'image_url' => Yii::app()->params['homeUrl'] . '/uploads/' . $dir,
                                'color' => '#36a64f'
                            ];
                        } else {
                            $dir = substr(strrchr($file, '/'), 1);
                            $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                            $attachments[] = [
                                'title' => $fileObj->name,
                                'title_link' => Yii::app()->params['homeUrl'] . '/uploads/' . $dir,
                                'color' => '#36a64f'
                            ];
                        }
                    }
                }
                Slack::send(Yii::t('main-ui', 'New ticket created'), null, $attachments, null);
            }
        }
        /* END SLACK SEND */

        if (Yii::app()->params->use_rapid_msg == 1) {
            $is_console = PHP_SAPI == 'cli'; //if is console app return bool
            if ($key == 1 and isset($service_temp) and $service_temp->gtype == 1) {
                if (!$is_console) {
                    if (Yii::app()->user->name !== $manager->Username) {
                        Email::alert_send($manager->Username, $id);
                    }
                } else {
                    Email::alert_send($manager->Username, $id);
                }
            } else {
                if (!$is_console) {
                    if (Yii::app()->user->name !== $user->Username) {
                        Email::alert_send($user->Username, $id);
                    }
                } else {
                    if ($user) {
                        Email::alert_send($user->Username, $id);
                    }
                }
                if (isset($managers)) {
                    foreach ($managers as $manager_id) {
                        $email = CUsers::model()->findByPk($manager_id);
                        if ($is_console) {
                            Email::alert_send($email->Username, $id);
                        } else {
                            if (Yii::app()->user->name !== $email->Username) {
                                Email::alert_send($email->Username, $id);
                            }
                        }
                    }
                } elseif (!$is_console and $key == 1 and Yii::app()->user->checkAccess('liteformRequest')) {
                    $manager = CUsers::model()->findByAttributes(['Username' => Yii::app()->params['zdmanager']]);
                    Email::alert_send($manager->Username, $id);
                } else {
                    if ($is_console) {
                        Email::alert_send($manager->Username, $id);
                    } else {
                        if (Yii::app()->user->name !== $manager->Username) {
                            Email::alert_send($manager->Username, $id);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $address
     * @param $subject
     * @param $manager
     * @param $message
     * @param $model
     * @param $afiles
     */
    static function Mailsend($address, $subject, $manager, $message, $model, $afiles)
    {

        self::$_user = CUsers::model()->findByAttributes(['Email' => $address]);
        $is_console = PHP_SAPI == 'cli'; //if is console app return bool
        $getmailconf = array(null);
        if (isset($model->getmailconfig) and !empty($model->getmailconfig)) {
            $filename = dirname(__FILE__) . '/../config/' . $model->getmailconfig;
            $content = file_get_contents($filename);
            $getmail = unserialize(base64_decode($content));
            if (!empty($getmail['getmailsmhost'])) {
                $getmailconf = $getmail;
            } else {
                $getmailconf['getmailsmdebug'] = Yii::app()->params['smdebug'];
                $getmailconf['getmailsmhost'] = Yii::app()->params['smhost'];
                $getmailconf['getmailsmport'] = Yii::app()->params['smport'];
                $getmailconf['getmailsmtpauth'] = Yii::app()->params['smtpauth'];
                $getmailconf['getmailsmusername'] = Yii::app()->params['smusername'];
                $getmailconf['getmailsmpassword'] = Yii::app()->params['smpassword'];
                $getmailconf['getmailsmfrom'] = Yii::app()->params['smfrom'];
                $getmailconf['getmailsmfromname'] = Yii::app()->params['smfromname'];
                $getmailconf['getmailsmsec'] = Yii::app()->params['smsec'];
                $getmailconf['getmailsmignoressl'] = Yii::app()->params['smignoressl'];
                $getmailconf['getmailsmqueue'] = Yii::app()->params['smqueue'];
            }
        } else {
            $getmailconf['getmailsmdebug'] = Yii::app()->params['smdebug'];
            $getmailconf['getmailsmhost'] = Yii::app()->params['smhost'];
            $getmailconf['getmailsmport'] = Yii::app()->params['smport'];
            $getmailconf['getmailsmtpauth'] = Yii::app()->params['smtpauth'];
            $getmailconf['getmailsmusername'] = Yii::app()->params['smusername'];
            $getmailconf['getmailsmpassword'] = Yii::app()->params['smpassword'];
            $getmailconf['getmailsmfrom'] = Yii::app()->params['smfrom'];
            $getmailconf['getmailsmfromname'] = Yii::app()->params['smfromname'];
            $getmailconf['getmailsmsec'] = Yii::app()->params['smsec'];
            $getmailconf['getmailsmignoressl'] = Yii::app()->params['smignoressl'];
            $getmailconf['getmailsmqueue'] = Yii::app()->params['smqueue'];
            $getmailconf['getmailsmqueue'] = Yii::app()->params['smqueue'];
        }
        $umessage = Email::MessageGen($message->content, $manager, $model);
        $subject = Email::MessageGen($subject, $manager, $model);

        if (isset($getmailconf['getmailsmqueue']) and $getmailconf['getmailsmqueue'] == 1 and !$is_console) { //проверка включена ли очередь
            $afiles = json_encode($afiles);
        }
        if (is_array($address)) {
            foreach ($address as $value) {
                if (!empty($value)) {
                    if (isset($getmailconf['getmailsmqueue']) and $getmailconf['getmailsmqueue'] == 1 and !$is_console) { //проверка включена ли очередь
                        Yii::app()->mailQueue->push($value, $subject, $umessage, $priority = 0, $from = '', $afiles,
                            null);
                    } else {
                        Email::send($value, $subject, $umessage, $afiles, $getmailconf);
                    }

                }
            }
        } else {
            if (isset($getmailconf['getmailsmqueue']) and $getmailconf['getmailsmqueue'] == 1 and !$is_console) { //проверка включена ли очередь
                Yii::app()->mailQueue->push($address, $subject, $umessage, $priority = 0, $from = '', $afiles, null);
            } else {
                Email::send($address, $subject, $umessage, $afiles, $getmailconf);
            }
        }

    }

    /**
     * Send Email function
     * @param $content
     * @param $manager
     * @param $model
     * @return string
     */
    static function MessageGen($content, $manager, $model)
    {
        $comment = null;
        $vote = null;
        $reopen = null;
        $criteria = new CDbCriteria;
        $criteria->order = 'id DESC';
        if (isset($_POST['Comments']['comment']) and !empty($_POST['Comments']['comment'])) {
            $fullname = CUsers::model()->findByAttributes(['Username' => Yii::app()->user->name]);
            $comment = '<b>Добавлен новый комментарий</b><br>' . $fullname->fullname . ' [' . date('d.m.Y H:i') . '] : ' . $_POST['Comments']['comment'];
        } else {
            $last_comment = Comments::model()->findByAttributes(['rid' => $model->id], $criteria);
            if (isset($last_comment) and !empty($last_comment)) {
                $comment = '<b>Добавлен новый комментарий</b><br>' . $last_comment->author . ' [' . date('d.m.Y H:i') . '] : ' . $last_comment->comment;
            }
        }
        if (!isset($model->Managers_id) and isset($model->groups_id)) {
            $group = Groups::model()->findByPk($model->groups_id);
            $gphone = !empty($group->phone) ? $group->phone : 'Не задано';
            $gemail = !empty($group->email) ? $group->email : 'Не задано';
        } elseif (isset($model->Managers_id)) {
            $gphone = !empty($manager->Phone) ? $manager->Phone : 'Не задано';
            $gemail = !empty($manager->Email) ? $manager->Email : 'Не задано';
        }

        if (isset($model->CUsers_id) and !empty($model->CUsers_id)) {
            $username = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
            $udepart = !empty($username->department) ? $username->department : 'Не задано';
            $uposition = !empty($username->position) ? $username->position : 'Не задано';
        }

        if (isset($model->key) and !empty($model->key)) {
            $url = Yii::app()->params['homeUrl'] . '/request/ratingFromMail';
            $url2 = Yii::app()->params['homeUrl'] . '/request/reopenFromMail';
            $vote = '
      <h3>' . Yii::t('main-ui', 'Set the rating:') . '</h3>
      <table style ="border: 0px  solid; width: 350px; height: 20px; border-spacing: 2px;">
      <tbody>
      <tr>
      <td style="border: 0px #1e1e1e solid; background-color: rgb(247, 5, 5); height: 20px; text-align: center; width:40px"><a href="' . $url . '?id=' . $model->id . '&star_rating=1&key=' . $model->key . '">1</a></td>
      <td style="border: 0px #1e1e1e solid; background-color: rgb(247, 63, 5); height: 20px; text-align: center; width:40px"><a href="' . $url . '?id=' . $model->id . '&star_rating=2&key=' . $model->key . '">2</a></td>
      <td style="border: 0px #1e1e1e solid; background-color: rgb(247, 206, 5); height: 20px; text-align: center; width:40px"><a href="' . $url . '?id=' . $model->id . '&star_rating=3&key=' . $model->key . '">3</a></td>
      <td style="border: 0px #1e1e1e solid; background-color: rgb(157, 247, 5); height: 20px; text-align: center; width:40px"><a href="' . $url . '?id=' . $model->id . '&star_rating=4&key=' . $model->key . '">4</a></td>
      <td style="border: 0px #1e1e1e solid; background-color: rgb(0, 255, 19); height: 20px; text-align: center; width:40px"><a href="' . $url . '?id=' . $model->id . '&star_rating=5&key=' . $model->key . '">5</a></td>
      </tr>
      </tbody>
      </table>';
            $reopen = '
      <h3>' . Yii::t('main-ui', 'Reopen ticket') . ':</h3>
      <table style ="border: 0px  solid; width: 350px; height: 20px; border-spacing: 2px;">
      <tbody>
      <tr>
      <td style="border: 0px #1e1e1e solid; background-color: rgb(0, 255, 19); height: 20px; text-align: center; width:40px"><a href="' . $url2 . '?id=' . $model->id . '&key=' . $model->key . '">' . Yii::t('main-ui',
                    'Open') . '</a></td>
      </tr>
      </tbody>
      </table>';
        }

        $urlReaction = Yii::app()->params['homeUrl'] . '/request/reactionFromMail';
        $user_id = self::$_user ? self::$_user->id : null;

        $agreed = '
          <h3>' . Yii::t('main-ui', 'Approve') . ':</h3>
          <table style ="border: 0px  solid; width: 350px; height: 20px; border-spacing: 2px;">
          <tbody>
          <tr>
          <td style="border: 0px #1e1e1e solid; background-color: rgb(0, 255, 19); height: 20px; text-align: center; width:40px">
          <a href="' . $urlReaction . '?id=' . $model->id . '&reaction=' . RequestMatchingReaction::REACTION_AGREED . '&user_id=' . $user_id . '">' . Yii::t('main-ui',
                'Approve') . '</a></td>
          </tr>
          </tbody>
          </table>';

        $denied = '
          <h3>' . Yii::t('main-ui', 'Deny') . ':</h3>
          <table style ="border: 0px  solid; width: 350px; height: 20px; border-spacing: 2px;">
          <tbody>
          <tr>
          <td style="border: 0px #1e1e1e solid; background-color: rgb(255,0,0); height: 20px; text-align: center; width:40px">
          <a href="' . $urlReaction . '?id=' . $model->id . '&reaction=' . RequestMatchingReaction::REACTION_DENIED . '&user_id=' . $user_id . '">' . Yii::t('main-ui',
                'Deny') . '</a></td>
          </tr>
          </tbody>
          </table>';

        $add_info = '
          <h3>' . Yii::t('main-ui', 'Need more information') . ':</h3>
          <table style ="border: 0px  solid; width: 350px; height: 20px; border-spacing: 2px;">
          <tbody>
          <tr>
          <td style="border: 0px #1e1e1e solid; background-color: rgb(255,230,0); height: 20px; text-align: center; width:40px">
          <a href="' . $urlReaction . '?id=' . $model->id . '&reaction=' . RequestMatchingReaction::REACTION_ADD_INFO . '&user_id=' . $user_id . '">' . Yii::t('main-ui',
                'Need more information') . '</a></td>
          </tr>
          </tbody>
          </table>';

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
            '{manager_name}' => isset($manager['fullname']) ? $manager['fullname'] : 'Не задано',
            '{manager_phone}' => $gphone,
            '{manager_intphone}' => isset($manager->intphone) ? $manager->intphone : 'Не задано',
            '{manager_mobile}' => isset($manager->mobile) ? $manager->mobile : 'Не задано',
            '{manager_email}' => $gemail,
            '{room}' => isset($model->room) ? $model->room : 'Не задано',
            '{category}' => $model->ZayavCategory_id,
            '{created}' => $model->Date,
            '{comment_message}' => $comment,
            '{StartTime}' => $model->StartTime,
            '{fStartTime}' => $model->fStartTime,
            '{EndTime}' => $model->EndTime,
            '{fEndTime}' => $model->fEndTime,
            '{service_name}' => $model->service_name,
            '{address}' => $model->Address,
            '{unit}' => isset($model->cunits) ? $model->cunits : 'Не задано',
            '{company}' => $model->company,
            '{comment}' => $model->Comment,
            '{content}' => $model->Content,
            '{url}' => '<a href="' . Yii::app()->params['homeUrl'] . '/request/view/' . $model->id . '">№ ' . $model->id . '</a>',
            '{smsurl}' => Yii::app()->params['homeUrl'] . '/request/view/' . $model->id,
            '{voting}' => $vote,
            '{reopen}' => $reopen,

            '{agreed}' => $agreed,
            '{denied}' => $denied,
            '{add_info}' => $add_info,
        ]);

        return $s_message;
    }

    /**
     * Send Email function
     * @param $to
     * @param $subject
     * @param $message
     * @param $afiles
     */
    static function send($to, $subject, $message, $afiles, $getmailconf)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = (isset($getmailconf['getmailsmdebug']) and $getmailconf['getmailsmdebug'] == 1) ? 3 : 0;  // Verbose debug output
            $mail->CharSet = 'UTF-8';                             // Charset to utf-8
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->setLanguage('ru',
                __DIR__ . '/../vendors/phpmailer/vendor/phpmailer/phpmailer/language/'); // Set language codes

            $mail->Host = $getmailconf['getmailsmhost'];           // Specify main and backup SMTP servers
            if ($getmailconf['getmailsmtpauth'] == 1) {
                $mail->SMTPAuth = true;                             // Enable SMTP authentication
            } else {
                $mail->SMTPAuth = false;                            // Disable SMTP authentication
            }
            $mail->SMTPKeepAlive = true;                          // Keep alive SMTP connection
            //$mail->SingleTo = true;                               // Sending each single message
            $mail->Username = $getmailconf['getmailsmusername'];   // SMTP username
            $mail->Password = $getmailconf['getmailsmpassword'];   // SMTP password
            $mail->setFrom($getmailconf['getmailsmfrom'], $getmailconf['getmailsmfromname']); //From email and email
            $mail->addReplyTo($getmailconf['getmailsmfrom'], $getmailconf['getmailsmfromname']); //Reply to

            if (!empty($getmailconf['getmailsmsec'])) {
                $mail->SMTPSecure = $getmailconf['getmailsmsec'];    // Enable SSL or TLS encryption
            } else {
                $mail->SMTPSecure = '';                          // Disable SSL or TLS encryption
            }
            $mail->Port = $getmailconf['getmailsmport'];           // TCP port to connect to

            if (isset($getmailconf['getmailsmignoressl']) and $getmailconf['getmailsmignoressl'] == 1) { //ignoring SSL or TLS certificate verify
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
            }

            //Recipients
            if (is_array($to)) {
                $addr = implode(',', $to);
                foreach ($to as $value) {
                    if (!empty($value)) {
                        $mail->addAddress($value);
                    }
                }
            } else {
                $mail->addAddress($to);
            }
            //Attachments
            if ($afiles) {
                foreach ($afiles as $path) {
                    $os_type = DetectOS::getOS();
                    $dir = substr(strrchr($path, '/'), 1);
                    $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                    $mail->addAttachment($path, $fileObj->name);
                }
            }

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
            $mail->clearAddresses();
            $mail->clearAttachments();
            if (is_array($to)) {
                $message = 'Сообщение "' . $subject . '" для ' . $addr . ' было успешно отправлено.';
            } else {
                $message = 'Сообщение "' . $subject . '" для ' . $to . ' было успешно отправлено.';
            }
            Yii::log($message, 'info', 'MAIL_SEND');

        } catch (Exception $e) {
            if (is_array($to)) {
                $message = 'Получена ошибка при отправке сообщения "' . $subject . '" для ' . $addr . ': ' . $mail->ErrorInfo;
                Yii::log($message, 'error', 'MAIL_ERR');
            } else {
                $message = 'Получена ошибка при отправке сообщения "' . $subject . '" для ' . $to . ': ' . $mail->ErrorInfo;
                Yii::log($message, 'error', 'MAIL_ERR');
            }
        }
    }

    /**
     * Generate message content by some templates
     * @param $usernum
     * @param $manager
     * @param $sms
     * @param $model
     */
    static function Smssend($usernum, $manager, $sms, $model)
    {
        $usmessage = Email::MessageGen($sms->content, $manager, $model);

        if($manager->sendsms == 1){
            $sms = Yii::app()->sms->send_sms($usernum, $usmessage, $translit = 0, $time = 0, $id = 0,
            Yii::app()->sms->format, Yii::app()->sms->sender, $query = "", $files = array());
            if (!Yii::app()->sms->isSuccess($sms)) {
                Yii::log(Yii::app()->sms->getError($sms), 'error', 'SMS_ERROR');
            } else {
                Yii::log('SMS to ' . $usernum . ' send successfully', 'info', 'SMS_SEND');
            }    
        }
        if(CUsers::getRole($manager->Username) == 'systemManager'){
            if($manager->send_wbot == 1){
            if (Yii::app()->params['WBotEnabled'] == 1) {
            $api = new ChatApi(
                Yii::app()->params['WBotToken'],
                Yii::app()->params['WBotApiUrl']
            );
            $api->sendMessage($manager->wbot, strip_tags($usmessage, '<i><a><b><code><pre><strong><em>'));
            }
        }
        if($manager->send_vbot == 1){
            if (Yii::app()->params['VBotEnabled'] == 1) {
            $apiKey = Yii::app()->params['VBotToken'];
            $bot = new Bot(['token' => $apiKey]);
            $botSender = new Sender([
                'name' => 'Univef service desk bot',
            ]);
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($manager->vbot)
                            ->setText(strip_tags($usmessage, '<i><a><b><code><pre><strong><em>'))
                    );
            }
        }
        if($manager->send_tbot == 1){
            if (Yii::app()->params['TBotEnabled'] == 1) {
            $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
                    $telegram->sendMessage([
                        'chat_id' => $manager->tbot,
                        'parse_mode' => 'HTML',
                        'text' => strip_tags($usmessage, '<i><a><b><code><pre><strong><em>')
                    ]);
                }

            }
        }
    }

    /**
     * Pushover module
     * @param $user
     * @param $subject
     * @param $manager
     * @param $message
     * @param $model
     */
    static function Push($user, $subject, $manager, $message, $model)
    {
        $usmessage = Email::MessageGen($message->content, $manager, $model);
        $subject = Email::MessageGen($subject, $manager, $model);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushover.net/1/messages.json');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'token' => 'ar4ciQ949c915YghfxQhvbtR5UMzYt',
            'user' => $user,
            'priority' => '1',
            'html' => '1',
            'title' => $subject,
            'message' => $usmessage,
        ));
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        if ($json['status'] == 1) {
            $logmsg = 'Сообщение PUSH успешно отправлено"' . $subject . '" id сообщения ' . $json['request'];
            Yii::log($logmsg, 'info', 'PUSH_SUCCESS');
        } else {
            $logmsg = 'Получена ошибка при отправке PUSH "' . $subject;
            Yii::log($logmsg, 'error', 'PUSH_ERR');
        }
        curl_close($ch);
    }

    /**
     * Pushover module for comments
     * @param $user
     * @param $subject
     * @param $message
     */
    static function Pushc($user, $subject, $message)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushover.net/1/messages.json');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'token' => 'ar4ciQ949c915YghfxQhvbtR5UMzYt',
            'user' => $user,
            'priority' => '1',
            'html' => '1',
            'title' => $subject,
            'message' => $message,
        ));
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        if ($json['status'] == 1) {
            $logmsg = 'Сообщение PUSH успешно отправлено"' . $subject . '" id сообщения ' . $json['request'];
            Yii::log($logmsg, 'info', 'PUSH_SUCCESS');
        } else {
            $logmsg = 'Получена ошибка при отправке PUSH "' . $subject;
            Yii::log($logmsg, 'error', 'PUSH_ERR');
        }
        curl_close($ch);
    }

    /**
     * @param $user
     * @param $id
     */
    public static function sendGooglePush($user, $id)
    {
        /** @var CUsers $userM */
        $userM = CUsers::model()->findByAttributes(['Username' => $user]);
        if (null === $userM) {
            return;
        }
        $model = Request::model()->findByPk($id);
        $message = '[Ticket #' . $model->id . "]\r\nСтатус: " . $model->Status . "\r\nСрок реакции до: " . $model->StartTime . "\r\nВремя решения до: " . $model->EndTime;
        $url = Yii::app()->params['homeUrl'] . '/request/' . $id;
        $userM->pushMessage($message, $url);
    }

    /**
     * @param $user
     * @param $id
     */
    static function Alert_send($user, $id)
    {
        $model = Request::model()->findByPk($id);
        if (!isset($user)) {
            $user_model = CUsers::model()->findByAttributes(['Username' => Yii::app()->params['zdmanager']]);
            $user = $user_model->Username;
        }
        $message = $model->Name . '<br/><b>Статус</b>: ' . $model->Status . '<br/><b>Срок реакции до</b>: ' . $model->StartTime . '<br/><b>Время решения до</b>: ' . $model->EndTime;
        $alert = new Alerts();
        $alert->user = $user;
        $alert->name = $id;
        $alert->message = $message;
        $alert->save();
    }

    /**
     * @param $file
     * @return string
     */
    static function get_filesize($file)
    {
        // идем файл
        if (!file_exists($file)) {
            return 'Файл  не найден';
        }
        // теперь определяем размер файла в несколько шагов
        $filesize = filesize($file);

        // Если размер больше 1 Кб
        if ($filesize > 1024) {
            $filesize /= 1024;
            // Если размер файла больше Килобайта
            // то лучше отобразить его в Мегабайтах. Пересчитываем в Мб
            if ($filesize > 1024) {
                $filesize /= 1024;
                // А уж если файл больше 1 Мегабайта, то проверяем
                // Не больше ли он 1 Гигабайта
                if ($filesize > 1024) {
                    $filesize /= 1024;
                    $filesize = round($filesize, 1);
                    return $filesize . ' GB';
                }

                $filesize = round($filesize, 1);
                return $filesize . ' MB';
            }

            $filesize = round($filesize, 1);
            return $filesize . ' Kb';
        }

        $filesize = round($filesize, 1);
        return $filesize . ' bite';
    }

}
