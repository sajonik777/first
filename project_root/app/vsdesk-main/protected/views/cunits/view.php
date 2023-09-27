<?php


$this->breadcrumbs = array(
    Yii::t('main-ui', 'Configuration units') => array('index'),
    $model->name,
);

$this->menu = array(
    Yii::app()->user->checkAccess('listUnit') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List units'))): array(NULL),
    Yii::app()->user->checkAccess('updateUnit') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id),'itemOptions'=>array('title'=>Yii::t('main-ui', 'Edit unit'))) : array(NULL),
    Yii::app()->user->checkAccess('printUnit') ? array('icon' => 'fa-solid fa-print fa-xl', 'url' => '#', 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Print unit'), 'data-toggle' => 'modal', 'data-target' => '#myModal'),'linkOptions'=>array('target'=>'_BLANK')
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
        <?php if (Yii::app()->user->checkAccess('systemAdmin') or Yii::app()->user->checkAccess('systemManager')): ?>
            <?php 
                $this->widget(
                'bootstrap.widgets.TbTabs',
                array(
                    'type' => 'tabs', // 'tabs' or 'pills'
                    'tabs' => array_filter(array(
                        array(
                            'label' => Yii::t('main-ui', 'Description'),
                            'content' => $this->renderPartial('_cuview', array('assets' => $assets, 'model' => $model), true),
                            'active' => true
                        ),
                        $problems ? array('label' => Yii::t('main-ui', 'Assigned problems'), 'content' => $this->renderPartial('_cuproblems', array('problems' => $problems), true)) : NULL,
                        array('label' => Yii::t('main-ui', 'Assigned requests'), 'content' => $this->renderPartial('_curequests', array('requests' => $requests), true)),
                        array('label' => Yii::t('main-ui', 'Unit history'), 'content' => $this->renderPartial('_cuhistory', array('history' => $history), true)),
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
                            'content' => $this->renderPartial('_cuview', array('assets' => $assets, 'model' => $model), true),
                            'active' => true
                        ),
                    ),
                )
            ); ?>
        <?php endif; ?>
        <?php
        if ($model->files) {
            FilesShow::show($model->files, 'cunits', '/uploads', '', 'Unit');
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
        'action' => Yii::app()->createUrl('/cunits/printform', array('id' => $model->id)),
    )); ?>
<div class="row-fluid">
    <?php $this->widget(
        'bootstrap.widgets.TbSelect2',
        array(
            'model' => $model,
            'name' => 'groups_id',
            'data' => CHtml::listData(UnitTemplates::model()->findAllByAttributes(array('type'=>1)), 'id', 'name'),
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
