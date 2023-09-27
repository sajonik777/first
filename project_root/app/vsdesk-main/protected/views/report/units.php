<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Problems by unit report'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('exportproblems', 'company' => isset($_POST['company']) ? $_POST['company'] : null), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Export to Excel'))),
);

?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Problems by unit report'); ?>:
        <?php
        if (!Yii::app()->user->checkAccess('systemUser')){
            echo (isset($_POST['company']) ? $_POST['company'] : 'Все компании') ;
        } else {
            $user = CUsers::model()->findByPk(Yii::app()->user->id);
            echo $user->company;
        }
        ?></h3>
    </div>
<div class="box">
    <div class="box-body table-responsive">
        <div class="form">
            <?php echo CHtml::beginForm(); ?>
            <div class="row">
                <?php echo CHtml::label(Yii::t('main-ui', 'Company'), 'company');?>
                <?php echo CHtml::dropDownList('company', isset($_POST['company']) ? $_POST['company'] : NULL, Companies::all(), array('empty' => '', 'class' => 'span3')); ?>
            </div>
            <div class="row submit">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'label' => Yii::t('main-ui', 'Create'),
                )); ?>
            </div>
            <?php echo CHtml::endForm(); ?>
        </div><!-- form -->
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>

        <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'id' => 'zreport-grid',
            'type' => 'striped bordered condensed',
            'fixedHeader' => true,
            'dataProvider' => $model->search(),
            'columns' => array(
                array(
                    'name' => 'assetname',
                    'class' => 'bootstrap.widgets.TbRelationalColumn',
                    'url' => $this->createUrl('unitproblemrelational'),
                ),
                //'slabel:raw',
                'assettype',
                'stnew',
                'stworkaround',
                'stsolved',
            ),
        )); ?>
    </div>
</div>