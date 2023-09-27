<?php

/**
 * Class JiraForm
 */
class JiraForm extends CFormModel
{
    /**
     * @var int
     */
    public $enabled;

    /**
     * @var string
     */
    public $domen;

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
     * @var array
     */
    public $services;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['enabled', 'numerical', 'integerOnly' => true],
            ['services', 'safe'],
            ['enabled, domen, user, password, project, issuetype', 'required'],
            ['user, domen, password, project, issuetype', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'enabled' => Yii::t('main-ui', 'Enabled'),
            'domen' => Yii::t('main-ui', 'Domen'),
            'user' => Yii::t('main-ui', 'User'),
            'password' => Yii::t('main-ui', 'Password'),
            'project' => Yii::t('main-ui', 'Project'),
            'issuetype' => Yii::t('main-ui', 'Issue Type'),
            'services' => Yii::t('main-ui', 'Services'),
        ];
    }
}
