<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' =>'pills',
            'items'=> $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
            'id'                  =>'bcats-form',
            'enableAjaxValidation'=>false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
                <?php echo $form->textFieldRow($model,'name',array('class'=>'span12','maxlength'=>50)); ?>
        </div>
        <div class="row-fluid">
                <?php
                echo $form->select2Row($model, 'access', array(
                    'asDropDownList'=>false,
                    'multiple' => 'multiple',
                    'options'=>array(
                        'tags'=> array_merge(array('0' => 'Гость'),Roles::RolesAll()),
                        'width'=>'100%',
                        'tokenSeparators'=>array(','),
                        'class' => 'biginp'
                    ),
                ));
                ?>
        </div>
    </div>
        <div class="box-footer">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'      =>'primary',
                'label'     =>$model->isNewRecord ?  Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            )); ?>
        </div>

        <?php $this->endWidget(); ?>
</div>
