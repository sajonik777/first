<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__FILE__) . '/../vendors/phpmailer/vendor/autoload.php';

class SendMail
{
    /**
     * Отправка почты
     * @param str $to
     * @param str $subject
     * @param str $message
     */
    static function send($to, $subject, $message, $afiles, $config)
    {
        $mail = new PHPMailer(true);
        if (isset($config) AND !empty($config)) {
            $filename = dirname(__FILE__) . '/../config/' . $config;
            $content = file_get_contents($filename);
            $getmailconf = unserialize(base64_decode($content));

            if(!empty($getmailconf['getmailsmhost'])){
                $smdebug = (isset($getmailconf['getmailsmdebug']) AND $getmailconf['getmailsmdebug'] == 1) ? 3 : 0;
                $smhost = $getmailconf['getmailsmhost'];
                $smport = $getmailconf['getmailsmport'];
                $smtpauth = $getmailconf['getmailsmtpauth'];
                $smusername = $getmailconf['getmailsmusername'];
                $smpassword = $getmailconf['getmailsmpassword'];
                $smfrom = $getmailconf['getmailsmfrom'];
                $smfromname = $getmailconf['getmailsmfromname'];
                $smsec = $getmailconf['getmailsmsec'];
                $smignoressl = $getmailconf['getmailsmignoressl'];
            } else {
                $smdebug = (isset(Yii::app()->params['smdebug']) AND Yii::app()->params['smdebug'] == 1) ? 3 : 0;
                $smhost = Yii::app()->params['smhost'];
                $smport = Yii::app()->params['smport'];
                $smtpauth = Yii::app()->params['smtpauth'];
                $smusername = Yii::app()->params['smusername'];
                $smpassword = Yii::app()->params['smpassword'];
                $smfrom = Yii::app()->params['smfrom'];
                $smfromname = Yii::app()->params['smfromname'];
                $smsec = Yii::app()->params['smsec'];
                $smignoressl = Yii::app()->params['smignoressl'];
            }
        } else {
            $smdebug = (isset(Yii::app()->params['smdebug']) AND Yii::app()->params['smdebug'] == 1) ? 3 : 0;
            $smhost = Yii::app()->params['smhost'];
            $smport = Yii::app()->params['smport'];
            $smtpauth = Yii::app()->params['smtpauth'];
            $smusername = Yii::app()->params['smusername'];
            $smpassword = Yii::app()->params['smpassword'];
            $smfrom = Yii::app()->params['smfrom'];
            $smfromname = Yii::app()->params['smfromname'];
            $smsec = Yii::app()->params['smsec'];
            $smignoressl = Yii::app()->params['smignoressl'];
        }

        try {
            //Server settings
            $mail->SMTPDebug = $smdebug;  // Verbose debug output
            $mail->CharSet = 'UTF-8';                             // Charset to utf-8
            $mail->setLanguage('ru', dirname(__FILE__) . '/../vendors/phpmailer/vendor/phpmailer/phpmailer/language/'); // Set language codes
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $smhost;           // Specify main and backup SMTP servers
            if ($smtpauth == 1) {
                $mail->SMTPAuth = true;                             // Enable SMTP authentication
            } else {
                $mail->SMTPAuth = false;                            // Disable SMTP authentication
            }
            $mail->SMTPKeepAlive = true;                          // Keep alive SMTP connection
            //$mail->SingleTo = true;                               // Sending each single message
            $mail->Username = $smusername;   // SMTP username
            $mail->Password = $smpassword;   // SMTP password
            $mail->setFrom($smfrom, $smfromname); //From email and email
            $mail->addReplyTo($smfrom, $smfromname); //Reply to

            if (!empty($smsec)) {
                $mail->SMTPSecure = $smsec;    // Enable SSL or TLS encryption
            } else {
                $mail->SMTPSecure = '';                          // Disable SSL or TLS encryption
            }
            $mail->Port = $smport;           // TCP port to connect to

            if (isset($smignoressl) AND $smignoressl == 1) { //ignoring SSL or TLS certificate verify
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }

            //Recipients
            if (is_array($to)) {
                $addr = implode(',', $to);
                //$mail->clearAddresses();
                foreach ($to as $value) {
                    if (!empty($value))
                        $mail->addAddress($value);
                }
            } else {
                //$mail->clearAddresses();
                $mail->addAddress($to);
            }
            //Attachments
            if ($afiles) {
                foreach ($afiles as $path) {
                    $os_type = DetectOS::getOS();
                    $dir = substr(strrchr($path, "/"), 1);
                    $fileObj = Files::model()->findByAttributes(['file_name' => $dir]);
                    $fname = ($os_type == 2) ? iconv('WINDOWS-1251', 'UTF-8', $dir) : $dir;
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
}
