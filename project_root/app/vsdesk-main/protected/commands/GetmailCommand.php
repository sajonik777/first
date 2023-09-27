<?php

set_time_limit(300);
spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
    if (file_exists($fileName)) {
        require $fileName;

        return true;
    }

    return false;
});

require dirname(__FILE__) . '/../vendors/telegram/autoload.php';
require_once __DIR__ . '/../vendors/viber/vendor/autoload.php';
require_once __DIR__ . '/../vendors/whatsapp/chatapi.class.php';

use Html2Text\Html2Text;
use Telegram\Bot\Api;
use EmailReplyParser\Parser\EmailParser;

use Viber\Bot;
use Viber\Api\Sender;

//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;

/**
 * Class GetmailCommand
 */
class GetmailCommand extends CConsoleCommand
{

    /**
     * @param array $args
     * @return int|void
     * @throws CException
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function run($args)
    {
        define('ROOT_PATH', dirname(__FILE__));
        $path = ROOT_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $os_type = DetectOS::getOS();
        $pidfile = ROOT_PATH . DIRECTORY_SEPARATOR . 'pid_getmail.pid';
        if (file_exists($pidfile)) {
            $pid = file_get_contents($pidfile);
            if ($os_type == 2) {
                exec('taskkill /f /PID ' . $pid);//убиваем процесс по PID Windows
            } else {
                exec("kill " . $pid);//убиваем процесс по PID Linux
            }
            unlink($pidfile);
            file_put_contents($pidfile, getmypid());//СОХРАНЯЕМ PID в файле
        } else {
            file_put_contents($pidfile, getmypid());//СОХРАНЯЕМ PID в файле
        }


        if (YII_DEBUG == true) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        //setting dafault timezone to Moscow
        date_default_timezone_set(Yii::app()->params['timezone']);
        require_once dirname(__FILE__) . '/../components/Html2Text.php';
        $configDirPath = dirname(__FILE__) . '/../config/';
        $mask = $configDirPath . 'getmail*.inc';
        $getmailIncFiles = array();
        foreach (glob($mask) as $filename) {
            $fArr = explode('/', $filename);
            $content = file_get_contents($filename);
            $confArr = unserialize(base64_decode($content));
            $confArr['fileName'] = $filename;
            $confArr['id'] = end($fArr);
            $getmailIncFiles[] = $confArr;
        }

        foreach ($getmailIncFiles as $configuration) {
            if ($configuration['getmail_enabled'] == 1) {
                echo("Mailbox: " . $configuration['id'] . "\n");
                $host = '{' . $configuration['getmailserver'] . ':' . $configuration['getmailport'] . $configuration['getmailpath'] . '}';
                $login = $configuration['getmailuser'];
                $password = $configuration['getmailpass'];
                $closedtonew = $configuration['getmailclosedtonew'] ? $configuration['getmailclosedtonew'] : 0;
                $msg = new Imaps($host, $login, $password);
                $messages = $msg->mail;
                $ctrim = $configuration['getmaildisablectrim'];
                $nlbr = $configuration['getmaildisablenl2br'];

                if ($messages) {
                    $service = Service::model()->findByAttributes(array('name' => $configuration['getmailservice']));
                    $nstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 1));
                    if (isset($service)) {
                        if ($service['gtype'] == 1) {
                            $manager = CUsers::model()->findByAttributes(array('Username' => $service['manager']));
                        } else {
                            $manager = null;
                        }
                    } else {
                        if (Yii::app()->params['zdtype'] == 1) {
                            $manager = CUsers::model()->findByAttributes(array('Username' => Yii::app()->params['zdmanager']));
                        } else {
                            $manager = null;
                        }
                    }

                    foreach ($messages as $message) {
                        echo("Message from: " . $message['sender'] . "\n");
                        $result = $this->checkSubject($message['subject'], $closedtonew, $message['sender']);
                        $ban = $this->checkBan($message['sender']);
                        //Если не забанен
                        if ($ban['ban'] !== true) {
                            // Если это ответ
                            if ($result !== null && $result['bool'] === true) {
                                $username = CUsers::model()->findByAttributes(array('Email' => $message['sender']));
                                if (isset($username)) {
                                    $author = $username->fullname;
                                    $senderIsUser = $username->id;
                                } else {
                                    $author = $message['sender'];
                                    $senderIsUser = false;
                                }
                                if ($message['plain'] !== '' and empty($message['html'])) {
                                    $emailContent = $message['plain'];
                                } else {
                                    if ($configuration['getmaildisableconvert'] == 1) {
                                        $decoded = mb_convert_encoding($message['html'], 'UTF-8',
                                            $message['charset'] ? $message['charset'] : 'auto');
                                        $washer = new washtml(array('allow_remote' => true));
                                        $emailContent = $washer->wash($decoded);
                                    } else {
                                        try {
                                            $decoded = mb_convert_encoding($message['html'], 'UTF-8',
                                                $message['charset'] ? $message['charset'] : 'auto');
                                            $html = new Html2Text($decoded);
                                            $pre = $html->getText();
                                        } catch (Exception $e) {
                                            $msg = $e->getMessage();
                                            Yii::log($msg, 'error', 'PARSE_ERR');
                                            $pre = $message['html'];
                                        }
                                        $emailContent = $pre ? $pre : $message['html'];
                                    }

                                }
                                $email = (new EmailParser())->parse($emailContent);
                                $visible = $email->getVisibleText();
                                $decoded = ($ctrim == 1) ? $emailContent : $visible;
                                $model = new Comments;
                                $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraComment']);
                                $model->rid = $result['request'];
                                $model->channel = 'email';
                                $model->timestamp = date('d.m.Y H:i:s');
                                $model->author = $author;
                                $model->comment = ($nlbr == 1) ? $decoded : str_replace(array("\r\n", "\r", "\n"),
                                    "<br />", $decoded);
                                $attach = $message['attach'];

                                /*******************************/
                                if ($senderIsUser) {
                                    ECommands::getCommandsInMailComment($model->rid, $emailContent, $senderIsUser);
                                }
                                /*******************************/

                                if ($model->save(false)) {
                                    $id = $model->primaryKey;
                                    Request::model()->updateByPk($result['request'],
                                        array('lastactivity' => date('Y-m-d H:i:s')));
                                    /* if (!is_dir($path . 'media' . DIRECTORY_SEPARATOR . $model->r->id) AND $attach) {
                                        mkdir($path . 'media' . DIRECTORY_SEPARATOR . $model->r->id);
                                        chmod($path . 'media' . DIRECTORY_SEPARATOR . $id, 0755);
                                    }
                                    if (!is_dir($path . 'media' . DIRECTORY_SEPARATOR . $model->r->id . DIRECTORY_SEPARATOR . $id) AND $attach) {
                                        mkdir($path . 'media' . DIRECTORY_SEPARATOR . $model->r->id . DIRECTORY_SEPARATOR . $id);
                                        chmod($path . 'media' . DIRECTORY_SEPARATOR . $model->r->id . DIRECTORY_SEPARATOR . $id, 0755);
                                    } */
                                    if ($attach) {
                                        $afiles = array();
                                        foreach ($attach as $file) {
                                            if ($file['filename'] !== 'header') {
                                                if (!empty($file['filename'])) {
                                                    $fn_charset = mb_detect_encoding($file['filename']);
                                                    if (strtolower($fn_charset) !== 'utf-8') {
                                                        $flname = mb_convert_encoding($file['filename'], 'utf-8',
                                                            $fn_charset ? $fn_charset : 'auto');
                                                    } else {
                                                        $flname = $file['filename'];
                                                    }
                                                    $fnameArr = explode('.', $flname);
                                                    $fname = uniqid('', false) . '.' . end($fnameArr);
                                                    // $name = $path . 'media' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $fname;
                                                    $name = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                                    $fp = fopen($name, "w+");
                                                    fwrite($fp, $file['data']);
                                                    fclose($fp);
                                                } else {
                                                    $fname = uniqid('', false);
                                                    $name = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                                    $fp = fopen($name, "w+");
                                                    fwrite($fp, $file['data']);
                                                    fclose($fp);
                                                    $fmime = explode('/', mime_content_type($name));
                                                    $fname = uniqid('', false) . '.' . $fmime[1];
                                                    unlink($name);
                                                    $name2 = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                                    $fp2 = fopen($name2, "w+");
                                                    fwrite($fp2, $file['data']);
                                                    fclose($fp2);

                                                }
                                                $fileObj = new Files;
                                                $fileObj->file_name = $fname;
                                                $fileObj->name = $flname;
                                                $fileObj->save(false);
                                                $requestFile = new CommentFiles;
                                                $requestFile->comment_id = $model->id;
                                                $requestFile->file_id = $fileObj->id;
                                                $requestFile->save(false);

                                                $afiles[] = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;

                                                if ($file['id'] !== null) {
                                                    $this->cinline($id, $file['id'], $fname);
                                                }
                                            }
                                        }

                                    }
                                    $mdl = Comments::model()->findByPk($model->id);
                                }

                                $usermail = CUsers::model()->findByAttributes(array('Username' => $result['user']));
                                $managermail = CUsers::model()->findByAttributes(array('Username' => $result['manager']));
                                $uaddress = array($usermail->Email);
                                $maddress = isset($managermail) ? array($managermail->Email) : '';
                                $template = Messages::model()->findByAttributes(array('name' => '{comments}'));

                                if (isset($template)) {
                                    //комментарии для пользователя
                                    $ucomments = Comments::model()->findAllByAttributes(array(
                                        'rid' => $result['request'],
                                        'show' => 0
                                    ));
                                    arsort($ucomments);
                                    $uctext = null;
                                    foreach ($ucomments as $comment) {
                                        $uctext .= '<blockquote><b>' . $comment->author . ' [' . $comment->timestamp . '] :</b><br/>' . $comment->comment . '<br/></blockquote>';
                                    }
                                    $ucomments_list = '<blockquote>' . $uctext . '<br/>' . $result['content'] . '</blockquote>';

                                    //комментарии для исполнителя
                                    $comments = Comments::model()->findAllByAttributes(array('rid' => $result['request']));
                                    arsort($comments);
                                    $ctext = null;
                                    foreach ($comments as $comment) {
                                        $ctext .= '<blockquote><b>' . $comment->author . ' [' . $comment->timestamp . '] :</b><br/>' . $comment->comment . '<br/></blockquote>';
                                    }
                                    $comments_list = '<blockquote>' . $ctext . '<br/>' . $result['content'] . '</blockquote>';

                                    $ureply_text = Yii::t('message', "$template->content", array(
                                        '{author}' => $mdl->author,
                                        '{date}' => $mdl->timestamp,
                                        '{comment}' => $mdl->comment,
                                        '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $result['request'] . '">№ ' . $result['request'] . '</a>',
                                        '{comments_list}' => $ucomments_list,

                                    ));

                                    $reply_text = Yii::t('message', "$template->content", array(
                                        '{author}' => $mdl->author,
                                        '{date}' => $mdl->timestamp,
                                        '{comment}' => $mdl->comment,
                                        '{url}' => '<a href="' . Yii::app()->params->homeUrl . '/request/' . $result['request'] . '">№ ' . $result['request'] . '</a>',
                                        '{comments_list}' => $comments_list,

                                    ));
                                    $ssubject = '[Ticket #' . $result['request'] . '] ' . $result['name'] . '';

                                    $umessage = $ureply_text;
                                    $mmessage = $reply_text;
                                } else {
                                    $ureply_text = '<b>Добавлен новый комментарий</b><br>' . $mdl->author . ' [' . $mdl->timestamp . '] :<br/>' . $mdl->comment . '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl . '/request/' . $result['request'] . '">№ ' . $result['request'] . '</a>';
                                    $reply_text = '<b>Добавлен новый комментарий</b><br>' . $mdl->author . ' [' . $mdl->timestamp . '] :<br/>' . $mdl->comment . '<br/>Просмотреть заявку: <a href="' . Yii::app()->params->homeUrl . '/request/' . $result['request'] . '">№ ' . $result['request'] . '</a>';

                                    $ssubject = '[Ticket #' . $result['request'] . '] ' . $result['name'] . '';
                                    //комментарии для заказчика
                                    $ucomments = Comments::model()->findAllByAttributes(array(
                                        'rid' => $result['request'],
                                        'show' => 0
                                    ));
                                    arsort($ucomments);
                                    $uctext = null;
                                    foreach ($ucomments as $comment) {
                                        $uctext .= '<blockquote>' . $comment->author . ' [' . $comment->timestamp . '] :<br/>' . $comment->comment . '<br/></blockquote>';
                                    }
                                    $ureply_text .= '<blockquote>' . $uctext . '<br/>' . $result['content'] . '</blockquote>';
                                    $umessage = $ureply_text;

                                    //комментарии для исполнителя
                                    $comments = Comments::model()->findAllByAttributes(array('rid' => $result['request']));
                                    arsort($comments);
                                    $ctext = null;
                                    foreach ($comments as $comment) {
                                        $ctext .= '<blockquote>' . $comment->author . ' [' . $comment->timestamp . '] :<br/>' . $comment->comment . '<br/></blockquote>';
                                    }
                                    $reply_text .= '<blockquote>' . $ctext . '<br/>' . $result['content'] . '</blockquote>';
                                    $mmessage = $reply_text;
                                }

                                $this->AddHistory('Добавлен комментарий: ' . '<b>' . $decoded . '</b>',
                                    $result['request'], $mdl->author);

                                /* TELEGRAM SEND */
                                $ticket = Request::model()->findByPk($result['request']);
                                if (isset($ticket->tchat_id) and !empty($ticket->tchat_id) and Yii::app()->params['TBotEnabled'] == 1) {
                                    $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
                                    $telegram->sendMessage([
                                        'chat_id' => $ticket->tchat_id,
                                        'parse_mode' => 'HTML',
                                        'text' => mb_strimwidth(strip_tags($mdl->comment, '<i><a><b><code><pre><strong><em>'), 0, 4000, "...")
                                    ]);
                                    if (isset($afiles)) {
                                        foreach ($afiles as $file) {
                                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                            $fname = $file;
                                            if (is_dir($fname) or !file_exists($fname)) {
                                                continue;
                                            }
                                            $mime = finfo_file($finfo, $fname);
                                            $image = explode("/", $mime);
                                            if ($image[0] == 'image') {
                                                $dir = substr(strrchr($file, "/"), 1);
                                                $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                                                $telegram->sendPhoto([
                                                    'chat_id' => $ticket->tchat_id,
                                                    'photo' => $file,
                                                    'caption' => $fileObj->name
                                                ]);
                                            } else {
                                                $dir = substr(strrchr($file, "/"), 1);
                                                $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                                                $telegram->sendDocument([
                                                    'chat_id' => $ticket->tchat_id,
                                                    'document' => $file,
                                                    'caption' => $fileObj->name
                                                ]);
                                            }
                                        }
                                    }
                                }
                                /* END TELEGRAM SEND */
                                /* VIBER SEND */
                                if (isset($ticket->viber_id) && !empty($ticket->viber_id) && 1 == Yii::app()->params['VBotEnabled']) {
                                    $apiKey = Yii::app()->params['VBotToken'];
                                    $botSender = new Sender([
                                        'name' => 'Univef service desk bot',
                                        //  'avatar' => 'https://developers.viber.com/images/favicon.ico',
                                    ]);
                                    //                $log = new Logger('bot');
                                    //                $log->pushHandler(new StreamHandler(__DIR__ . '/../runtime/vbot.log'));
                                    $bot = new Bot(['token' => $apiKey]);
                                    $bot->getClient()->sendMessage(
                                        (new \Viber\Api\Message\Text())
                                            ->setSender($botSender)
                                            ->setReceiver($ticket->viber_id)
                                            ->setText(strip_tags($mdl->comment, '<i><a><b><code><pre><strong><em>'))
                                    );
                                    if (isset($afiles)) {
                                        foreach ($afiles as $file) {
                                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                            $fname = $file;
                                            if (is_dir($fname) or !file_exists($fname)) {
                                                continue;
                                            }
                                            $mime = finfo_file($finfo, $fname);
                                            $image = explode("/", $mime);
                                            if ($image[0] == 'image') {
                                                $dir = substr(strrchr($file, "/"), 1);
                                                $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                                                $bot->getClient()->sendMessage(
                                                    (new \Viber\Api\Message\Picture())
                                                        ->setSender($botSender)
                                                        ->setReceiver($ticket->viber_id)
                                                        ->setText($fileObj->name)
                                                        ->setMedia(Yii::app()->params['homeUrl'] . '/uploads/' . $fileObj->file_name)
                                                );
                                            } else {
                                                $dir = substr(strrchr($file, "/"), 1);
                                                $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                                                $bot->getClient()->sendMessage(
                                                    (new \Viber\Api\Message\Url())
                                                        ->setSender($botSender)
                                                        ->setReceiver($ticket->viber_id)
                                                        ->setMedia(Yii::app()->params['homeUrl'] . '/uploads/' . $fileObj->file_name)
                                                );
                                            }
                                        }
                                    }
                                    //                $log->info('add comment');
                                }
                                /* END VIBER SEND */

                                /* MSBOT SEND */
                                if (isset($model->msbot_id) and !empty($model->msbot_id) and Yii::app()->params['MSBotEnabled'] == 1) {

                                    $microsoftBot = new MicrosoftBotFramework(Yii::app()->params['MSBotAppId'],
                                        Yii::app()->params['MSBotAppPassword']);
                                    $microsoftBot->sendMessage(strip_tags($mdl->comment),
                                        json_decode($model->msbot_params, true));

                                    if (isset($afiles)) {
                                        foreach ($afiles as $file) {
                                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                            $fname = $file;
                                            if (is_dir($fname) or !file_exists($fname)) {
                                                continue;
                                            }
                                            $mime = finfo_file($finfo, $fname);
                                            $image = explode("/", $mime);
                                            if ($image[0] == 'image') {
                                                $dir = substr(strrchr($file, "/"), 1);
                                                $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                                                $attachments = [
                                                    [
                                                        'contentType' => 'image/png',
                                                        'contentUrl' => Yii::app()->params['homeUrl'] . '/uploads/' . $fileObj->file_name,
                                                        'name' => $fileObj->name,
                                                    ]
                                                ];
                                                $microsoftBot->sendAttach($attachments,
                                                    json_decode($ticket->msbot_params, true));
                                            } else {
                                                $dir = substr(strrchr($file, "/"), 1);
                                                $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                                                $attachments = [
                                                    [
                                                        'contentType' => '',
                                                        'contentUrl' => Yii::app()->params['homeUrl'] . '/uploads/' . $fileObj->file_name,
                                                        'name' => $fileObj->name,
                                                    ]
                                                ];
                                                $microsoftBot->sendAttach($attachments,
                                                    json_decode($ticket->msbot_params, true));
                                            }
                                        }
                                    }
                                }
                                /* END MSBOT SEND */

                                /* WBOT SEND */
                                if (isset($model->wbot_id) && !empty($model->wbot_id) && 1 == Yii::app()->params['WBotEnabled'] and $this->show == 0) {
                                    $api = new ChatApi(
                                        Yii::app()->params['WBotToken'],
                                        Yii::app()->params['WBotApiUrl']
                                    );
                                    $api->sendMessage($model->wbot_id, strip_tags($this->comment, '<i><a><b><code><pre><strong><em>'));

                                    if (isset($afiles)) {
                                        foreach ($afiles as $file) {
                                            $fname = $file;
                                            if (is_dir($fname) or !file_exists($fname)) {
                                                continue;
                                            }

                                            $api->sendMessage($model->wbot_id, Yii::app()->params['homeUrl'] . '/uploads/' . $fileObj->file_name);
                                        }
                                    }
                                }
                                /* END WBOT SEND */

                                if (Yii::app()->params->use_rapid_msg == 1) {
                                    $this->alert_send($managermail, $result['request'],
                                        $result['name'] . '<br/><b>Был добавлен комментарий</b>: ' . $decoded);
                                    $this->alert_send($usermail, $result['request'],
                                        $result['name'] . '<br/><b>Был добавлен комментарий</b>: ' . $decoded);
                                }
                                if (isset($usermail) and $usermail->sendmail == 1 and $usermail !== $managermail) {
                                    SendMail::send($uaddress, $ssubject, $umessage, $afiles, $configuration['id']);
                                }
                                if ($result['user'] == null and $message['sender'] !== $managermail) {
                                    $req = Request::model()->findByPk($result['request']);
                                    if (isset($req) and $req->channel !== 'Telegram') {
                                        $user_address = $req['creator'];
                                        SendMail::send($user_address, $ssubject, $umessage, $afiles,
                                            $configuration['id']);
                                    }
                                }

                                //Проверка и отправка группе исполнителей или на общий ящик
                                if (!isset($result['manager']) and isset($result['groups_id'])) {
                                    $groups = Groups::model()->findByPk($result['groups_id']);
                                    if ($groups && $groups->send && $groups->email) {
                                        $manager_address = $groups->email;
                                        SendMail::send($manager_address, $ssubject, $mmessage, $afiles,
                                            $configuration['id']);
                                    } else {
                                        $managers = explode(",", $groups->users);
                                        if (isset($managers)) {
                                            foreach ($managers as $manager_id) {
                                                $email = CUsers::model()->findByPk($manager_id);
                                                if ($email->sendmail == 1) {
                                                    if ($email->Email !== $message['sender']) {
                                                        SendMail::send($email->Email, $ssubject, $mmessage, $afiles,
                                                            $configuration['id']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if (isset($managermail) and $managermail->sendmail == 1) {
                                        if ($maddress !== $message['sender']) {
                                            SendMail::send($maddress, $ssubject, $mmessage, $afiles,
                                                $configuration['id']);
                                        }
                                    }
                                }

                                if (isset($usermail)) {
                                    $pmessage = "[Ticket #" . $result['request'] . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($mdl->comment));
                                    $url = Yii::app()->params['homeUrl'] . "/request/" . $result['request'];
                                    $usermail->pushMessage($pmessage, $url);
                                }
                                if (isset($managermail)) {
                                    $pmessage = "[Ticket #" . $result['request'] . "]\r\nБыл добавлен комментарий:\r\n " . trim(strip_tags($mdl->comment));
                                    $url = Yii::app()->params['homeUrl'] . "/request/" . $result['request'];
                                    $managermail->pushMessage($pmessage, $url);
                                }
                                if ($configuration['getmaildelete'] == 1) {
                                    $msg->deleteMail($host, $login, $password, $message['id']);
                                }

                            } else {
                                // Если это новое письмо
                                if (isset($result) && $result['bool'] == false) {
                                    //$subj = preg_replace('/^((\[(RE|re|на|fw(d)?)\s*\]|[\[]?(re|fw(d)?))\s*[\:\;]\s*([\]]\s?)*|\(fw(d)?\)\s*)*([^\[\]]*)[\]]*/i', '', $message['subject']);
                                    $subj = preg_replace('/^((Re|На|От|Fwd?)(\[\d+\])?: |(Re|На|От|Fwd?)(\(\d+\))?: )+/iu',
                                        '', $message['subject']);
                                    $subject = preg_replace("/\[Ticket #\d*\]*/i", '', $subj);
                                } else {
                                    $subject = $message['subject'];
                                }
                                $exists = null;
                                $exist = null;
                                if (isset($configuration['getmailcopytowatchers']) and $configuration['getmailcopytowatchers'] == 1) { //проверяем добавлять ли в наблюдатели из копии СС
                                    $cc = explode(',', $message['cc']);
                                    foreach ($cc as $ccitem) {
                                        if (!empty($ccitem)) {
                                            $exist = CUsers::model()->findByAttributes(['Email' => $ccitem]);
                                        }
                                        if (isset($exist)) {
                                            $exists[] = $exist->fullname; //проверка среди пользователей в БД адресата в копии
                                        }
                                    }
                                }
                                $username = CUsers::model()->findByAttributes(array('Email' => $message['sender']));
                                if ($username) {
                                    $company = Companies::model()->findByAttributes(array('name' => $username->company));
                                } else {
                                    $companies = Companies::model()->findAll();
                                    foreach ($companies as $comp) {
                                        if (!empty($comp->domains) and (isset($comp->domains))) {
                                            $domains = explode(',', $comp->domains);
                                            foreach ($domains as $domain) {
                                                if (!empty($domain)) {
                                                    $daddress = substr($message['sender'],
                                                        strrpos($message['sender'], '@') + 1);
                                                    if (trim($domain) == $daddress) {
                                                        $company = $comp;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $attach = $message['attach'];
                                $watchers = null;
                                $model = new MailRequest;
                                $model->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
                                $model->Name = $subject;
                                $model->channel = 'Email';
                                $model->channel_icon = 'fa-solid fa-envelope';
                                if (isset($service)) {
                                    $model->Priority = $service['priority'];
                                    $watchers = explode(',', $service['watcher']);
                                    $watchers = array_values(array_diff($watchers, array("null", "")));
                                    $model->watchers = $exists ? implode(',',
                                        array_unique(array_merge($watchers, $exists))) : $service['watcher'];
                                    if ($service['gtype'] == 1) {
                                        $model->Managers_id = $service['manager'];
                                    } else {
                                        $model->gfullname = $service['group'];
                                        $group = Groups::model()->findByAttributes(array('name' => $service['group']));
                                        $model->groups_id = $group->id;
                                    }
                                } else {
                                    $model->Priority = Yii::app()->params['zdpriority'];
                                    $model->watchers = implode(',', $exists);
                                    if (Yii::app()->params['zdtype'] == 1) {
                                        $model->Managers_id = Yii::app()->params['zdmanager'];
                                    } else {
                                        $model->gfullname = Yii::app()->params['zdmanager'];
                                        $group = Groups::model()->findByAttributes(array('name' => Yii::app()->params['zdmanager']));
                                        $model->groups_id = $group->id;
                                    }
                                }
                                $model->Type = '';
                                $model->mfullname = $manager->fullname ? $manager->fullname : null;
                                if ($username) {
                                    $model->CUsers_id = $username->Username;
                                    $model->fullname = $username->fullname;
                                    $model->creator = $username->fullname;
                                    $model->company = $username->company ? $username->company : null;
                                    $model->company_id = $company->id ? $company->id : null;
                                    $model->depart = $username->department;
                                    $depart = Depart::model()->findByAttributes([
                                        'name' => $username->department,
                                        'company' => $username->company
                                    ]);
                                    $model->depart_id = $depart->id;
                                    $model->room = $username->room ? $username->room : null;
                                    $model->phone = $username->Phone ? $username->Phone : null;
                                    $model->Address = $company->faddress ? $company->faddress : null;
                                } else {
                                    $model->fullname = $message['sender'];
                                    $model->creator = $message['sender'];
                                    $model->company = $company->name ? $company->name : null;
                                }
                                $model->service_id = $service->id ? $service->id : null;
                                $model->service_name = $service->name ? $service->name : null;
                                $model->ZayavCategory_id = Yii::t('main-ui', 'E-mail ticket');
                                $model->Date = date("d.m.Y H:i");
                                $model->timestamp = date('Y-m-d H:i:s');
                                $model->cunits = '';
                                $model->Status = $nstatus->name;
                                $model->slabel = $nstatus->label;
                                $model->getmailconfig = $configuration['id'];
                                $trim = $configuration['getmaildisabletrim'];

                                if ($message['plain'] !== '' and empty($message['html'])) {
                                    $decoded = mb_convert_encoding($message['plain'], 'UTF-8',
                                        $message['charset'] ? $message['charset'] : 'auto');
                                    if ($trim !== 1) {
                                        $email = (new EmailParser())->parse($decoded);
                                        $decoded = $email->getVisibleText();
                                    }
                                    $model->Content = ($nlbr == 1) ? $decoded : str_replace(array("\r\n", "\r", "\n"),
                                        "<br />", $decoded);
                                } else {
                                    if ($configuration['getmaildisableconvert'] == 1) {
                                        $emailContent = $message['html'];
                                        $commandAttributes = ECommands::getCommandsInMail($emailContent);
                                        $email = (new EmailParser())->parse($emailContent);
                                        $visible = $email->getVisibleText();
                                        $decoded = mb_convert_encoding(($trim == 1) ? $emailContent : $visible, 'UTF-8',
                                            $message['charset'] ? $message['charset'] : 'auto');
                                        $washer = new washtml(array('allow_remote' => true));
                                        $content = $washer->wash($decoded);
                                        $model->Content = ($nlbr == 1) ? $content : str_replace(array(
                                            "\r\n",
                                            "\r",
                                            "\n"
                                        ), "<br />", $content);
                                    } else {
                                        try {
                                            $emailContent = $message['html'];
                                            $decoded = mb_convert_encoding($emailContent, 'UTF-8',
                                                $message['charset'] ? $message['charset'] : 'auto');
                                            $html = new Html2Text($decoded);
                                            $pre = $html->getText();
                                        } catch (Exception $e) {
                                            $msg = $e->getMessage();
                                            Yii::log($msg, 'error', 'PARSE_ERR');
                                            $pre = $message['html'];
                                        }
                                        $emailContent = $pre ? $pre : $message['html'];
                                        $commandAttributes = ECommands::getCommandsInMail($emailContent);
                                        $email = (new EmailParser())->parse($emailContent);
                                        $visible = $email->getVisibleText();
                                        if ($trim == 1) {
                                            $decoded = ($nlbr == 1) ? $emailContent : str_replace(array(
                                                "\r\n",
                                                "\r",
                                                "\n"
                                            ), "<br />", $emailContent);
                                        } else {
                                            $decoded = ($nlbr == 1) ? $visible : str_replace(array("\r\n", "\r", "\n"),
                                                "<br />", $visible);
                                        }
                                        $model->Content = $decoded;
                                    }
                                }


                                foreach ($commandAttributes as $attribute => $value) {
                                    $model->$attribute = $value;
                                }

                                if ($model->save(false)) {
                                    $id = $model->primaryKey;
                                    /*if (!is_dir($path . 'media' . DIRECTORY_SEPARATOR . $id) AND $attach) {
                                        mkdir($path . 'media' . DIRECTORY_SEPARATOR . $id);
                                        chmod($path . 'media' . DIRECTORY_SEPARATOR . $id, 0755);
                                    }*/
                                    if ($attach) {
                                        $afiles = array();
                                        foreach ($attach as $file) {
                                            if ($file['filename'] !== 'header') {
                                                if (!empty($file['filename'])) {
                                                    $fn_charset = mb_detect_encoding($file['filename']);
                                                    if (strtolower($fn_charset) !== 'utf-8') {
                                                        $flname = mb_convert_encoding($file['filename'], 'utf-8',
                                                            $fn_charset ? $fn_charset : 'auto');
                                                    } else {
                                                        $flname = $file['filename'];
                                                    }
                                                    $fnameArr = explode('.', $flname);
                                                    $fname = uniqid('', false) . '.' . end($fnameArr);
                                                    // $name = $path . 'media' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . $fname;
                                                    $name = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                                    $fp = fopen($name, "w+");
                                                    fwrite($fp, $file['data']);
                                                    fclose($fp);
                                                } else {
                                                    $fname = uniqid('', false);
                                                    $name = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                                    $fp = fopen($name, "w+");
                                                    fwrite($fp, $file['data']);
                                                    fclose($fp);
                                                    $fmime = explode('/', mime_content_type($name));
                                                    $fname = uniqid('', false) . '.' . $fmime[1];
                                                    unlink($name);
                                                    $name2 = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                                    $fp2 = fopen($name2, "w+");
                                                    fwrite($fp2, $file['data']);
                                                    fclose($fp2);

                                                }
                                                $fileObj = new Files;
                                                $fileObj->file_name = $fname;
                                                $fileObj->name = $flname;
                                                $fileObj->save(false);
                                                $requestFile = new RequestFiles;
                                                $requestFile->request_id = $id;
                                                $requestFile->file_id = $fileObj->id;
                                                $requestFile->save(false);

                                                $afiles[] = $path . 'uploads' . DIRECTORY_SEPARATOR . $fname;
                                            }
                                            if ($file['id'] !== null) {
                                                $this->inline($id, $file['id'], $fname);
                                            }
                                        }
                                        Email::prepare($id, 1, $afiles);
                                        unset($afiles);
                                    } else {
                                        Email::prepare($id, 1, null);
                                    }

                                }
                                if ($configuration['getmaildelete'] == 1) {
                                    $msg->deleteMail($host, $login, $password, $message['id']);
                                }
                            }
                        } else {
                            Yii::log('Email from ' . $message["sender"] . ' was banned', 'error', 'PARSE_ERR');
                        }
                    }
                }
            }
        }

    }


    public function detect_encoding($string, $pattern_size = 50)
    {
        $list = array('cp1251', 'utf-8', 'ascii', '855', 'KOI8R', 'ISO-IR-111', 'CP866', 'KOI8U');
        $c = strlen($string);
        if ($c > $pattern_size) {
            $string = substr($string, floor(($c - $pattern_size) / 2), $pattern_size);
            $c = $pattern_size;
        }

        $reg1 = '/(\xE0|\xE5|\xE8|\xEE|\xF3|\xFB|\xFD|\xFE|\xFF)/i';
        $reg2 = '/(\xE1|\xE2|\xE3|\xE4|\xE6|\xE7|\xE9|\xEA|\xEB|\xEC|\xED|\xEF|\xF0|\xF1|\xF2|\xF4|\xF5|\xF6|\xF7|\xF8|\xF9|\xFA|\xFC)/i';

        $mk = 10000;
        $enc = 'ascii';
        foreach ($list as $item) {
            $sample1 = @iconv($item, 'cp1251', $string);
            $gl = @preg_match_all($reg1, $sample1, $arr);
            $sl = @preg_match_all($reg2, $sample1, $arr);
            if (!$gl || !$sl) {
                continue;
            }
            $k = abs(3 - ($sl / $gl));
            $k += $c - $gl - $sl;
            if ($k < $mk) {
                $enc = $item;
                $mk = $k;
            }
        }
        return $enc;
    }

    public function checkSubject($subject, $closed, $user)
    {
        //$subj = preg_replace('/^((\[(RE|re|на|fw(d)?)\s*\]|[\[]?(re|fw(d)?))\s*[\:\;]\s*([\]]\s?)*|\(fw(d)?\)\s*)*([^\[\]]*)[\]]*/i', '', $subject);
        $subj = preg_replace('/^((Re|На|От|Fwd?)(\[\d+\])?: |(Re|На|От|Fwd?)(\(\d+\))?: )+/iu', '', $subject);
        //new matching of ticket ID
        preg_match('/#[[^\d]+/iu', $subj, $req_id);
        $id = substr($req_id[0], 1);

        //old matching of ticket ID
        //list($req_id) = sscanf(strtolower($subj), "[ticket #%d"); //выделяем id заявки
        //$id = $req_id;
        $request = Request::model()->findByPk($id);
        if (isset ($request)) {
            if ($request->closed == 3 && isset($closed) && $closed == 1) { //проверяем закрыта ли заявка и включен ли параметр создания новой заявки при добавлении комментария
                $username = CUsers::model()->findByAttributes(array('Email' => $user));
                if ($request->CUsers_id == $username->Username or $request->fullname == $user) { //проверяем добавляет комментарий заказчик или нет
                    return array(
                        'request' => $id,
                        'bool' => false,
                        'user' => $request->CUsers_id,
                        'manager' => $request->Managers_id,
                        'groups_id' => $request->groups_id,
                        'name' => $request->Name,
                        'content' => $request->Content
                    );
                } else {
                    return array(
                        'request' => $id,
                        'bool' => true,
                        'user' => $request->CUsers_id,
                        'manager' => $request->Managers_id,
                        'groups_id' => $request->groups_id,
                        'name' => $request->Name,
                        'content' => $request->Content
                    );
                }
            } else {
                return array(
                    'request' => $id,
                    'bool' => true,
                    'user' => $request->CUsers_id,
                    'manager' => $request->Managers_id,
                    'groups_id' => $request->groups_id,
                    'name' => $request->Name,
                    'content' => $request->Content
                );
            }
        }
    }

    public function checkBan($sender)
    {
        $banlist = Banlist::model()->findAllByAttributes(array('value' => mb_strtolower($sender)));
        if (isset($banlist) and !empty($banlist)) {
            return array(
                'ban' => true,
            );
        }
    }

    public function Alert_send($user, $id, $message)
    {
        $alert = new Alerts();
        $alert->user = $user->Username;
        $alert->name = $id;
        $alert->message = $message;
        $alert->save();
    }

    public function AddHistory($action, $id, $user = null)
    {
        $history = new History();
        $history->datetime = date("d.m.Y H:i");
        $history->cusers_id = $user ? $user : 'system';
        $history->zid = $id;
        $history->action = $action;
        $history->save(false);
    }

    // Сканирование папки с вложениями
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

    public function inline($id, $cid, $name)
    {
        $request = Request::model()->findByPk($id);
        $content = $request->Content;
        $url = Yii::app()->params['homeUrl'];

        preg_match_all('/src="cid:(.*)"/Uims', $content, $matches);
        if (count($matches)) {
            $search = array();
            $replace = array();

            foreach ($matches[1] as $match) {
                $arr = array("<", ">");
                $cid = str_replace($arr, "", $cid);
                // work out some unique filename for it and save to filesystem etc
                if ($match == $cid) {
                    $uniqueFilename = $name;
                    $search[] = "src=\"cid:$match\"";
                    $replace[] = "src=\"$url/uploads/$uniqueFilename\"";
                }
            }

            // now do the replacements
            $newcontent = str_replace($search, $replace, $content);
            Request::model()->updateByPk($id, array('Content' => $newcontent));

        }

    }

    public function cinline($id, $cid, $name)
    {
        $request = Comments::model()->findByPk($id);
        $content = $request->comment;
        $url = Yii::app()->params['homeUrl'];

        preg_match_all('/src="cid:(.*)"/Uims', $content, $matches);
        if (count($matches)) {
            $search = array();
            $replace = array();

            foreach ($matches[1] as $match) {
                $arr = array("<", ">");
                $cid = str_replace($arr, "", $cid);
                // work out some unique filename for it and save to filesystem etc
                if ($match == $cid) {
                    $uniqueFilename = $name;
                    $search[] = "src=\"cid:$match\"";
                    $replace[] = "src=\"$url/uploads/$uniqueFilename\"";
                }
            }

            // now do the replacements
            $newcontent = str_replace($search, $replace, $content);
            Comments::model()->updateByPk($id, array('comment' => $newcontent));

        }

    }
}
