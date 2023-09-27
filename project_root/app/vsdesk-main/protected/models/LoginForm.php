<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
    public $domain;
    public $username;
    public $password;
    public $rememberMe;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public
    function rules()
    {
        return array(
            // username and password are required
            array('username, password', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public
    function attributeLabels()
    {
        return array(
            'rememberMe' => Yii::t('main-ui', 'Remember me'),
            'username' => Yii::t('main-ui', 'Login'),
            'password' => Yii::t('main-ui', 'Password'),
            'domain' => Yii::t('main-ui', 'Domain'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public
    function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->domain = $this->domain;
            if (!$this->_identity->authenticate())
            if ($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID) {
                $this->addError('password', Yii::t('main-ui', 'Invalid username or password'));
            } else if($this->_identity->errorCode === UserIdentity::ERROR_UNKNOWN_IDENTITY) {
                $this->addError('password', Yii::t('main-ui', 'Your account has been disabled'));
            }
                
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public
    function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->domain = $this->domain;
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 3600 * 24; // 30 days OR 1 day
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
            return false;
    }
}
