<?php

/**
 * ChangePassword class.
 * ChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class ResetForm extends CFormModel
{
    public $password;
    public $verifyPassword;


    public function rules()
    {
        return array(
            array('password, verifyPassword', 'required'),
            array('password, verifyPassword', 'length', 'max' => 50, 'min' => 4, 'message' => Yii::t('main-ui', 'Incorrect password (minimal length 4 symbols).')),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('main-ui', 'Retype Password is incorrect.')),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'password' => Yii::t('main-ui', 'New password'),
            'verifyPassword' => Yii::t('main-ui', 'Repeat new password'),
        );
    }

}