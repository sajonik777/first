<?php

/**
 * Class TeamViewer
 */
class TeamViewer
{
    const URL_CREATE_SESSIONS = 'https://webapi.teamviewer.com/api/v1/sessions';

    const URL_ACCESS_TOKEN = 'https://webapi.teamviewer.com/api/v1/oauth2/token';

    const URL_PING = 'https://webapi.teamviewer.com/api/v1/ping';

    /**
     * @var resource
     */
    protected $curl;

    /**
     * TeamViewer constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
    }

    /**
     * TeamViewer destructor.
     */
    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     * @param string $access_token
     * @param array|null $data
     * @return bool|mixed
     */
    public function createSessions($access_token, array $data = null)
    {
        if ($this->curl) {
            if (null === $data) {
                $data = ['groupname' => 'univefservicedesk'];
            }
            curl_setopt($this->curl, CURLOPT_URL, static::URL_CREATE_SESSIONS);
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: Bearer {$access_token}",
            ]);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($this->curl);
            if ($response) {
                return json_decode($response, true);
            }
        }

        return false;
    }

    /**
     * @param string $code
     * @param string $client_id
     * @param string $client_secret
     * @return bool|string
     */
    public function createAccessToken($code, $client_id, $client_secret)
    {
        if ($this->curl) {
            curl_setopt($this->curl, CURLOPT_URL, static::URL_ACCESS_TOKEN);
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS,
                "grant_type=authorization_code&code={$code}&client_id={$client_id}&client_secret={$client_secret}");
//                "grant_type=authorization_code&code={$code}&redirect_uri={$redirect_uri}&client_id={$client_id}&client_secret={$client_secret}");
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($this->curl, CURLOPT_USERAGENT, 'Univef service desk agent 1.0');
            $response = curl_exec($this->curl);
            if ($response) {
                $token_data = json_decode($response, true);
                if (is_array($token_data) && isset($token_data['access_token'])) {
                    return $token_data['access_token'];
                }
            }
        }

        return false;
    }

    /**
     * @param string $client_id
     * @param string $redirect_uri
     * @return string
     */
    public function createUrl($client_id, $redirect_uri)
    {
        $url = "https://webapi.teamviewer.com/api/v1/oauth2/authorize?response_type=code&client_id={$client_id}&display=popup&redirect_uri={$redirect_uri}";
        return $url;
    }

    /**
     * @param string $access_token
     * @return bool
     */
    public function ping($access_token)
    {
        if ($this->curl) {
            curl_setopt($this->curl, CURLOPT_URL, static::URL_PING);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: Bearer {$access_token}",
            ]);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($this->curl);
            if ($response) {
                $valid = json_decode($response, true);
                if (isset($valid['token_valid'])) {
                    return (bool)$valid['token_valid'];
                }
            }
        }

        return false;
    }

    /**
     * @param string $access_token
     * @return bool|array
     */
    public function sessionsList($access_token)
    {
        if ($this->curl) {
            curl_setopt($this->curl, CURLOPT_URL, static::URL_CREATE_SESSIONS);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                "Authorization: Bearer {$access_token}",
            ]);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($this->curl);
            if ($response) {
                return json_decode($response, true);
            }
        }

        return false;
    }
}
