<?php

/**
 * Class PushAPI
 */
class PushAPI extends CComponent
{
    /**
     * @var string
     */
    static public $api_key;

    /**
     * @var string
     */
    static public $script_config;

    /**
     * @var int
     */
    static public $time_to_live = 300;

    /**
     * Добавляет пользователя в бд.
     *
     * @param $endpoint
     * @param $user
     * @return string
     * @throws CDbException
     * @throws CException
     * @internal param $subscriber_id
     */
    public static function add($endpoint, $user)
    {
        $sql = "SELECT COUNT(*) FROM `subscribers` WHERE `user_id`=$user and `subscriber_id`='$endpoint'";
        /** @var CDbConnection $connection */
        $connection = Yii::app()->db;
        $count = $connection->createCommand($sql)->queryScalar();
        if (!$count) {
            $chrome = 1;
            $sql = "INSERT INTO `subscribers` (`user_id`, `subscriber_id`, `chrome`) VALUES ({$user}, '{$endpoint}', {$chrome}) ON DUPLICATE KEY UPDATE `user_id`={$user}, `subscriber_id`='{$endpoint}', `chrome`={$chrome};";
            $count = $connection->createCommand($sql)->execute();
            if ($count) {
                return '{"response": "OK", "id": "' . $endpoint . '"}';
            }

            return '{"response": "ERROR"}';

        }

        return '{"response": "OK"}';
    }

    /**
     * @param $user
     * @return bool|CDbDataReader|mixed|string
     * @throws CException
     */
    public static function getToken($user)
    {
        $sql = "SELECT `subscriber_id` FROM `subscribers` WHERE `user_id`=$user ";
        /** @var CDbConnection $connection */
        $connection = Yii::app()->db;

        return $connection->createCommand($sql)->queryScalar();
    }

    /**
     * Удаляет подписчика из бд.
     * @param $endpoint
     * @return string
     * @throws CDbException
     * @throws CException
     */
    public static function del($endpoint)
    {
        $sql = "SELECT COUNT(*) FROM `subscribers` WHERE `subscriber_id`='$endpoint'";
        /** @var CDbConnection $connection */
        $connection = Yii::app()->db;
        $count = $connection->createCommand($sql)->queryScalar();
        if ($count) {
            $sql = "DELETE FROM `subscribers` WHERE `subscriber_id`='$endpoint'";
            $count = $connection->createCommand($sql)->execute();
            if ($count) {
                return '{"response": "OK", "id": "' . $endpoint . '"}';
            }

            return '{"response": "ERROR"}';
        }

        return '{"response": "OK"}';
    }

    /**
     * Отсылает сообщение в браузер.
     * @param $endpoint
     * @param null $notification
     * @throws CDbException
     * @throws CException
     * @deprecated
     */
    public static function push($endpoint, $notification = null)
    {
        $data['notification'] = [
            'title' => 'Уведомление от Univef service desk!',
            'message' => "У вас новое уведомление!",
            'tag' => 'notification',
            'icon' => '/images/icon-192x192.png',
            'data' => '/?utm_source=push-api'
        ];

        $endpoint_parsed = parse_url($endpoint);

        $subscriber_id = end(explode('/', $endpoint_parsed['path']));

        if (null !== $notification and is_array($notification)) {
            foreach ($notification as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key] = $value;
                }
            }
        } else {
            $connection = Yii::app()->db;
            /** @var CDbConnection $connection */
            $sql = "SELECT p.* FROM `subscribers` s, `pushs` p WHERE s.`subscriber_id`='$subscriber_id' AND p.`user_id` = s.`user_id` ORDER BY id DESC LIMIT 1";
            $row = $connection->createCommand($sql)->queryRow();
            if ($row) {
                $data['notification']['message'] = $row['notification'];
                $data['notification']['data'] = $row['url'];
                $sql = "DELETE FROM `pushs` WHERE `user_id`={$row['user_id']}";
                $connection->createCommand($sql)->execute();
            }
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * @param $notification
     * @param $url
     * @param $subscriber_id
     * @return bool
     */
    public static function sendPush($notification, $url, $subscriber_id)
    {
        self::loadConfig();
        $request_body = [
            'to' => $subscriber_id,
            'notification' => [
                'title' => 'Уведомление от Univef service desk!',
                'body' => $notification,
                'icon' => '/images/icon-192x192.png',
                'click_action' => $url,
            ],
        ];
        $fields = json_encode($request_body);
        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . self::$api_key,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            $res = json_decode($response, true);
            if (isset($res['success'])) {
                return $res['success'] == 1;
            }
        }

        return false;
    }

    /**
     * Отправка уведомлений на пуш сервер.
     *
     * @param $browser
     * @param $subscriber_id
     * @return mixed
     * @deprecated
     */
    public static function send_push_message($browser, $subscriber_id)
    {
        self::loadConfig();
        $ch = curl_init();
        switch ($browser) {
            case 'chrome':
                curl_setopt($ch, CURLOPT_URL, 'https://gcm-http.googleapis.com/gcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,
                    ['Authorization: key=' . self::$api_key, 'Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POSTFIELDS,
                    json_encode([
                        'registration_ids' => [$subscriber_id],
                        'data' => ['message' => 'send'],
                        'time_to_live' => self::$time_to_live,
                        'collapse_key' => 'test'
                    ])
                );
                break;
            case 'firefox':
                if (isset($_SERVER['HTTPS'])) {
                    exec('curl -H "TTL: 300" -X POST https://updates.push.services.mozilla.com/wpush/v1/' . $subscriber_id);
                } else {
                    curl_setopt($ch, CURLOPT_URL,
                        'https://updates.push.services.mozilla.com/wpush/v1/' . $subscriber_id);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    /** @noinspection CurlSslServerSpoofingInspection */
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['TTL: ' . self::$time_to_live]);
                }
                break;
        }
        $result = curl_exec($ch);
        $jr = json_decode($result);
        curl_close($ch);
        if (isset($jr->success) and 1 == $jr->success) {
            return true;
        }
        return false;
    }

    /**
     * Загружает конфигурационный файл.
     */
    private static function loadConfig()
    {
        $pushApiPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'pushApi.json';
        $pushApi = json_decode(file_get_contents($pushApiPath), true);
        self::$api_key = $pushApi['api_key'];
        self::$script_config = $pushApi['script_config'];
    }

    /**
     * @return string|null
     */
    public static function getScriptConfig()
    {
        self::loadConfig();
        return (self::$api_key && self::$script_config) ? self::$script_config : null;
    }
}
