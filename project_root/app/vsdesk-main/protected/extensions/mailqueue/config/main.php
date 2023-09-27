<?php

return [
    'modelMap' => [
        'MailQueue' => [
            'alias' => 'yiicod\mailqueue\models\MailQueueModel',
            'class' => 'yiicod\mailqueue\models\MailQueueModel',
            'fieldFrom' => 'from',
            'fieldTo' => 'to',
            'fieldSubject' => 'subject',
            'fieldBody' => 'body',
            'fieldPriority' => 'priority',
            'fieldAttachs' => 'attachs',
            'fieldStatus' => 'status',
            'fieldCreateDate' => 'createDate',
            'fieldUpdateDate' => 'updateDate',
            'status' => [
                'sended' => 1,
                'unsended' => 0,
                'failed' => 2,
            ],
        ],
    ],
    'commandMap' => [
        'mailQueue' => [
            'class' => 'yiicod\mailqueue\commands\MailQueueCommand',
            'condition' => 'status=:unsend OR status=:failed',
            'params' => [':unsend' => 0, ':failed' => 0],
            'limit' => 60,
        ],
    ],
    'mailer' => 'phpMailer',
    'components' => [
        'mailQueue' => [
            'class' => 'yiicod\mailqueue\components\MailQueue',
            'afterSendDelete' => false,
        ],
    ],
];
