<?php

/* @var $this ServiceCategoriesController */
/* @var $model ServiceCategories */
/* @var $form CActiveForm */
?>

<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'service-form',
            'enableAjaxValidation' => false,
        ]); ?>
        <div class="row-fluid">
            <div class="span6">
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->textFieldRow($model, 'name', ['class' => 'span12']); ?>
            </div>
        </div>
        <?php if (!$model->isNewRecord): ?>
            <div class="row-fluid">
                <?php
                
                echo CHtml::label(Yii::t('main-ui', 'Services'), 'service');
                echo CHtml::DropDownList('service', null, CHtml::listData(Service::model()->findAllByAttributes(['category_id' => null]), 'id', 'name'),
                    [
                        'class' => 'span6',
                        'empty' => '',
                        'ajax' => [
                            'type' => 'POST',
                            'url' => CController::createUrl('/servicecategories/serviceadd', ['category_id' => $model->id]),
                            'update' => '#services',
                        ]
                    ]);
                ?>
            </div>
            <div class="row-fluid">
                <div class="span12" id="services">
                    <?php $this->widget('FilterGridResizable', [
                        'id' => 'services-grid',
                        'dataProvider' => new CArrayDataProvider($model->services),
                        'type' => 'striped bordered condensed',
                        'htmlOptions' => ['style' => 'cursor: pointer'],
                        'columns' => [
                            'name:text:'.Yii::t('main-ui', 'Services'),
                            [
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                'template' => '{delete}',
                                'deleteButtonUrl' => 'Yii::app()->createUrl("/servicecategories/servicedelete", array("service_id"=>$data->id))',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        ]); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
