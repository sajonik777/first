<?php
$filename = 'protected/data/installer.lock';
file_exists($filename) ? $newInstall = false : $newInstall = true;
$ldap = require(dirname(__FILE__) . '/ad.php');
Yii::setPathOfAlias('editable', dirname(__FILE__).'/../extensions/x-editable');
Yii::setPathOfAlias('yiicod.mailqueue', dirname(__FILE__).'/../extensions/mailqueue');
Yii::setPathOfAlias('yiicod.mailqueue.models', dirname(__FILE__).'/../extensions/mailqueue/models');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return [
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Univef service desk',
    'language' => isset($_GET['lang']) ? $_GET['lang'] : 'ru',
    'sourceLanguage' => 'en_US',

    // Uncomment 'adLdap' to use LDAP auth
    'preload' => [
        'log',
        'adLDAP',
        'mailqueue',
        'bootstrap',
        !$newInstall ? 'languages' : null,
    ],

    // autoloading model and component classes
    'import' => [
        'application.models.*',
        'application.components.*',
        'application.modules.news.models.*',
        'application.modules.knowledge.models.*',
        'application.helpers.*',
    ],

    'modules' => [
        // here includes modules
        'install',
        'news',
        'knowledge',
        'import',
        'crm',
        //'api',
        'backup' => ['path' => __DIR__ . '/../_backup/'],
        'gii' => [
            'class' => 'system.gii.GiiModule',
            'password' => 'univef',
            'generatorPaths' => [
                'bootstrap.gii',
            ],
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => ["*"],
            // 'ipFilters' => ['127.0.0.1', '::1', '185.8.183.102'],
        ],
    ],
    // application components
    'components' => [
        'assetManager' => [
            'linkAssets' => true,
        ],
        'sms' => require(dirname(__FILE__) . '/sms.php'),
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
        'languages' => [
            'class' => 'Languages',
        ],
        'editable' => array(
            'class'     => 'editable.EditableConfig',
            'form'      => 'bootstrap',        //form style: 'bootstrap', 'jqueryui', 'plain' 
            'mode'      => 'inline',            //mode: 'popup' or 'inline'  
            'defaults'  => array(              //default settings for all editable elements
               'emptytext' => Yii::t('main-ui', 'Not set')
            )
        ), 
        'request' => [
            'enableCsrfValidation' => true,
            'class' => 'HttpRequest',
            'noCsrfValidationRoutes' => [
                '^api.*$',
                'getmsgcount',
                'getprivcount',
                'push'
            ],
        ],
        'session' => !$newInstall ? [
            'class' => 'system.web.CDbHttpSession',
            'connectionID' => 'db',
            'autoCreateSessionTable' => 'false',
        ] : null,
        // 'mailer' => [
        //     'class' => 'application.extensions.mailer.EMailer',
        //     'pathViews' => 'application.views.email',
        //     'pathLayouts' => 'application.views.email.layouts'
        // ],
        'ldap' => require(dirname(__FILE__) . '/ad.php'),
        'ldap_conf' => require(dirname(__FILE__) . '/ad_conf.php'),
        'authManager' => [
            'class' => !$ldap['ad_enabled'] == 1 ? 'PhpAuthManager' : 'LdapAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => ['guest'],

        ],

        /*'cache' => array(
            'class' => 'system.caching.CFileCache'
        ),*/

        'bootstrap' => [
            'class' => 'ext.bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ],
        'user' => [
            'class' => !$ldap['ad_enabled'] == 1 ? 'WebUser' : 'LdapUser',
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ],
        // uncomment the following to enable URLs in path-format

        'urlManager' => [
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => [
                'favicon.ico' => NULL,
                'robots.txt' => NULL,
                'apple-touch-icon.png' => NULL,
                'apple-touch-icon-precomposed.png' => NULL,
                'apple-touch-icon-120x120.png' => NULL,
                'apple-touch-icon-120x120-precomposed.png' => NULL,
                '/js/jquery.resizableColumns.min.js.map' => NULL,
                // REST patterns
                ['api/list', 'pattern' => 'api/<model:\w+>', 'verb' => 'GET'],
                ['api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'GET'],
                ['api/update', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'PUT'],
                ['api/delete', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'DELETE'],
                ['api/create', 'pattern' => 'api/<model:\w+>', 'verb' => 'POST'],
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'addsubscriber' => 'site/addsubscriber',
                'delsubscriber' => 'site/delsubscriber',
                'push' => 'site/push'
            ],
        ],
        'image'=>array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
//            'params'=>array('directory'=>'/opt/local/bin'),
        ),
        'db' => !$newInstall ? require(__DIR__ . '/dbconfig.php') : null,

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => !$newInstall ? [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CDbLogRoute',
                    'except' => 'exception.CHttpException.404',
                    'levels' => 'created, updated, deleted, info, error, warning',
                    'connectionID' => 'db',
                ],
                // uncomment the following to show log messages on web pages
                [
                    'class' => 'CProfileLogRoute',
                    'levels' => 'error, warning, trace, profile, info',
                    'enabled' => true,
                ],
                [
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels' => 'error, warning, trace, profile, info',
                    //'levels'=>'profile', // sql only
                ],
            ],
        ] : null,
    ],
    'params' => require(dirname(__FILE__) . '/params.php'),
];
