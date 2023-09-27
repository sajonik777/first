<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Update'),

);
$expiration = constant('support_date');
$date = strtotime($expiration);
if (strtotime(date('d.m.Y')) >= strtotime($expiration)) {
$expires = true;
}
?>

<div class="page-header">
    <h3><i class="fa-solid fa-square-up-right fa-xl"> </i><?php echo Yii::t('main-ui', 'Update'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <p><?php echo Yii::t('main-ui', 'Current version: '); ?> <b><span id="cur_ver"><?php echo $current_version; ?></span></b></p>
        <p><?php echo Yii::t('main-ui', 'Available version: '); ?> <b><?php echo $new_version; ?></b></p>
        <p><?php echo Yii::t('main-ui', 'Update is active until'); ?>: <b><?php echo constant('support_date');?></b></p>
    </div>
    <div class="box-footer">
        <?php
        if ($current_version == $new_version AND !$expires) {
            echo '<p>' . Yii::t('main-ui', 'You have the latest version. No update is required.') . '</p>';
        } else {
            echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Update now'), Yii::app()->createUrl('config/updateRun'),
                array(
                    'type' => 'POST',
                    'data' => array('YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                    'beforeSend'=>'function(){document.getElementById("yt0").disabled=true;}',
                    'success' => 'js:function(string){ document.getElementById("cur_ver").innerHTML = "<span style=\"color:green;\">" + string + "</span>"; alert("' . Yii::t('main-ui', 'Upgrade complete') . '"); }'
                ), array('class' => 'btn btn-primary',));
        }
        ?>
    </div>
</div>
