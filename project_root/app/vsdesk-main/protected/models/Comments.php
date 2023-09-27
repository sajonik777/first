<?php

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property integer $id
 * @property integer $rid
 * @property string $timestamp
 * @property string $author
 * @property string $comment
 * @property integer $show
 * @property string $recipients
 * @property string $readership
 * @property boolean $read
 *
 * @property array $files2
 * @property Files[] $commFiles
 * @property RequestFiles[] $commentFiles
 *
 * The followings are the available model relations:
 * @property Request $r
 */
require dirname(__FILE__) . '/../vendors/telegram/autoload.php';

use Telegram\Bot\Api;

require_once __DIR__ . '/../vendors/viber/vendor/autoload.php';
require_once __DIR__ . '/../vendors/whatsapp/chatapi.class.php';
require_once __DIR__ . '/../components/Html2Text.php';

use Viber\Bot;
use Viber\Api\Sender;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Html2Text\Html2Text;

class Comments extends CActiveRecord
{
    public $theme;
    public $kbtheme;
    public $status;
    public $add_temp;
    public $add_kb;
    public $channel;

    private $_read = false;

    /** @var array */
    private $_files = [];

    /**
     * @return array
     */
    public function getFiles2()
    {
        if ($this->isNewRecord) {
            return $this->_files;
        } else {
            return $this->getAttachments();
        }
    }

    /**
     * @return array
     */
    private function getAttachments()
    {
        $attachments = [];
        if (!empty($this->commFiles)) {
            foreach ($this->commFiles as $file) {
                /* @var $file Files */
                $attachments[$file->id] = $file->file_name;
            }
        }
        return $attachments;
    }

    /**
     * @param array $value
     */
    public function setFiles2($value)
    {
        if (!empty($value)) {
            $this->_files = $value;
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Comments the static model class
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
        return 'comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rid, timestamp, author, comment', 'required'),
            array('rid', 'numerical', 'integerOnly' => true),
            array('author, theme, kbtheme, channel', 'length', 'max' => 100),
            array('recipients', 'length', 'max' => 500),
            array('readership', 'length', 'max' => 255),
            array('show, add_temp, add_kb', 'length', 'max' => 1),
            array('files', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, rid, timestamp, author, comment, show, recipients, channel, files', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'r' => array(self::BELONGS_TO, 'Request', 'rid'),
            'commentFiles' => array(self::HAS_MANY, 'CommentFiles', 'comment_id'),
            'commFiles' => array(self::MANY_MANY, 'Files', 'comment_files(comment_id, file_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'rid' => 'Rid',
            'timestamp' => Yii::t('main-ui', 'Created'),
            'author' => Yii::t('main-ui', 'Author'),
            'comment' => Yii::t('main-ui', 'Comment'),
            'show' => Yii::t('main-ui', 'Hide from client'),
            'add_temp' => Yii::t('main-ui', 'Add reply to templates'),
            'add_kb' => Yii::t('main-ui', 'Add reply to knowledge base'),
            'theme' => Yii::t('main-ui', 'Select template'),
            'kbtheme' => Yii::t('main-ui', 'Select KB record'),
            'status' => Yii::t('main-ui', 'Change status'),
            'recipients' => Yii::t('main-ui', 'Recipients'),
            'readership' => Yii::t('main-ui', 'Readership'),
        );
    }

    /**
     * @return bool
     */
    public function getRead()
    {
        if (!empty($this->readership)) {
            $readership = explode(',', $this->readership);
            if (in_array(Yii::app()->user->id, $readership)) {
                $this->_read = true;
            } else {
                $this->_read = false;
            }
        } else {
            $this->_read = false;
        }
        return $this->_read;
    }

    /**
     * @param bool $read
     */
    public function setRead($read = true)
    {
        if ($read) {
            if (empty($this->readership)) {
                $this->readership = Yii::app()->user->id;
            } else {
                if (!$this->read) {
                    $this->readership .= ',' . Yii::app()->user->id;
                }
            }
        } else {
            if ($this->read) {
                $readership = explode(',', $this->readership);
                $key = array_search(Yii::app()->user->id, $readership);
                unset($readership[$key]);
                $this->readership = implode(',', $readership);
            }
        }
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('rid', $this->rid);
        $criteria->compare('timestamp', $this->timestamp, true);
        $criteria->compare('author', $this->author, true);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('show', $this->show, true);
        $criteria->compare('theme', $this->theme, true);
        $criteria->compare('recipients', $this->recipients, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeSave()
    {
        $ticket = Request::model()->findByPk($this->rid);
        $commentAuthor = CUsers::model()->findByAttributes(['fullname' => $this->author]);
        $ticket_manager = CUsers::model()->findByAttributes(['Username' => $ticket->Managers_id]);
        $html = new Html2Text($this->comment);
        $text = $html->getText();
        $comment_text = "Пользователь " . $this->author . " добавил комментарий к заявке [№ " . $ticket->id . "]: \r\n" . $text . "\r\n" . Yii::app()->params['homeUrl'] . "/request/view/" . $ticket->id;
        $comment_text = mb_strimwidth($comment_text, 0, 4000, "...");

        if($this->isNewRecord) {
            if ($ticket->Managers_id == null and $ticket->groups_id !== null) {
                $groups = Groups::model()->findByPk($ticket->groups_id);
                $managers = explode(',', $groups->users);
                foreach ($managers as $manager) {
                    $group_manager = CUsers::model()->findByPk($manager);
                    if(($group_manager->sendsms == 1) AND (CUsers::getRole($group_manager->Username) == 'systemManager')) {
                        if($group_manager->send_wbot == 1) {
                            if (Yii::app()->params['WBotEnabled'] == 1) {
                                $api = new ChatApi(
                                    Yii::app()->params['WBotToken'],
                                    Yii::app()->params['WBotApiUrl']
                                );
                                $api->sendMessage($group_manager->wbot, strip_tags($comment_text, '<i><a><b><code><pre><strong><em>'));
                            }
                        }
                        if ($group_manager->send_vbot == 1) {
                            if (Yii::app()->params['VBotEnabled'] == 1) {
                                $apiKey = Yii::app()->params['VBotToken'];
                                $bot = new Bot(['token' => $apiKey]);
                                $botSender = new Sender([
                                    'name' => 'Univef bot',
                                ]);
                                $bot->getClient()->sendMessage(
                                    (new \Viber\Api\Message\Text())
                                        ->setSender($botSender)
                                        ->setReceiver($group_manager->vbot)
                                        ->setText(strip_tags($comment_text, '<i><a><b><code><pre><strong><em>'))
                                );
                            }
                        }
                        if ($group_manager->send_tbot == 1) {
                            if (Yii::app()->params['TBotEnabled'] == 1) {
                                $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
                                $telegram->sendMessage([
                                    'chat_id' => $group_manager->tbot,
                                    'parse_mode' => 'HTML',
                                    'text' => strip_tags($comment_text, '<i><a><b><code><pre><strong><em>')
                                ]);
                            }
                        }
                    }
                }
            }
            if ($ticket->Managers_id !== $commentAuthor->Username) {
                //$text = htmlentities($this->comment);
                if (CUsers::getRole($ticket_manager->Username) == 'systemManager') {
                    if ($ticket_manager->send_wbot == 1) {
                        if (Yii::app()->params['WBotEnabled'] == 1) {
                            $api = new ChatApi(
                                Yii::app()->params['WBotToken'],
                                Yii::app()->params['WBotApiUrl']
                            );
                            $api->sendMessage($ticket_manager->wbot, strip_tags($comment_text, '<i><a><b><code><pre><strong><em>'));
                        }
                    }
                    if ($ticket_manager->send_vbot == 1) {
                        if (Yii::app()->params['VBotEnabled'] == 1) {
                            $apiKey = Yii::app()->params['VBotToken'];
                            $bot = new Bot(['token' => $apiKey]);
                            $botSender = new Sender([
                                'name' => 'Univef bot',
                            ]);
                            $bot->getClient()->sendMessage(
                                (new \Viber\Api\Message\Text())
                                    ->setSender($botSender)
                                    ->setReceiver($ticket_manager->vbot)
                                    ->setText(strip_tags($comment_text, '<i><a><b><code><pre><strong><em>'))
                            );
                        }
                    }
                    if ($ticket_manager->send_tbot == 1) {
                        if (Yii::app()->params['TBotEnabled'] == 1) {
                            $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
                            $telegram->sendMessage([
                                'chat_id' => $ticket_manager->tbot,
                                'parse_mode' => 'HTML',
                                'text' => strip_tags($comment_text, '<i><a><b><code><pre><strong><em>')
                            ]);
                        }

                    }
                }
            }
        }
        return parent::beforeSave();
    }


    public function afterSave()
    {
        //$os_type = DetectOS::getOS();
        //$fileList = array();

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

        // Новый блок загрузки файла в форме создания заявки
        /*********************************************/
        $allFiles = [];
        $result1 = [];
        preg_match_all('#src="/uploads/([^"]+)"#i', $this->comment, $result1);
        $result2 = [];
        preg_match_all('#href="/uploads/([^"]+)"#i', $this->comment, $result2);
        if (!empty($result1[0][0])) {
            $allFiles = array_merge($allFiles, $result1[1]);
        }
        if (!empty($result2[0][0])) {
            $allFiles = array_merge($allFiles, $result2[1]);
        }

        // Сохраняем вложения
        if (!empty($this->_files)) {
            $attachments = $this->getAttachments();
            foreach ($this->_files as $file) {
                // Если такое вложение существует, пропускаем.
                if (array_key_exists($file, $attachments)) {
                    continue;
                }
                $requestFile = new CommentFiles;
                $requestFile->file_id = (int)$file;
                $requestFile->comment_id = $this->id;
                $requestFile->save(false);

                $fileObj = Files::model()->findByPk($file);
                $afiles[] = Yii::getPathOfAlias('webroot') . '/uploads/' . $fileObj->file_name;
            }
        }
        if (isset($_POST['Comments']['add_temp']) and $_POST['Comments']['add_temp'] == '1') {
            $find = ReplyTemplates::model()->countBySQL('select COUNT(*) from reply_templates where name like :name',
                array(':name' => '%' . Yii::t('main-ui', 'Solution for the ticket: ') . $this->r->Name . '%'));
            $template = new ReplyTemplates;
            $template->name = ($find > 0) ? Yii::t('main-ui',
                'Solution for the ticket: ') . $this->r->Name . ' (' . ($find + 1) . ')' : Yii::t('main-ui',
                'Solution for the ticket: ') . $this->r->Name;
                $template->content = $this->comment;
                $template->save();
            }

            if (isset($_POST['Comments']['add_kb']) and $_POST['Comments']['add_kb'] == '1') {
                $template = new Knowledge;
                $kbcat = Categories::model()->findByPk(Yii::app()->params->kbcategory);
                $user = CUsers::model()->findByPk(Yii::app()->user->id);
                $find = Knowledge::model()->countBySQL('select COUNT(*) from brecords where name like :name',
                    array(':name' => '%' . Yii::t('main-ui', 'Solution for the ticket: ') . $this->r->Name . '%'));
                $template->parent_id = Yii::app()->params->kbcategory ? Yii::app()->params->kbcategory : 1;
                $template->bcat_name = $kbcat->name;
                $template->created = date('d.m.Y H:i');
                $template->author = $user->fullname;
                $template->name = ($find > 0) ? Yii::t('main-ui',
                    'Solution for the ticket: ') . $this->r->Name . ' (' . ($find + 1) . ')' : Yii::t('main-ui',
                    'Solution for the ticket: ') . $this->r->Name;
                    $template->content = $this->comment;
                    $template->access = $kbcat->access;
                    $template->save(false);
                }
        $is_console = PHP_SAPI == 'cli'; //if is console app return bool
        $form_web = false;
        if (!$is_console) {
            if (isset(Yii::app()->user->id)) {
                $from_web = true;
            }
        }
        if (!Yii::app()->request->getIsAjaxRequest() and $from_web == true) {
            /* TELEGRAM SEND */
            $ticket = Request::model()->findByPk($this->rid);
            $comment_text = "Пользователь " . $this->author . " добавил комментарий к заявке [№ ".$ticket->id."]: \r" . trim(strip_tags($this->comment)) . " \r" . Yii::app()->params['homeUrl'] . "/request/view/" . $ticket->id;
            if (isset($ticket->tchat_id) and !empty($ticket->tchat_id) and Yii::app()->params['TBotEnabled'] == 1 and $this->show == 0) {
                $telegram = new Api(Yii::app()->params['TBotToken']); //BotFather bot token
                $telegram->sendMessage([
                    'chat_id' => $ticket->tchat_id,
                    'parse_mode' => 'HTML',
                    'text' => $comment_text
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
            if (isset($ticket->viber_id) && !empty($ticket->viber_id) && 1 == Yii::app()->params['VBotEnabled'] and $this->show == 0) {
                $apiKey = Yii::app()->params['VBotToken'];
                $botSender = new Sender([
                    'name' => 'Univef bot',
                    //  'avatar' => 'https://developers.viber.com/images/favicon.ico',
                ]);
//                $log = new Logger('bot');
//                $log->pushHandler(new StreamHandler(__DIR__ . '/../runtime/vbot.log'));
                $bot = new Bot(['token' => $apiKey]);
                $bot->getClient()->sendMessage(
                    (new \Viber\Api\Message\Text())
                    ->setSender($botSender)
                    ->setReceiver($ticket->viber_id)
                    ->setText($comment_text)
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
            if (isset($ticket->msbot_id) && !empty($ticket->msbot_id) && !empty($ticket->msbot_params) && 1 == Yii::app()->params['MSBotEnabled'] and $this->show == 0) {

                $microsoftBot = new MicrosoftBotFramework(Yii::app()->params['MSBotAppId'],
                    Yii::app()->params['MSBotAppPassword']);
                $microsoftBot->sendMessage($comment_text, json_decode($ticket->msbot_params, true));

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
                            $microsoftBot->sendAttach($attachments, json_decode($ticket->msbot_params, true));
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
                            $microsoftBot->sendAttach($attachments, json_decode($ticket->msbot_params, true));
                        }
                    }
                }

            }
            /* END MSBOT SEND */

            /* WBOT SEND */
            if (isset($ticket->wbot_id) && !empty($ticket->wbot_id) && 1 == Yii::app()->params['WBotEnabled'] and $this->show == 0) {
                $api = new ChatApi(
                    Yii::app()->params['WBotToken'],
                    Yii::app()->params['WBotApiUrl']
                );
                $api->sendMessage($ticket->wbot_id, $comment_text);

                if (isset($afiles)) {
                    foreach ($afiles as $file) {
                        $fname = $file;
                        $dir = substr(strrchr($file, "/"), 1);
                        $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                        if (is_dir($fname) or !file_exists($fname)) {
                            continue;
                        }

                        $api->sendFile($ticket->wbot_id,Yii::app()->params['homeUrl'] . '/uploads/' . $fileObj->file_name, $fileObj->name);
                    }
                }
            }
            /* END WBOT SEND */
}

return parent::afterSave();
}

    /**
     *
     * @throws \CDbException
     */
    public function beforeDelete()
    {
        // Удаляем связи с файлами
        foreach ($this->commentFiles as $commentFile) {
            /** @var Files $file */
            $file = $commentFile->file;
            /** @var RequestFiles $file */
            $commentFile->delete();
            $file->delete();
        }
        return parent::beforeDelete();
    }

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

    public function getCommentWithFiles()
    {
        $comment = $this->comment;
        $files = explode(",", $this->files);
        foreach ((array)$files as $file) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fname = Yii::getPathOfAlias('webroot') . '/media/' . $this->r->id . '/' . $this->id . '/' . $file;
            $mime = finfo_file($finfo, $fname);
            $image = explode("/", $mime);
            if ($image[0] == 'image') {
                $comment .= '<b><span class="icon-paper-clip"></span> <a class="thumb" href="/media/' . $this->r->id . '/' . $this->id . '/' . $file . '">' . $file . '<span><img src="/media/' . $this->r->id . '/' . $this->id . '/' . $file . '"/></span></a></b>' . '  ';
            } else {
                $comment .= '<b><span class="icon-paper-clip"></span> <a href="/media/' . $this->r->id . '/' . $this->id . '/' . $file . '">' . $file . '</a></b>' . '  ';
            }
            finfo_close($finfo);
        }
        return $comment;
    }

}
