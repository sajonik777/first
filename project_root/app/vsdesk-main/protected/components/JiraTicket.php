<?php

/**
 * Class JiraTicket
 */
class JiraTicket extends CApplicationComponent
{
    const CREATE_TICKET_URL = '{domen}/rest/api/2/issue';
    const CREATE_COMMENT_URL = '{domen}/rest/api/2/issue/{key}/comment';

    /**
     * @var int
     */
    public $enabled;

    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $project;

    /**
     * @var string
     */
    public $issuetype;

    /**
     * @param $event
     */
    public static function createJiraTicket($event)
    {
        if (!(bool)Yii::app()->params['JiraEnabled']) {
            return;
        }
        $services = Yii::app()->params['JiraServices'];
        if (empty($services)) {
            return;
        }
        $service_id = $event->sender->service_id;
        if (!in_array($service_id, $services)) {
            return;
        }

        $id = (int)$event->sender->id;
        $name = $event->sender->Name;
        $content = strip_tags($event->sender->Content);
        $user_name = $event->sender->fullname;
        $url = Yii::app()->params['homeUrl'] . '/request/view/' . $id;
        $ticket = "Пользователь {$user_name} создал новую заявку {$url} \r\n{$content}";

        // Новая таска
        $data = [
            'fields' => [
                'project' => [
                    'key' => Yii::app()->params['JiraProject'],
                ],
                'summary' => $name,
                'description' => $ticket,
                'assignee' => [
                    'name' => '-1',
                ],
                'priority' => [
                    'name' => $event->sender->Priority,
                ],
                'issuetype' => [
                    'name' => Yii::app()->params['JiraIssuetype'],
                ],
            ],
        ];

        $url = str_replace('{domen}', Yii::app()->params['JiraDomen'], static::CREATE_TICKET_URL);

        $response = static::send($url, $data);
        if (!isset($response['errorMessages']) && isset($response['key'])) {
            Request::model()->updateByPk($id, ['jira' => $response['key']]);
        } else {
            Yii::log('Jira ERROR: ' . $response['errorMessages'][0], CLogger::LEVEL_ERROR);
        }
    }

    /**
     * @param $event
     */
    public static function createJiraComment($event)
    {
        if (!(bool)Yii::app()->params['JiraEnabled']) {
            return;
        }
        $request = $event->sender->r;
        if (null == $request->jira) {
            return;
        }
        $user_name = $event->sender->author;
        $content = strip_tags($event->sender->comment);

        $comment = "Пользователь {$user_name} добавил новый комментарий: \r\n{$content}";

        // Новый коммент
        $data = [
            'body' => $comment,
        ];

        $url = str_replace('{key}', $request->jira, static::CREATE_COMMENT_URL);
        $url = str_replace('{domen}', Yii::app()->params['JiraDomen'], $url);

        $response = static::send($url, $data);
        var_dump($url);
        if (isset($response['errorMessages'])) {
            Yii::log('Jira ERROR: ' . $response['errorMessages'][0], CLogger::LEVEL_ERROR);
        }
    }

    /**
     * @param string $url
     * @param array $data
     * @return array
     */
    public static function send($url, $data)
    {
        $username = Yii::app()->params['JiraUser'];
        $password = Yii::app()->params['JiraPassword'];
        if ($username && $password) {
            $ch = curl_init($url); // Новый тикет
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            try {
                $return = curl_exec($ch);
                $return = json_decode($return, true);
            } catch (Exception $e) {
                $return = ['errorMessages' => [$e->getMessage()]];
            }
            curl_close($ch);
        } else {
            $return = ['errorMessages' => ['Некорректный конфиг']];
        }

        return $return;
    }
}
