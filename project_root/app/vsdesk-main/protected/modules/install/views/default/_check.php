<?php

define('ROOT_PATH', dirname('index.php'));
$path = ROOT_PATH . DIRECTORY_SEPARATOR;
$path_runtime = $path . 'protected' . DIRECTORY_SEPARATOR . 'runtime';
$path_assets = $path . 'assets';
$path_config = $path . 'protected' . DIRECTORY_SEPARATOR . 'config';
$path_installer = $path . 'protected' . DIRECTORY_SEPARATOR . 'data';
$path_media = $path . 'media';
$path_backup = $path . 'protected' . DIRECTORY_SEPARATOR . '_backup';

function isAvailable($func)
{
    if (ini_get('safe_mode')) return false;
    $disabled = ini_get('disable_functions');
    if ($disabled) {
        $disabled = explode(',', $disabled);
        $disabled = array_map('trim', $disabled);
        return !in_array($func, $disabled);
    }
    return true;
}
?>
<style>
    .red {
        color: red;
    }
    .green {
        color: green;
    }
    .yellow {
        color: yellow;
    }
</style>
<p><strong><?php echo Yii::t('install', 'Checking PHP version and modules:');?></strong></p>
<code><?php if (version_compare(PHP_VERSION, "5.5", ">=")) {
        echo '<span class="green">' .Yii::t('install', 'Your PHP version is above 5.5'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Your version of PHP below 5.5, then upgrade your version of PHP'). '<b> FAIL</b></span>';
    } ?></code><br>

<code><?php if (extension_loaded("imap")) {
        echo '<span class="green">' .Yii::t('install', 'Module PHP5-IMAP is installed'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Module PHP5-IMAP is not installed or not activated!'). '<b> FAIL</b></span>';
    } ?></code><br>

<code><?php if (extension_loaded("ldap")) {
        echo '<span class="green">' .Yii::t('install', 'Module PHP5-LDAP is installed'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Module PHP5-LDAP is not installed or not activated!'). '<b> FAIL</b></span>';
    } ?></code><br>

<code><?php if (extension_loaded("curl")) {
        echo '<span class="green">' .Yii::t('install', 'Module PHP5-CURL is installed'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Module PHP5-CURL is not installed or not activated!'). '<b> FAIL</b></span>';
    } ?></code><br>

<code><?php if (extension_loaded("gd")) {
        echo '<span class="green">' .Yii::t('install', 'Module PHP5-GD is installed'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Module PHP5-GD is not installed or not activated!'). '<b> FAIL</b></span>';
    } ?></code><br>

<code><?php if ($rewrite) {
        echo '<span class="green">' .Yii::t('install', 'Module APACHE-REWRITE is activated'). '<b> OK</b></span>';
    } else {
        echo '<span class="yellow">' .Yii::t('install', 'Module APACHE-REWRITE is not activated!'). '<b> FAIL</b></span>';
    } ?></code><br>
<code><?php if (isAvailable('exec') == true) {
        echo '<span class="green">' .Yii::t('install', 'Function EXEC is activated'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Function EXEC is not activated or disabled in php.ini!'). '<b> FAIL</b></span>';
    } ?></code><br>
<code><?php if (isAvailable('symlink') == true) {
        echo '<span class="green">' .Yii::t('install', 'Function SYMLINK is activated'). '<b> OK</b></span>';
    } else {
        echo '<span class="red">' .Yii::t('install', 'Function SYMLINK is not activated or disabled in php.ini!'). '<b> FAIL</b></span>';
    } ?></code><br>
<br>

<p><strong><?php echo Yii::t('install', 'Check the required files and folders write permission:');?></strong></p>

<code>sudo chown www-data:www-data -R protected/runtime/</code>
<b><?php if (is_writable($path_runtime) || @chmod($path_runtime, 0777) && is_writable($path_runtime)) {
        echo '<span class="green">OK</span>';
    } else {
        echo '<span class="red">FAIL</span>';
    } ?></b><br>

<code>sudo chown www-data:www-data -R assets/</code>
<b><?php if (is_writable($path_assets) || @chmod($path_assets, 0777) && is_writable($path_assets)) {
        echo '<span class="green">OK</span>';
    } else {
        echo '<span class="red">FAIL</span>';
    } ?></b><br>

<code>sudo chown www-data:www-data -R protected/config/</code>
<b><?php if (is_writable($path_config) || @chmod($path_config, 0777) && is_writable($path_config)) {
        echo '<span class="green">OK</span>';
    } else {
        echo '<span class="red">FAIL</span>';
    } ?></b><br>

<code>sudo chown www-data:www-data -R protected/data/</code>
<b><?php if (is_writable($path_installer) || @chmod($path_installer, 0777) && is_writable($path_installer)) {
        echo '<span class="green">OK</span>';
    } else {
        echo '<span class="red">FAIL</span>';
    } ?></b><br>

<code>sudo chown www-data:www-data -R media/</code>
<b><?php if (is_writable($path_media) || @chmod($path_media, 0777) && is_writable($path_media)) {
        echo '<span class="green">OK</span>';
    } else {
        echo '<span class="red">FAIL</span>';
    } ?></b><br>

<code>sudo chown www-data:www-data -R protected/_backup/</code>
<b><?php if (is_writable($path_backup) || @chmod($path_backup, 0777) && is_writable($path_backup)) {
        echo '<span class="green">OK</span>';
    } else {
        echo '<span class="red">FAIL</span>';
    } ?></b><br>
