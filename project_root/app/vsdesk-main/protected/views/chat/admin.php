<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Chat') => array('index'),
    Yii::t('main-ui', 'Chat admin') => array('admin'),
);
Yii::app()->clientScript->registerScriptFile('/js/chatadmin.js', CClientScript::POS_END);
?>
<div class="page-header">
    <div style="display: inline-block;"><h3><i class="fa-solid fa-comment fa-xl" ></i> <?= Yii::t('main-ui', 'Chat admin') ?></h3></div>
</div>
<div class="box">
    <div class="box-body">
        <input id="echo-ws-start" class="btn btn-primary" type="button" value="<?php echo Yii::t('main-ui', 'Start'); ?>">
        <input id="echo-ws-stop" class="btn btn-danger" type="button" value="<?php echo Yii::t('main-ui', 'Stop'); ?>">
        <br>
        <label><?= Yii::t('main-ui', 'Chat server current status') ?></label>
        <div id="echo-ws-status" style="border: 1px solid">
            Loading...
        </div>
        <br>
        <input id="echo-ws-status-refresh" class="btn btn-warning btn-small" type="button" value="<?php echo Yii::t('main-ui', 'Refresh') ?>"><br><br>
        <label><?= Yii::t('main-ui', 'Chat server logfile') ?></label>
        <div id="echo-ws-logfile" style="border: 1px solid; overflow-y: scroll; height: 500px; resize: vertical;">
            Loading...
        </div>
        <br>
        <input id="echo-ws-logfile-refresh" class="btn btn-warning btn-small" type="button"
               value="<?= Yii::t('main-ui', 'Refresh') ?>"><br><br>
    </div>
</div>
