<?php

$file = __DIR__ . '/jira.inc';
$content = file_get_contents($file);
if ($content) {
    $content = base64_decode($content);
    if ($content) {
        $arr = unserialize($content);
        return [
            'class' => 'JiraTicket',
            'enabled' => $arr['enabled'],
            'user' => $arr['user'],
            'password' => $arr['password'],
            'project' => $arr['project'],
            'issuetype' => $arr['issuetype'],
            'services' => $arr['services'],
            'services' => $arr['services'],
        ];
    }
}

return [
    'class' => 'JiraTicket',
    'enabled' => false,
    'user' => 'user',
    'password' => 'password',
    'project' => 'project',
    'issuetype' => 'issuetype',
    'services' => 'services',
    'domen' => 'domen',
];
