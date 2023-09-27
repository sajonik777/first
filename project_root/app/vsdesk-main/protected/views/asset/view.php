<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Assets') => array('index'),
    $model->name,
);
$this->menu = array(
    Yii::app()->user->checkAccess('listAsset') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List assets'))) : array(NULL),
    Yii::app()->user->checkAccess('updateAsset') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id), 'itemOptions' => array('title' => Yii::t('main-ui', 'Edit asset'))) : array(NULL),
    Yii::app()->user->checkAccess('printAsset') ? array('icon' => 'fa-solid fa-print fa-xl', 'url' => '#', 'itemOptions' => array('title' => Yii::t('main-ui', 'Print asset'), 'data-toggle' => 'modal', 'data-target' => '#myModal')) : array(NULL),
);

?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'View asset'); ?> <?php echo $model->name; ?></h3>
    </div>
    <div class="box">
        <div class="box-body">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => $this->menu,
            )); ?>
            <?php if (Yii::app()->user->checkAccess('systemAdmin') or Yii::app()->user->checkAccess('systemManager')): ?>
                <?php $this->widget(
                    'bootstrap.widgets.TbTabs',
                    array(
                        'type' => 'tabs', // 'tabs' or 'pills'
                        'tabs' => array_filter(array(
                            array(
                                'label' => Yii::t('main-ui', 'Description'),
                                'content' => $this->renderPartial('_aview', array('data' => $data,'model' => $model), true),
                                'active' => true
                            ),
                            $model->uid ? array('label' => Yii::t('main-ui', 'Unit'), 'content' => $this->renderPartial('_unit', array('unit' => $unit), true)) : NULL,
                            array('label' => Yii::t('main-ui', 'Asset history'), 'content' => $this->renderPartial('_ahistory', array('history' => $history), true)),
                        )),
                    )
                ); ?>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('systemUser')): ?>
                <?php $this->widget(
                    'bootstrap.widgets.TbTabs',
                    array(
                        'type' => 'tabs', // 'tabs' or 'pills'
                        'tabs' => array(
                            array(
                                'label' => Yii::t('main-ui', 'Description'),
                                'content' => $this->renderPartial('_aview', array('data' => $data, 'model' => $model), true),
                                'active' => true
                            ),
                        ),
                    )
                ); ?>
            <?php endif; ?>
            <?php
            if ($model->files) {
                FilesShow::show($model->files, 'asset', '/uploads', '', 'Asset');
            }
            ?>
    </div>
    </div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Select print form template'); ?></h4>
    </div>

    <div class="modal-body">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'adduser-form',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('/asset/printform', array('id' => $model->id)),
        )); ?>
        <div class="row-fluid">
            <?php $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'groups_id',
                    'data' => CHtml::listData(UnitTemplates::model()->findAllByAttributes(array('type' => 2)), 'id', 'name'),
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