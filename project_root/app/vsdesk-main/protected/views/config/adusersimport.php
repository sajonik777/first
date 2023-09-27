<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Импорт пользователей из AD'),
);
?>

<div class="page-header">
    <h3><i class="icon-group icon-2x"> </i><?php echo Yii::t('main-ui', 'Импорт пользователей из AD'); ?></h3>
</div>

<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>

<?php
$adusersimport = new ADUsersImport();
$adusersimport->defaultrole = isset($_POST['ADUsersImport']['defaultrole']) ? $_POST['ADUsersImport']['defaultrole'] : null;
$adusersimport->notemailusers =  isset($_POST['ADUsersImport']['notemailusers']) ? $_POST['ADUsersImport']['notemailusers'] : null;
?>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'adusersimport',
        'enableAjaxValidation' => false,
    )); ?>
    <div class="row">
        <div class="span2">
            <?php echo $form->toggleButtonRow($adusersimport, 'notemailusers'); ?>
        </div>
    </div>
    <div class="row">
        <div class="span2">
            <?php echo $form->dropDownListRow($adusersimport, 'defaultrole', Roles::all()); ?>
        </div>
    </div>
    <div class="row">
        <?php $this->widget('CTreeView', array('url' => array('ajaxADTree'))); ?>
    </div>
    <div class="row submit">
        <?php echo CHtml::submitButton('Импортировать', array('class' => 'btn btn-info')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>