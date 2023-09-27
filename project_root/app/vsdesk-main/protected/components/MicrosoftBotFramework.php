<?php

/**
 * Class MicrosoftBotFramework
 */
class MicrosoftBotFramework
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $appPassword;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var array
     */
    private $requestActivity;

    /**
     * @var string
     */
    private $authRequestUrl = 'https://login.microsoftonline.com/botframework.com/oauth2/v2.0/token';

    /**
     * MicrosoftBotFramework constructor.
     * @param string $appId
     * @param string $appPassword
     */
    public function __construct(string $appId, string $appPassword)
    {
        $this->appId = $appId;
        $this->appPassword = $appPassword;
    }

    /**
     * @param string $url
     * @param string $data
     * @param bool $isJson
     * @return array
     */
    private function send($url, $data, $isJson = true)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($isJson) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: Bearer {$this->getAccessToken()}",
            ]);
        }

        try {
            $return = curl_exec($ch);
            $return = json_decode($return, true);
        } catch (Exception $e) {
            $return = ['errorMessages' => [$e->getMessage()]];
        }
        curl_close($ch);

        return $return;
    }

    /**
     * @return string|null
     */
    private function getAccessToken()
    {
        if ($this->accessToken !== null) {
            return $this->accessToken;
        }

        $post = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->appId,
            'client_secret' => $this->appPassword,
            'scope' => 'https://api.botframework.com/.default'
        ];

        $data = $this->send($this->authRequestUrl, http_build_query($post), false);

        if (isset($data['access_token']) && !empty($data['access_token'])) {
            $this->accessToken = $data['access_token'];
        } else {
            $this->accessToken = null;
        }

        return $this->accessToken;
    }

    /**
     * @param array $requestActivity
     * @return string
     */
    private function buildUrl($requestActivity)
    {
        $url = rtrim($requestActivity['serviceUrl'], '/')
            . '/v3/conversations/' . $requestActivity['conversation']['id']
            . '/activities/' . urlencode($requestActivity['replyToId']);

        return $url;
    }

    /**
     * @param array $requestActivity
     * @param string $text
     * @param array $attachments
     * @return array
     */
    private function buildMessage($requestActivity, $text, $attachments = null)
    {
        $message = [
            //Мы отвечаем обычным сообщением
            'type' => 'message',
            //Текст ответа на сообщение
            'text' => $text,
            'channelId' => (string)$requestActivity['channelId'],
            //Говорим, что ответ - это простой текст
            'textFormat' => 'plain',
            //Устанавливаем локаль ответа
            'locale' => 'ru-RU',
            //Устанавливаем внутренний ID активности, в контексте которого мы находимся (берем из поля id входящего POST-запроса с сообщением)
            'replyToId' => (string)$requestActivity['id'],
            //Сообщаем id и имя участника чата (берем из полей recipient->id и recipient->name входящего POST-запроса с сообщением, то есть id и name, которым было адресовано входящее сообщение)
            'from' => [
                'id' => (string)$requestActivity['recipient']['id'],
                'name' => (string)$requestActivity['recipient']['name'],
            ],
            //Устанавливаем id и имя участника чата, к которому обращаемся, он отправил нам входящее сообщение (берем из полей from->id и from->name входящего POST-запроса с сообщением)
            'recipient' => [
                'id' => (string)$requestActivity['from']['id'],
                'name' => (string)$requestActivity['from']['name'],
            ],
            //Устанавливаем id беседы, в которую мы отвечаем (берем из поля conversation->id входящего POST-запроса с сообщением)
            'conversation' => [
                'id' => (string)$requestActivity['conversation']['id'],
            ],
        ];

        if ($attachments) {
            unset($message['text']);
            $message['attachments'] = $attachments;
        }

        return $message;
    }

    /**
     * @param array $requestActivity
     * @param string $title
     * @param string $text
     * @param array $buttons
     * @return array
     */
    private function buildCardMessage($requestActivity, $title, $text, $buttons)
    {
        $message = [
            //Мы отвечаем обычным сообщением
            'type' => 'message',
            //Текст ответа на сообщение
            'attachments' => [
                [
                    'content' => [
                        'buttons' => $buttons,
                        'text' => $text,
                        'title' => $title,
                    ],
                    'contentType' => 'application/vnd.microsoft.card.hero',
                ]
            ],
            'channelId' => (string)$requestActivity['channelId'],
            //Говорим, что ответ - это простой текст
            'textFormat' => 'plain',
            //Устанавливаем локаль ответа
            'locale' => 'ru-RU',
            //Устанавливаем внутренний ID активности, в контексте которого мы находимся (берем из поля id входящего POST-запроса с сообщением)
            'replyToId' => (string)$requestActivity['id'],
            //Сообщаем id и имя участника чата (берем из полей recipient->id и recipient->name входящего POST-запроса с сообщением, то есть id и name, которым было адресовано входящее сообщение)
            'from' => [
                'id' => (string)$requestActivity['recipient']['id'],
                'name' => (string)$requestActivity['recipient']['name'],
            ],
            //Устанавливаем id и имя участника чата, к которому обращаемся, он отправил нам входящее сообщение (берем из полей from->id и from->name входящего POST-запроса с сообщением)
            'recipient' => [
                'id' => (string)$requestActivity['from']['id'],
                'name' => (string)$requestActivity['from']['name'],
            ],
            //Устанавливаем id беседы, в которую мы отвечаем (берем из поля conversation->id входящего POST-запроса с сообщением)
            'conversation' => [
                'id' => (string)$requestActivity['conversation']['id'],
            ],
        ];

        return $message;
    }

    /**
     * @param string $message
     * @param string|null $title
     * @param array|null $buttons
     * @return bool
     */
    public function replyToMessage($message, $title = null, $buttons = null)
    {
        if ($this->requestActivity === null) {
            return false;
        }

        $url = $this->buildUrl($this->requestActivity);

        if ($title !== null && $buttons !== null) {
            $messageData = $this->buildCardMessage($this->requestActivity, $title, $message, $buttons);
        } else {
            $messageData = $this->buildMessage($this->requestActivity, $message);
        }

        $this->send($url, json_encode($messageData));

        return true;
    }

    /**
     * @param string $message
     * @param null $requestActivity
     * @param array|null $buttons
     * @return bool
     */
    public function sendMessage($message, $requestActivity = null, $buttons = null)
    {
        if ($requestActivity !== null) {
            $this->requestActivity = $requestActivity;
        }

        if ($this->requestActivity === null) {
            return false;
        }

        $url = $this->buildUrl($this->requestActivity);

        if ($buttons !== null) {
            $messageData = $this->buildCardMessage($this->requestActivity, $message, $message, $buttons);
        } else {
            $messageData = $this->buildMessage($this->requestActivity, $message);
        }
//        $messageData = $this->buildMessage($this->requestActivity, $message);
        $this->send($url, json_encode($messageData));

        return true;
    }

    /**
     * @param array $attachments
     * @param null $requestActivity
     * @return bool
     */
    public function sendAttach($attachments, $requestActivity = null)
    {
        if ($requestActivity !== null) {
            $this->requestActivity = $requestActivity;
        }

        if ($this->requestActivity === null) {
            return false;
        }

        $url = $this->buildUrl($this->requestActivity);
        $messageData = $this->buildMessage($this->requestActivity, '', $attachments);
        $this->send($url, json_encode($messageData));

        return true;
    }

    /**
     * @return array|null
     */
    public function receiveMessage()
    {
        $request = file_get_contents('php://input');
        $requestActivity = json_decode($request, true);
        if (isset($requestActivity['id'])) {
            $this->requestActivity = $requestActivity;
        } else {
            $this->requestActivity = null;
        }

        return $this->requestActivity;
    }

    /**
     * @return bool
     */
    public function test()
    {
        return (bool)$this->getAccessToken();
    }

    /**
     * @param string $from
     * @param string $to
     */
    public function saveAttachment($from, $to)
    {
        $ch = curl_init($from);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer {$this->getAccessToken()}",
        ]);
        $data = curl_exec($ch);
        curl_close($ch);
        file_put_contents($to, $data);
    }
}
