<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Contracts') => array('index'),
    $model->name,
);

$this->menu = array(
    Yii::app()->user->checkAccess('listContracts') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List Contracts'))) : array(NULL),
    Yii::app()->user->checkAccess('updateContracts') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Edit contract'))) : array(NULL),
    Yii::app()->user->checkAccess('printContracts') ? array('icon' => 'fa-solid fa-print fa-xl', 'url' => '#', 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Print'), 'data-toggle' => 'modal', 'data-target' => '#myModal'),'linkOptions'=>array('target'=>'_BLANK')
    ) : array(NULL),
);
?>
<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
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
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'data' => $model,
            'attributes' => array(
                'number',
                'name',
                'type',
                'date_view',
                'tildate_view',
                array(
                    'label' => Yii::t('main-ui', 'Customer'),
                    'type' => 'raw',
                    'value' => '<a href="/companies/'.$model->customer_id.'">'.$model->customer_name.'</a>',
                ),
                array(
                    'label' => Yii::t('main-ui', 'Contractor'),
                    'type' => 'raw',
                    'value' => '<a href="/companies/'.$model->company_id.'">'.$model->company_name.'</a>',
                ),
                'cost',
            ),
        )); ?>

    </div>
    <div class="box-footer">
        <?php if ($model->files): ?>
                <?php FilesShow::show($model->files, 'contracts', '/uploads', '', 'Contracts'); ?>
        <?php endif; ?>
    </div>
</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Select print form template'); ?></h4>
    </div>

    <div class="modal-body">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'print-form',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('/contracts/printform', array('id' => $model->id)),
        )); ?>
        <div class="row-fluid">
            <?php $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'groups_id',
                    'data' => CHtml::listData(UnitTemplates::model()->findAllByAttributes(array('type'=>4)), 'id', 'name'),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
        </div>
    </div>

    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Print'),
        )); ?>

        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Cancel'),
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )); ?>
    </div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>