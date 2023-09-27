<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', [
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        ]); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'status-form',
            'enableAjaxValidation' => false,
        ]); ?>

        <?php
        Yii::app()->clientScript->registerScript('toggle', '
		$("form input:radio").click(function () {
			var val = $("form input:radio:checked").val();
			if(val==4 || val==5){$("#mw").show();}
			else {$("#mw").hide();}
			if(val==7){$("#mw2").show();}
			else {$("#mw2").hide();}
			if(val==7){$("#mw0").show();}
			else {$("#mw0").hide();}
		});
');

        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <div class="span3">
                <?php echo $form->textFieldRow($model, 'name', ['maxlength' => 50, 'class' => 'span12']); ?>

                <?php echo $form->colorpickerRow($model, 'tag', array('class' => 'span12')); ?>
                <?php echo $form->toggleButtonRow($model, 'enabled'); ?>
                <?php echo $form->toggleButtonRow($model, 'hide'); ?>
                <?php echo $form->toggleButtonRow($model, 'freeze'); ?>
                <?php echo $form->toggleButtonRow($model, 'show'); ?>
                <?php echo $form->toggleButtonRow($model, 'is_need_comment'); ?>
                <?php echo $form->toggleButtonRow($model, 'is_need_rating'); ?>

            </div>
            <div class="span3">
                <fieldset>
                    <?php echo $form->radioButtonListRow(
                        $model,
                        'close',
                        [
                            '1' => Yii::t('main-ui', 'Ticket open'),
                            '2' => Yii::t('main-ui', 'Ticket in work'),
                            '3' => Yii::t('main-ui', 'Ticket closed'),
                            '4' => Yii::t('main-ui', 'Overdue reaction'),
                            '5' => Yii::t('main-ui', 'Overdue salvation'),
                            '6' => Yii::t('main-ui', 'Ticket canceled'),
                            '7' => Yii::t('main-ui', 'Ticket matching'),
                            '8' => Yii::t('main-ui', 'Ticket delayed'),
                            '9' => Yii::t('main-ui', 'Ticket reopened'),
                            '10' => Yii::t('main-ui', 'Ticket suspend'),
                            '11' => Yii::t('main-ui', 'Archive ticket'),
                            '0' => Yii::t('main-ui', 'Other'),
                        ]
                    ); ?>
                </fieldset>
            </div>
            <div class="span3">

                <?php echo $form->toggleButtonRow($model, 'notify_user'); ?>
                <?php echo $form->toggleButtonRow($model, 'notify_user_sms'); ?>
                <?php echo $form->toggleButtonRow($model, 'notify_manager'); ?>
                <?php echo $form->toggleButtonRow($model, 'notify_manager_sms'); ?>
                <?php echo $form->toggleButtonRow($model, 'notify_group'); ?>
                <div id="mw0"
                     style="display: <?= (isset($model->close) and ($model->close == 7)) ? 'display' : 'none' ?>">
                    <?php echo $form->toggleButtonRow($model, 'notify_matching'); ?>
                    <?php echo $form->toggleButtonRow($model, 'notify_matching_sms'); ?>
                </div>

            </div>
            <div class="span3">
                <?php echo $form->dropDownListRow($model, 'message', $messages); ?>
                <?php echo $form->dropDownListRow($model, 'mmessage', $messages); ?>
                <?php echo $form->dropDownListRow($model, 'gmessage', $messages); ?>
                <?php echo $form->dropDownListRow($model, 'sms', $smss); ?>
                <?php echo $form->dropDownListRow($model, 'msms', $smss); ?>
                <div id="mw2"
                     style="display: <?= (isset($model->close) and ($model->close == 7)) ? 'display' : 'none' ?>">
                    <?php echo $form->dropDownListRow($model, 'matching_message', $messages); ?>
                    <?php echo $form->dropDownListRow($model, 'matching_sms', $smss); ?>
                </div>
                <div id="mw"
                     style="display: <?= (isset($model->close) and ($model->close == 4 or $model->close == 5)) ? 'display' : 'none' ?>">
                    <?php echo $form->dropDownListRow($model, 'mwmessage', $messages); ?>
                    <?php echo $form->dropDownListRow($model, 'mwsms', $smss); ?>
                </div>
            </div>
        </div>
        <?php if (!$model->isNewRecord): ?>
            <div class="row-fluid">
                <?php
                echo CHtml::label(Yii::t('main-ui', 'Role'), 'role');
                echo CHtml::DropDownList('role', null, CHtml::listData(Roles::model()->findAll(), 'id', 'name'), array(
                    'class' => 'span12',
                    'empty' => '',
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('zstatusToRoles/create', ["zstatus_id" => $model->id]),//вызов контроллера c Ajax
                        'update' => '#roles',//id DIV - а в котором надо обновить данные
                    )));
                ?>
            </div>
            <div class="row-fluid">
                <div class="span12" id="roles">
                    <?php $this->widget('FilterGridResizable', [
                        'id' => 'roles-grid',
                        'dataProvider' => new CArrayDataProvider($model->roles_rl),
                        'type' => 'striped bordered condensed',
                        //'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/roles/update') . '/"+$.fn.yiiGridView.getSelection(id);}',
                        'htmlOptions' => ['style' => 'cursor: pointer'],
                        'columns' => [
                            'name:text:'.Yii::t('main-ui', 'Role'),
                            [
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                'template' => '{delete}',
                                'deleteButtonUrl' => 'Yii::app()->createUrl("/zstatusToRoles/delete", array("roles_id"=>$data->id, "zstatus_id"=>"' . $model->id . '"))',
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
