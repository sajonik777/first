<?php

/**
 * RecoveryForm class.
 * RecoveryForm is the data structure for keeping
 * user recovery form data. It is used by the 'recovery' action of 'UserController'.
 */
class RecoveryForm extends CFormModel
{
    public $Email;
    public $Username;
    public $verifyCode;

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the recovery page
            'captcha' => array(
              'class' => 'CaptchaExtendedAction',
              'mode' => 'WORDS'
            ),
        );
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        $rules = array(
            // username and password are required
            array('Username, Email, verifyCode', 'required'),
            array('Email', 'email'),
            // password needs to be authenticated
            array('Email', 'checkexists'),
        );
        if ($this->verifyCode !== false && Yii::app()->user->isGuest) {
            $rules[] = array('verifyCode', 'CaptchaExtendedValidator', 'allowEmpty' => false);
        }
        return $rules;
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'Username' => Yii::t('main-ui', 'Username'),
            'Email' => Yii::t('main-ui', 'E-mail'),
        );
    }

    public function checkexists($attribute, $params)
    {
        if (!$this->hasErrors())  // we only want to authenticate when no input errors
        {
            $user = CUsers::model()->findByAttributes(array('Email' => $this->Email));

            if ($user) {
                $this->Username = $user->id;
            } else {
                $this->addError("Email", Yii::t('main-ui', 'Invalid email'));
            }
        }
    }

}
