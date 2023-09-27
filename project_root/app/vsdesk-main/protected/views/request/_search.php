<br/>
<div class="row-fluid">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type' => 'inline',
    )); ?>

    <div class="span12">
        <?php echo $form->textFieldRow($model, 'Content',
            array('maxlength' => 500, 'class' => 'span8', 'id' => 'Request_Content_search')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Search'),
            'icon' => 'icon-search',
            'size' => 'small',
        )); ?>

    </div>
    <?php $this->endWidget(); ?>
</div>

            