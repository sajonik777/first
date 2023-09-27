<?php

/* @var $form TbActiveForm */
/* @var $model Report */
$model->type = 1;
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Managers report'),
);
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'KPI report'); ?></h3>
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
            'action' => 'kpireport',
        )); ?>
        <div class="row-fluid">
            <div class="span3">
                <?php echo '<h4>'.Yii::t('main-ui', 'Select а type of report').'</h4>'; ?>
                <?php echo
                CHtml::activeRadioButtonList($model, 'type',  array('1' => Yii::t('main-ui', 'Manager'), '2' => Yii::t('main-ui', 'Customer'), '3' => Yii::t('main-ui', 'Service'), '4' => Yii::t('main-ui', 'Group')), array('container' => 'div', 'separator' => '', 'template' => '{beginLabel}{input}  {labelTitle}{endLabel}', 'labelOptions' => array('class' => 'radio-inline')));?>
                <br>
                <?php echo $form->select2Row($model, 'company', [
                    'data' => array('0' => 'Все компании')+Companies::all(),
                    'multiple' => false,
                    'id' => 'company',
                    'options' => ['width' => '100%'],
                ]); ?>
            </div>
        </div>
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

