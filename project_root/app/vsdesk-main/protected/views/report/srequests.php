<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports'),
    Yii::t('main-ui', 'Summary by requests'),
);

?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Summary by requests'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'dusers-form',
            'enableAjaxValidation' => false,
            'action' => 'srequests',
        )); ?>
        <div class="row-fluid">

            <div class="span2">
                <label><?php echo Yii::t('main-ui', 'Start date'); ?>:</label>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'sdate',
                    'language' => 'ru',
                    'options' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'changeYear' => true,
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;'
                    ),
                )); ?>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span2">
                <label><?php echo Yii::t('main-ui', 'End date'); ?>:</label>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'edate',
                    'language' => 'ru',
                    'options' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'changeYear' => true,
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;'
                    ),
                )); ?>
                </div>
            </div>
        </div>
    </div>
        <div class="row-fluid">
            <div class="box-footer">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'label' => Yii::t('main-ui', 'Create'),
                )); ?>

                <?php $this->endWidget(); ?>
            </div>
        </div>
</div>



