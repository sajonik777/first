<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'SMS templates') => array('index'),
    $model->name,
);
?>
<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php echo $model->content; ?>
    </div>
</div>
