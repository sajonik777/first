<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'groups-form',
            'enableAjaxValidation' => false,
        )); ?>


        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->toggleButtonRow($model, 'send');  ?>
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'phone', array('class' => 'span12', 'maxlength' => 100)); ?>

            <label>Выберите пользователей:</label>
            <?php $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'users',
                    'data' => CUsers::all_id(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'class' => 'biginp',
                    ),
                )
            ); ?>
        </div>
    </div>
        <div class="box-footer">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? 'Создать' : 'Сохранить',
            )); ?>
        </div>

        <?php $this->endWidget(); ?>
</div>
