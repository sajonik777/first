<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Licensing'),
);
if (constant('redaction') == 'DEMO'){
    Yii::app()->user->setFlash('danger', Yii::t('main-ui', '<strong>В демо режиме вы можете создать только 50 заявок!</strong>'));
}
?>

<div class="page-header">
    <h3><i class="fa-solid fa-file-contract fa-xl"> </i><?php echo Yii::t('main-ui', 'Licensing'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <h4><?php echo Yii::t('main-ui', 'Software version '); ?>:</h4> <p><?php echo constant('version') . ' "' . constant('redaction'); ?>"</p>
        <h4><?php echo Yii::t('main-ui', 'Customer'); ?>:</h4> <p><?php echo constant('licensor');?></p>
        <h4><?php echo Yii::t('main-ui', 'License number'); ?>:</h4> <p><?php echo constant('serial');?></p>
        <h4><?php echo Yii::t('main-ui', 'Update login'); ?>:</h4> <p><?php echo constant('update_login');?></p>
        <h4><?php echo Yii::t('main-ui', 'Technical support is active until'); ?>:</h4> <p><?php echo constant('support_date');?></p>
        <?php if(!empty(constant('license_date'))): ?>
        <h4><?php echo Yii::t('main-ui', 'Licence is active until'); ?>:</h4> <p><?php echo constant('license_date');?></p>
    <?php endif; ?>
    </div>
            <div class="row-fluid">
                <div class="box-footer">
                    <p><i>Незаконное копирование и распространение данного Программного Обеспечения преследуется по закону.</i></p>
                </div>
            </div>


</div>