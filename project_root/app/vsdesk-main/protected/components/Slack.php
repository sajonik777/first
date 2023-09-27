<?php

require __DIR__ . '/../vendors/slack/httpclient/src/HttpClient.php';

/**
 * Send messages to Slack channel.
 */
class Slack
{
    /**
     * Value "Webhook URL" from slack.
     * @var string
     */
    public $webhookUrl;
    
    /**
     * Bot username. Defaults to application name.
     * @var string
     */
    public $username;
    /**
     * Bot icon url
     * @var string
     */
    public $icon_url;
    /**
     * Bot icon emoji
     * @var string
     */
    public $icon_emoji;
    
    /**
     * Initializes the class.
     */
    public function init()
    {
        if (ini_get('date.timezone') == '') {
            date_default_timezone_set(Yii::app()->params['timezone']);
        }

        parent::init();
    }
    
    /**
     * Pushes messages to slack.
     */
    static function send($text = null, $emoji = null, $attachments = [], $channel = null)
    {
        $webhookUrl = Yii::app()->params['SlackWebhookURL'];
        $username = Yii::app()->params['SlackUsername'];
        $icon_url = Yii::app()->params['SlackIconURL'];
        $icon_emoji = Yii::app()->params['SlackEmojii'];

        $body = json_encode([
            'username' => $username,
            'icon_url' => $icon_url,
            'icon_emoji' => $icon_emoji,
            'text' => $text,
            'attachments' => $attachments,
        ], JSON_PRETTY_PRINT);
        
        $params = ['headers' => ['Content-Type: application/json']];
        HttpClient::from()->post($webhookUrl, $body, $params);
    }
}
