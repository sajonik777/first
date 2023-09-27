<?php

/**
 * Class OpenLdapComponent
 */
class OpenLdapComponent
{
    /**
     * @var bool
     */
    public $ad_enabled;

    /**
     * @var string
     */
    public $host;

    /**
     * @var integer
     */
    public $port = 389;

    /**
     * @var string
     */
    public $account;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $baseDN;

    /**
     * @var string
     */
    public $usersDN;

    /**
     * @var string
     */
    public $groupsDN;

    /**
     * @var false|resource
     */
    private $ldapConnection;

    /**
     * @var bool
     */
    private $ldapBind;

    /**
     * OpenLdapComponent constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $attribute => $value) {
                $this->$attribute = $value;
            }
        }
    }

    /**
     * @return OpenLdapComponent
     */
    public function init()
    {
        return $this->__construct();
    }

    /**
     * @return bool
     */
    public function connect()
    {
        $this->ldapConnection = ldap_connect($this->host, $this->port);
        if (false === $this->ldapConnection) {
            return false;
        }

        ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);

        $this->ldapBind = @ldap_bind($this->ldapConnection, $this->account, $this->password);

        return $this->ldapBind;
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate(string $username, string $password)
    {
        if (null === $this->ldapBind) {
            $this->connect();
        }
        return $this->ldapBind = @ldap_bind($this->ldapConnection, 'uid=' . $username . ',' . $this->usersDN,
            $password);
    }

    /**
     * @return $this
     */
    public function user()
    {
        if (null === $this->ldapBind) {
            $this->connect();
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function group()
    {
        if (null === $this->ldapBind) {
            $this->connect();
        }
        return $this;
    }

    /**
     * @param $group
     * @param null $recursive
     * @return bool|array
     */
    public function members($group, $recursive = null)
    {
        if (null === $this->ldapBind) {
            $this->connect();
        }

        if ($group === null || !$this->ldapBind) {
            return false;
        }

        $filter = "(cn={$group})";
        $sr = ldap_search($this->ldapConnection, $this->groupsDN, $filter);
        $entries = ldap_get_entries($this->ldapConnection, $sr);

        //OPENLDAP SEARCH STRING
        if (isset($entries[0]['memberuid']['count']) && (int)$entries[0]['memberuid']['count'] >= 1) {
            unset($entries[0]['memberuid']['count']);
            return $entries[0]['memberuid'];
        }

        //FREE IPA SEARCH STRING
//        $members = [];
//        if (isset($entries[0]['member']['count']) && (int)$entries[0]['member']['count'] >= 1) {
//            unset($entries[0]['member']['count']);
//            foreach ($entries[0]['member'] as $uid => $value){
//                $value = strstr($value, '=');
//                $value = strstr($value, ',', true);
//                $value = str_replace('=', '', $value);
//                $members[] = $value;
//            }
//            return $members;
//        }

        return false;
    }

    /**
     * @param $username
     * @return bool|array
     */
    public function groups($username)
    {
        if (null === $this->ldapBind) {
            $this->connect();
        }

        if ($username === null || !$this->ldapBind) {
            return false;
        }

        $filter = "(memberuid={$username})";
        //$filter = "(member=uid=".$username.",".$this->usersDN.")"; //FreeIpa FILTER
        $sr = ldap_search($this->ldapConnection, $this->groupsDN, $filter);
        $entries = ldap_get_entries($this->ldapConnection, $sr);

        $obj = [];
        if (isset($entries['count']) && (int)$entries['count'] >= 1) {
            unset($entries['count']);
            foreach ($entries as $entrie) {
                $obj[] = $entrie['cn'][0];
                if (isset($entrie['cn'][0]) && !in_array($entrie['cn'][0], $obj)) {
                    $obj[] = $entrie['cn'][0];
                }
            }
            return $obj;
        }

        return false;
    }


    /**
     * @param string $username
     * @param null $fields
     * @return bool|object
     */
    public function infoCollection(string $username, $fields = null)
    {
        if (null === $this->ldapBind) {
            $this->connect();
        }

        if ($username === null || !$this->ldapBind) {
            return false;
        }

        $filter = "(uid={$username})";
        if (null === $fields) {
            $fields = [
                'cn',
                'givenname',
                'sn',
                'mail',
            ];
        }

        $sr = ldap_search($this->ldapConnection, $this->usersDN, $filter, $fields);
        $entries = ldap_get_entries($this->ldapConnection, $sr);
        //var_dump($entries);

        $obj = [];
        if (isset($entries[0])) {
            foreach ($fields as $field) {
                if (isset($entries[0][$field][0])) {
                    $obj[$field] = $entries[0][$field][0];
                }
            }
            if (isset($obj['sn'], $obj['givenname']) && !isset($obj['displayname'])) {
                $obj['displayname'] = $obj['sn'] . ' ' . $obj['givenname'];
            }

            return (object)$obj;
        }

        return false;
    }
}
