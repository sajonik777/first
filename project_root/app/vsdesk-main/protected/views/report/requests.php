<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('users'),
    Yii::t('main-ui', 'Service request report'),
);

?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Service request report by month'); ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>
<?php /* $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); */?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'problems-form',
    'enableAjaxValidation' => false,
    'action' => 'requestproblem',
)); ?>
<div class="row-fluid">
    <div>
	<?php 
	$month = date('m');
	$year = date ('Y');
	?>
        <label><?php echo Yii::t('main-ui', 'Select month'); ?>:</label>
        <?php echo $form->dropDownList($model, 'date', array('01' => 'Январь', '02' => 'Февраль', '03' => 'Март', '04' => 'Апрель', '05' => 'Май', '06' => 'Июнь', '07' => 'Июль', '08' => 'Август', '09' => 'Сентябрь', '10' => 'Октябрь', '11' => 'Ноябрь', '12' => 'Декабрь'), array('options' => array($month=>array('selected'=>true)))); ?>
    </div>

    <div>
        <label><?php echo Yii::t('main-ui', 'Select year'); ?>:</label>
        <?php echo $form->dropDownList($model, 'year', array('2013' => '2013', '2014' => '2014', '2015' => '2015', '2016' => '2016', '2017' => '2017', '2018' => '2018', '2019' => '2019', '2020' => '2020', '2021' => '2021', '2022' => '2022', '2023' => '2023', '2024' => '2024'), array('options' => array($year=>array('selected'=>true)))); ?>
    </div>
</div>

<div class="row-fluid">
    <div class="span1">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Create'),
        )); ?>

        <?php $this->endWidget(); ?>
    </div>
</div>
