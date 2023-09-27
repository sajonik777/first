<?php
$file = dirname(__FILE__) . '/params.inc';
$file3 = dirname(__FILE__) . '/request.inc';
$file4 = dirname(__FILE__) . '/appear.inc';
//$file5 = dirname(__FILE__) . '/lic.inc';
$file6 = dirname(__FILE__) . '/attach.inc';
$file10 = dirname(__FILE__) . '/tbot.inc';
$file_vbot = __DIR__ . '/vbot.inc';
$file_msbot = __DIR__ . '/msbot.inc';
$file_wbot = __DIR__ . '/whatsapp.inc';
$file11 = dirname(__FILE__) . '/slack.inc';
$file12 = dirname(__FILE__) . '/widget.inc';
$file13 = dirname(__FILE__) . '/portal.inc';
$file_teamviewer = __DIR__ . '/teamviewer.inc';
$file_jira = __DIR__ . '/jira.inc';
$content = file_get_contents($file);
$content3 = file_get_contents($file3);
$content4 = file_get_contents($file4);
//$content5 = file_get_contents($file5);
$content6 = file_get_contents($file6);
$content10 = file_get_contents($file10);
$content_vbot = file_get_contents($file_vbot);
$content_msbot = file_get_contents($file_msbot);
$content_wbot = file_get_contents($file_wbot);
$content11 = file_get_contents($file11);
$content12 = file_get_contents($file12);
$content13 = file_get_contents($file13);
$content_teamviewer = file_get_contents($file_teamviewer);
$content_jira = file_get_contents($file_jira);
$arr = unserialize(base64_decode($content));
$arr3 = unserialize(base64_decode($content3));
$arr4 = unserialize(base64_decode($content4));
//$arr5 = unserialize(base64_decode($content5));
$arr6 = unserialize(base64_decode($content6));
$arr10 = unserialize(base64_decode($content10));
$arr_vbot = unserialize(base64_decode($content_vbot));
$arr_msbot = unserialize(base64_decode($content_msbot));
$arr_wbot = unserialize(base64_decode($content_wbot));
$arr11 = unserialize(base64_decode($content11));
$arr12 = unserialize(base64_decode($content12));
$arr13 = unserialize(base64_decode($content13));
$arr_teamviewer = unserialize(base64_decode($content_teamviewer));
$arr_jira = unserialize(base64_decode($content_jira));
return CMap::mergeArray(
    $arr,
    $arr3,
    $arr4,
    //$arr5,
    $arr6,
    $arr10,
    $arr11,
    $arr12,
    $arr13,
    [
        'salt' => 'P@bl0',
        'someOption' => true,
        'selectPageCount' => [
            '10' => '10',
            '30' => '30',
            '50' => '50',
            '100' => '100',
            '500' => '500',
            '1000' => '1000',
            '1500' => '1500',
            '2000' => '2000',
            '3000' => '3000',
            '5000' => '5000',
        ],
    ],
    [
        'VBotEnabled' => isset($arr_vbot['enabled']) ? $arr_vbot['enabled'] : null,
        'VBotToken' => isset($arr_vbot['token']) ? $arr_vbot['token'] : null,
        'VBotUrl' => isset($arr_vbot['webhookUrl']) ? $arr_vbot['webhookUrl'] : null,
        'VBotMsg' => isset($arr_vbot['msg']) ? $arr_vbot['msg'] : null,
    ],
    [
        'MSBotEnabled' => isset($arr_msbot['enabled']) ? (int)$arr_msbot['enabled'] : null,
        'MSBotAppId' => isset($arr_msbot['appId']) ? $arr_msbot['appId'] : null,
        'MSBotAppPassword' => isset($arr_msbot['appPassword']) ? $arr_msbot['appPassword'] : null,
    ],
    [
        'TeamViewerEnabled' => isset($arr_teamviewer['enabled']) ? $arr_teamviewer['enabled'] : null,
        'TeamViewerClientId' => isset($arr_teamviewer['client_id']) ? $arr_teamviewer['client_id'] : null,
        'TeamViewerClientSecret' => isset($arr_teamviewer['client_secret']) ? $arr_teamviewer['client_secret'] : null,
        'TeamViewerAccessToken' => isset($arr_teamviewer['access_token']) ? $arr_teamviewer['access_token'] : null,
    ],
    [
        'JiraEnabled' => isset($arr_jira['enabled']) ? $arr_jira['enabled'] : false,
        'JiraUser' => isset($arr_jira['user']) ? $arr_jira['user'] : null,
        'JiraPassword' => isset($arr_jira['password']) ? $arr_jira['password'] : null,
        'JiraProject' => isset($arr_jira['project']) ? $arr_jira['project'] : null,
        'JiraIssuetype' => isset($arr_jira['issuetype']) ? $arr_jira['issuetype'] : null,
        'JiraServices' => isset($arr_jira['services']) ? $arr_jira['services'] : null,
        'JiraDomen' => isset($arr_jira['domen']) ? $arr_jira['domen'] : null,
    ],
    [
        'WBotEnabled' => isset($arr_wbot['enabled']) ? $arr_wbot['enabled'] : null,
        'WBotToken' => isset($arr_wbot['token']) ? $arr_wbot['token'] : null,
        'WBotApiUrl' => isset($arr_wbot['apiUrl']) ? $arr_wbot['apiUrl'] : null,
        'WBotWebhookUrl' => isset($arr_wbot['webhookUrl']) ? $arr_wbot['webhookUrl'] : null,
    ]
);

