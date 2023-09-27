<br/>
<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type' => 'inline',
    )); ?>

    <div class="span12">
        <?php echo $form->textFieldRow($model, 'description', array('maxlength' => 500, 'class' => 'span8')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Search'),
            'icon' => 'icon-search',
            'size' => 'small',
        )); ?>

        <?php $this->endWidget(); ?>
    </div>
</div>