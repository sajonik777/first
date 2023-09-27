<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'asset-attrib-form',
            'enableAjaxValidation' => false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 50)); ?>
        </div>
    </div>
        <div class="box-footer">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model_s->isNewRecord ? Yii::t('main-ui', 'Add') : Yii::t('main-ui', 'Save'),
            )); ?>
        </div>
        <?php $this->endWidget(); ?>
</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Create new attributes'); ?></h4>
</div>

<div class="modal-body">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'asset-attribs-form',
        'enableAjaxValidation' => false,
        'action' => '/assetAttribValue/create'
    )); ?>
<div class="row-fluid">
    <?php echo $form->errorSummary($model_s); ?>
    <?php echo CHtml::activeLabel($model, 'name'); ?>
    <?php echo $form->textField($model_s, 'name'); ?>

    <?php echo $form->hiddenField($model_s, 'asset_id', array('value' => $model->id)); ?>
    <?php echo $form->hiddenField($model_s, 'asset_attrib_id', array('value' => $model->id)); ?>
</div>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model_s->isNewRecord ? Yii::t('main-ui', 'Add') : Yii::t('main-ui', 'Save'),
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>

