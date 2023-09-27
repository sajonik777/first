<?php
define('version', '9.0505');
$oldlic = __DIR__ . '/protected/config/lic.inc';
if (!file_exists($oldlic)) {
    require(__DIR__ . '/protected/config/license.php');
}
define('ROOT_PATH', __DIR__);
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
$filename = 'protected/data/installer.lock';

if (!file_exists($filename) and !strpos($_SERVER['REQUEST_URI'], 'install')) {
    header("Location:install?lang=ru");
    exit;
}

$yii = __DIR__ . '/protected/vendors/yii/yii.php';
$config = __DIR__ . '/protected/config/main.php';

//debug level
defined('YII_DEBUG') or define('YII_DEBUG', true); //set "false" or "true" to disable or enable debug mode
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

if (true === YII_DEBUG) {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL & ~E_NOTICE);
} else {
    ini_set('display_errors', 'Off');
    error_reporting(0);
}

require_once($yii);
Yii::createWebApplication($config)->run();