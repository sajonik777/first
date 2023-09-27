<?php
Yii::setPathOfAlias('yiicod.mailqueue', dirname(__FILE__).'/../extensions/mailqueue');
Yii::setPathOfAlias('yiicod.mailqueue.models', dirname(__FILE__).'/../extensions/mailqueue/models');
Yii::setPathOfAlias('yiicod.mailqueue.commands', dirname(__FILE__).'/../extensions/mailqueue/commands');
Yii::setPathOfAlias('yiicod.mailqueue.components', dirname(__FILE__).'/../extensions/mailqueue/components');
Yii::setPathOfAlias('yiicod.cron.commands.behaviors', dirname(__FILE__).'/../extensions/cron/commands/behaviors');
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Cron',

    // preloading 'log' component
    'preload'=>array('log', 'mailqueue'),
    'import'=>array(
        'application.components.*',
        'application.models.*',
    ),

    // application components
    'components'=>array(
       'db'=>require(dirname(__FILE__). '/dbconfig.php'),
        'ldap' => require(dirname(__FILE__) . '/ad.php'),
        'ldap_conf' => require(dirname(__FILE__) . '/ad_conf.php'),
        'sms' => require(dirname(__FILE__).'/sms.php'),
        'mailqueue' => array(
            'class' => 'yiicod\mailqueue\MailQueue',
            'modelMap' => array(
                'MailQueue' => array(
                    'alias' => 'yiicod\mailqueue\models\MailQueueModel',
                    'class' => 'yiicod\mailqueue\models\MailQueueModel',
                    'fieldFrom' => 'from',
                    'fieldTo' => 'to',
                    'fieldSubject' => 'subject',
                    'fieldBody' => 'body',
                    'fieldAttachs' => 'attachs',
                    'fieldStatus' => 'status',
                    'status' => array(
                        'send' => 1,
                        'unsend' => 0,
                        'failed' => 0,
                    )
                )
            ),
            'mailer' => 'phpMailer',
            'components' => array(
                'mailQueue' => array(
                    'class' => 'yiicod\mailqueue\components\MailQueue',
                    'afterSendDelete' => true,
                ),
            ),
        ),
    'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CDbLogRoute',
                    'levels' => 'created, updated, deleted, info, error, warning',
                    'connectionID' => 'db',
                ),
            ),
        ),
    ),
    'commandMap' => array(
        'mailQueue' => array(
            'class' => 'yiicod\mailqueue\commands\MailQueueCommand',
            'limit' => 60,
            'condition' => 'status=:unsend OR status=:failed',
            'params' => array(':unsend' => 0, ':failed' => 0),
        ),
    ),
    'params'=>require(dirname(__FILE__).'/params.php'),
);
