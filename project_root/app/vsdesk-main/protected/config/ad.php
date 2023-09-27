<?php
if (isset($_POST['LoginForm']['domain']) and !empty($_POST['LoginForm']['domain'])) {
    $file = __DIR__ . '/' . $_POST['LoginForm']['domain'];
} else {
    $file = __DIR__ . '/ad.inc';
}
$content = file_get_contents($file);
$arr = unserialize(base64_decode($content));

if ('openldap' === $arr['type']) {
    return CMap::mergeArray(
        [
            'class' => 'OpenLdapComponent',
        ],
        [
            'type' => $arr['type'],
            'ad_enabled' => $arr['ad_enabled'],
            'host' => $arr['host'],
            'account' => $arr['account'],
            'password' => $arr['password'],
            'baseDN' => $arr['baseDN'],
            'usersDN' => $arr['usersDN'],
            'groupsDN' => $arr['groupsDN'],
            'accountSuffix' => $arr['accountSuffix'],
            'fastAuth' => 0,
        ]
    );
}

return CMap::mergeArray(
    [
        'class' => 'LdapComponent',
    ],
    [
        'type' => $arr['type'],
        'ad_enabled' => $arr['ad_enabled'],
        'baseDn' => $arr['basedn'],
        'accountSuffix' => $arr['accountSuffix'],
        'domainControllers' => [$arr['domaincontrollers']],
        'adminUsername' => $arr['adminusername'],
        'adminPassword' => $arr['adminpassword'],
        'fastAuth' => $arr['fastAuth'],
    ]
);
