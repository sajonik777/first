<?php


/** @var $escalate Escalates */
/** @var $escalateNew Escalates */

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>
<div class="box">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'service-form',
        'enableAjaxValidation' => false,
    )); ?>
	<div class="box-body">
		<div class="row-fluid">
			<div class="span6">
                <?php
                echo $form->errorSummary($model); ?>

                <?php
                echo $form->textFieldRow($model, 'name', array('class' => 'span12')); ?>

				
                <?php
                $slas = explode(',', $model->sla);
                $slas = json_encode($slas);
                echo $form->labelEx($model, 'sla');
                $this->widget('ext.select2.ESelect2', array(
                    'model' => $model,
                    'attribute' => 'sla',
                    'data' => CHtml::listData(Sla::model()->findAllByAttributes([]), 'id', 'name'),
                    'htmlOptions' => array(
						'required' => 'required',
						// 'initialize' => true,
                        'multiple' => 'multiple',
                        'style' => 'width:100%',
                        'name' => 'sla'
                    ),
                ));
                ?>
                <?php
                echo $form->dropDownListRow($model, 'outsource', [false => "Собственная услуга", true => "Услуга внешнего поставщика"], array('class' => 'span12')); ?>

                <?php
                echo $form->dropDownListRow($model, 'priority', Zpriority::model()->all(),
                    array('class' => 'span12')); ?>

                <?php
                $tags = explode(',', $model->watcher);
                $tags = json_encode($tags);
                echo $form->labelEx($model, 'watcher');
                $this->widget('ext.select2.ESelect2', array(
                    'model' => $model,
                    'attribute' => 'watcher',
                    'data' => CHtml::listData(CUsers::model()->findAllByAttributes(array('active' => 1)), 'fullname', 'fullname'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'style' => 'width:100%',
                        'name' => 'watcher'
                    ),
                ));
                ?>

                <?php
                echo $form->toggleButtonRow($model, 'shared'); ?>
                <?php
                echo $form->toggleButtonRow($model, 'autoinwork'); ?>
			</div>

			<div class="span6">
                <?php
                echo $form->textFieldRow($model, 'availability', array('class' => 'span12')); ?>
				<!-- <?php
                echo $form->dropDownListRow($model, 'target_type', array('user-service' => 'Пользовательская', 'support-service' => 'Обеспечивающая'), array(
                    'id' => 'target_type',
                    'class' => 'span12',
                )); ?> -->
                <?php
                echo $form->dropDownListRow($model, 'category_id', CHtml::listData(ServiceCategories::model()->findAll(), 'id', 'name'),
                    ['class' => 'span12']); ?>
                <?php
                echo $form->dropDownListRow($model, 'gtype', array('1' => 'Пользователь', '2' => 'Группа'), array(
                    'id' => 'gtype',
                    'class' => 'span12',
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('Service/SelectGroup'),//вызов контроллера c Ajax
                        'update' => '#manag',//id DIV - а в котором надо обновить данные
                    )
                )); ?>
				<div id="manag">
                    <?php
                    if ($model->isNewRecord) {
                        echo $form->dropDownListRow($model, 'manager', CUsers::model()->all(),
                            array('class' => 'span12'));
                    } else {
                        if ($model->gtype == 1) {
                            echo $form->dropDownListRow($model, 'manager', CUsers::model()->all(),
                                array('class' => 'span12'));
                        } else {
                            echo $form->dropDownListRow($model, 'group', Groups::model()->all(),
                                array('class' => 'span12'));
                        }

                    } ?>
				</div>
                <?php
                echo $form->dropDownListRow($model, 'fieldset', Fieldsets::model()->all(),
                    array('class' => 'span12', 'prompt' => Yii::t('main-ui', 'Select item'))); ?>
                <?php
                echo $form->dropDownListRow($model, 'checklist_id', CHtml::listData(Checklists::model()->findAll(), 'id', 'name'),
                    ['class' => 'span12', 'prompt' => Yii::t('main-ui', 'Select item')]); ?>

                <?php
                $matchings = explode(',', $model->matchings);
                $matchings = json_encode($matchings);
                echo $form->labelEx($model, 'matchings');
                $this->widget('ext.select2.ESelect2', array(
                    'model' => $model,
                    'attribute' => 'matchings',
                    'data' => CHtml::listData(CUsers::model()->findAllByAttributes(array('active' => 1)), 'id', 'fullname'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'style' => 'width:100%',
                        'name' => 'matchings'
                    ),
                ));
                ?>

			</div>
		</div>
		<div class="row-fluid">
            <?php
            echo $form->textFieldRow($model, 'description', array('class' => 'span12', 'maxlength' => 200)); ?>

            <?php
            echo $form->textAreaRow($model, 'content', array('id' => 'content', 'rows' => 5));
            ?>
            <?php
            Yii::app()->clientScript->registerScript('redactor-init', "
                     $(function () {
                            $('#content').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'video','fullscreen'],
                            });
                        });
                    ");
            ?>
		</div>
		<div class="row-fluid" style="margin-top: 15px;">
			<div class="span12">
				<p><b><?php
                        echo Yii::t('main-ui', 'Escalate reaction rules'); ?></b></p>
			</div>
		</div>

        <?php
        foreach ($model->escalates as $escalate): ?>
            <?php
            if ($escalate->type_id != Escalates::TYPE_REACTION) continue; ?>
			<div class="row-fluid escalate">
				<div class="span12">
					<div class="span2">
						<input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
						<input name="Escalates[id]" type="hidden" value="<?= $escalate->id ?>">
						<input name="Escalates[type_id]" type="hidden" value="<?= $escalate->type_id ?>">
						<input name="Escalates[service_id]" type="hidden" value="<?= $escalate->service_id ?>">
						<label><?= Yii::t('main-ui', 'Minutes') ?></label>
						<input maxlength="10" class="span12" name="Escalates[minutes]" type="number"
							   value="<?= $escalate->minutes ?>">
					</div>
					<div class="span4">
						<label><?= Yii::t('main-ui', 'Manager') ?></label>
                        <?php
                        $manager_id = $escalate->manager_id;
                        $selectedValues = [$manager_id => ['selected' => 'selected']];
                        echo CHtml::activeDropDownList($escalate, 'manager_id', CUsers::all_id(), [
                                'empty' => '',
                                'options' => $selectedValues,
                                'class' => "span12 manager",
                            ]
                        );
                        ?>
					</div>
					<div class="span4">
						<label><?= Yii::t('main-ui', 'Group') ?></label>
                        <?php
                        $group_id = $escalate->group_id;
                        $selectedValues = [$group_id => ['selected' => 'selected']];
                        echo CHtml::activeDropDownList($escalate, 'group_id', Groups::allWithId(), [
                                'empty' => '',
                                'options' => $selectedValues,
                                'class' => "span12 group",
                            ]
                        );
                        ?>
					</div>
					<div class="span2">
						<label>&nbsp;</label>
						<button class="btn btn-success save"><i class="fa-solid fa-circle-check"></i></button>
						<button class="btn btn-danger delete" data-id="<?= $escalate->id ?>"><span
									class="fa-solid fa-trash"></span></button>
					</div>
				</div>
			</div>
        <?php
        endforeach; ?>

		<div class="row-fluid escalate">
			<div class="span12">
				<div class="span2">
					<input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
					<input name="Escalates[type_id]" type="hidden" value="<?= Escalates::TYPE_REACTION ?>">
					<input name="Escalates[service_id]" type="hidden" value="<?= $model->id ?>">
					<label><?= Yii::t('main-ui', 'Minutes') ?></label>
					<input maxlength="10" class="span12" name="Escalates[minutes]" type="number">
				</div>
				<div class="span4">
					<label><?= Yii::t('main-ui', 'Manager') ?></label>
                    <?php
                    echo CHtml::activeDropDownList($escalateNew, 'manager_id', CUsers::all_id(), [
                            'empty' => '',
                            'class' => "span12 manager",
                        ]
                    );
                    ?>
				</div>
				<div class="span4">
					<label><?= Yii::t('main-ui', 'Group') ?></label>
                    <?php
                    echo CHtml::activeDropDownList($escalateNew, 'group_id', Groups::allWithId(), [
                            'empty' => '',
                            'class' => "span12 group",
                        ]
                    );
                    ?>
				</div>
				<div class="span2">
					<label>&nbsp;</label>
					<button class="btn btn-success new"><i class="fa-solid fa-plus"></i></button>
				</div>
			</div>
		</div>
		<hr>
		<div class="row-fluid" style="margin-top: 15px;">
			<div class="span12">
				<p><b><?php
                        echo Yii::t('main-ui', 'Escalate solution rules'); ?></b></p>
			</div>
		</div>

        <?php
        foreach ($model->escalates as $escalate): ?>
            <?php
            if ($escalate->type_id != Escalates::TYPE_EXECUTION) continue; ?>
			<div class="row-fluid escalate">
				<div class="span12">
					<div class="span2">
						<input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
						<input name="Escalates[id]" type="hidden" value="<?= $escalate->id ?>">
						<input name="Escalates[type_id]" type="hidden" value="<?= $escalate->type_id ?>">
						<input name="Escalates[service_id]" type="hidden" value="<?= $escalate->service_id ?>">
						<label><?= Yii::t('main-ui', 'Minutes') ?></label>
						<input maxlength="10" class="span12" name="Escalates[minutes]" type="number"
							   value="<?= $escalate->minutes ?>">
					</div>
					<div class="span4">
						<label><?= Yii::t('main-ui', 'Manager') ?></label>
                        <?php
                        $manager_id = $escalate->manager_id;
                        $selectedValues = [$manager_id => ['selected' => 'selected']];
                        echo CHtml::activeDropDownList($escalate, 'manager_id', CUsers::all_id(), [
                                'empty' => '',
                                'options' => $selectedValues,
                                'class' => "span12 manager",
                            ]
                        );
                        ?>
					</div>
					<div class="span4">
						<label><?= Yii::t('main-ui', 'Group') ?></label>
                        <?php
                        $group_id = $escalate->group_id;
                        $selectedValues = [$group_id => ['selected' => 'selected']];
                        echo CHtml::activeDropDownList($escalate, 'group_id', Groups::allWithId(), [
                                'empty' => '',
                                'options' => $selectedValues,
                                'class' => "span12 group",
                            ]
                        );
                        ?>
					</div>
					<div class="span2">
						<label>&nbsp;</label>
						<button class="btn btn-success save"><i class="fa-solid fa-circle-check"></i></button>
						<button class="btn btn-danger delete" data-id="<?= $escalate->id ?>"><span class="fa-solid fa-trash"></span></button>
					</div>
				</div>
			</div>
        <?php
        endforeach; ?>

		<div class="row-fluid escalate">
			<div class="span12">
				<div class="span2">
					<input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
					<input name="Escalates[type_id]" type="hidden" value="<?= Escalates::TYPE_EXECUTION ?>">
					<input name="Escalates[service_id]" type="hidden" value="<?= $model->id ?>">
					<label><?= Yii::t('main-ui', 'Minutes') ?></label>
					<input maxlength="10" class="span12" name="Escalates[minutes]" type="number">
				</div>
				<div class="span4">
					<label><?= Yii::t('main-ui', 'Manager') ?></label>
                    <?php
                    echo CHtml::activeDropDownList($escalateNew, 'manager_id', CUsers::all_id(), [
                            'empty' => '',
                            'class' => "span12 manager",
                        ]
                    );
                    ?>
				</div>
				<div class="span4">
					<label><?= Yii::t('main-ui', 'Group') ?></label>
                    <?php
                    echo CHtml::activeDropDownList($escalateNew, 'group_id', Groups::allWithId(), [
                            'empty' => '',
                            'class' => "span12 group",
                        ]
                    );
                    ?>
				</div>
				<div class="span2">
					<label>&nbsp;</label>
					<button class="btn btn-success new"><i class="fa-solid fa-plus"></i></button>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
	</div>

    <?php
    $this->endWidget(); ?>
    <?php
    if (Yii::app()->request->url == '/service/update/' . $model->id): ?>
</div>

<?php
endif; ?>

<?php
$this->beginWidget('bootstrap.widgets.TbModal', ['id' => 'batchCommentModal', 'htmlOptions' => ['style' => 'height:auto;width:auto;']]); ?>
<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4><?php
        echo Yii::t('main-ui', 'Запись базы знаний'); ?></h4>
</div>
<div class="modal-body" style="min-height: 80%;">
    <?php
    $this->renderPartial('_kb_form', ['model' => $model]); ?>
</div>
<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', ['buttonType' => 'submit', 'label' => Yii::t('main-ui', 'Add comment'), 'type' => 'primary', 'id' => 'btnBatchComment']); ?>
</div>
<?php
$this->endWidget(); ?>

<script>
	$(document).ready(function () {
		let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
		$('#add_kb_record').on('click', () => {
			jQuery('#batchCommentModal').modal({'show': true});
		});

		if ($('#Service_category_id').val() === '1') {
			$.ajax(
				{
					type:     'POST',
					url:      '/sla/getOLA',
					data:     {
						'YII_CSRF_TOKEN': csrf
					},
					dataType: 'text',
					cache:    false,
					error:    function (e) {
						console.log('error', e);
					},
					success:  function (data) {
						console.log('data', data);
						let result = $.parseJSON(data);

						console.log('result', result);
						$('#sla').empty();

						$.each(result, function (i, item) {
							$('#sla').append($('<option>', {
								value: i,
								text:  item
							}));
						});
						// var slaArray;
						// $slaArray = explode(',', $model->sla);
						// $slaArray = json_encode($slas);
						// $('#sla').select2().select2('val', slaArray);
						var slaarray;
						slaarray = <?php echo $slas; ?>;
						$('#sla').select2().select2('val', slaarray);

						// $('#sla').select2().select2('val', Object.keys(result));

					}
				});
			// $("#Service_sla").hide();
			// $("[for=Service_sla]").hide()
		} else {
			// $("#Service_sla").show();
			// $("[for=Service_sla]").show()
			$.ajax(
				{
					type:     'POST',
					url:      '/sla/getSLA',
					data:     {
						'YII_CSRF_TOKEN': csrf
					},
					dataType: 'text',
					cache:    false,
					error:    function (e) {
						console.log('error', e);
					},
					success:  function (data) {
						console.log('data', data);
						let result = $.parseJSON(data);
						console.log('result', result);

						$('#sla').empty();

						$.each(result, function (i, item) {
							$('#sla').append($('<option>', {
								value: i,
								text:  item
							}));
						});
						var slaarray;
						slaarray = <?php echo $slas; ?>;
						$('#sla').select2().select2('val', slaarray);

					}
				});
		}
		$('#Service_category_id').on('change', () => {
			console.log($('#Service_category_id').val());
			// if ($( "#Service_category_id" ).val() === "1"){
			//     $("#Service_sla").hide();
			//     $("[for=Service_sla]").hide()
			// } else {
			//     $("#Service_sla").show();
			//     $("[for=Service_sla]").show()
			// }
		});

		// $('#myModal').modal('toggle')
		$('#Service_category_id').on('change', function () {
			console.log('Target type');
			console.log($('#target_type').val());
			let support_services_count = parseInt("<?php echo count($model->get_support_services()); ?>") || 0;
			let user_services_count    = parseInt("<?php echo count($model->get_user_services()); ?>") || 0;

			if ($('#Service_category_id').val() === '1') {

				$.ajax(
					{
						type:     'POST',
						url:      '/sla/getOLA',
						data:     {
							'YII_CSRF_TOKEN': csrf
						},
						dataType: 'text',
						cache:    false,
						error:    function (e) {
							console.log('error', e);
						},
						success:  function (data) {
							console.log('data', data);
							let result = $.parseJSON(data);
							$('#sla').empty();
							$('#sla').val(null).trigger('change');
							$.each(result, function (i, item) {
								$('#sla').append($('<option>', {
									value: i,
									text:  item
								}));
							});

						}
					});
				if (support_services_count > 0) {
					console.log('get_support_services');
					console.log(support_services_count);
					alert("<?php echo Yii::t('main-ui', 'After save all linked support services will be unlinked!'); ?>");
				}
			}

			if ($('#Service_category_id').val() === '2') {

				$.ajax(
					{
						type:     'POST',
						url:      '/sla/getSLA',
						data:     {
							'YII_CSRF_TOKEN': csrf
						},
						dataType: 'text',
						cache:    false,
						error:    function (e) {
							console.log('error', e);
						},
						success:  function (data) {
							console.log('data', data);
							let result = $.parseJSON(data);
							$('#sla').empty();
							$('#sla').val(null).trigger('change');

							$.each(result, function (i, item) {
								$('#sla').append($('<option>', {
									value: i,
									text:  item
								}));
							});

						}
					});
				if (user_services_count > 0) {
					console.log('get_user_services');
					console.log(user_services_count);

					alert("<?php echo Yii::t('main-ui', 'After save all linked user services will be unlinked!'); ?>");
				}
			}

		});

		var keywordArray;
		keywordArray = <?php echo $tags; ?>;

		var slaarray;
		slaarray = <?php echo $slas; ?>;
		
		// // slaArray = <?php echo $slas; ?>;
		// $('#watcher').select2().select2('val', keywordArray);
		// // console.log('$model->sla');;
		// // console.log('keywordArray');
		// // console.log(keywordArray);
		// var slaArray = "";
		// $slaArray = explode(',', <?php echo $model['sla'] ?>);
		// // $slaArray = json_encode($slas);
		// console.log('$model->sla');
		// console.log(slaArray);
		$('#sla').select2().select2('val', slaarray);
		

		var matchingsArray;
		matchingsArray = <?php echo $matchings; ?>;
		$('#matchings').select2().select2('val', matchingsArray);

		$('form .new').on('click', function () {

			let form = $(this.form);
			$.ajax({
				type:    'POST',
				url:     '/service/escalatesave',
				data:    form.serialize(),
				success: function (data) {
					window.location = window.location;
				}
			});

			return false;
		});

		$('form .save').on('click', function () {

			let form = $(this.form);
			$.ajax({
				type:    'POST',
				url:     '/service/escalatesave',
				data:    form.serialize(),
				success: function (data) {
					window.location = window.location;
				}
			});

			return false;
		});

		$('.delete').on('click', function () {
			let csfr = "<?= Yii::app()->request->csrfToken ?>";
			let eId  = $(this).data('id');
			$.ajax({
				type:    'POST',
				url:     '/service/escalatedel',
				data:    {id: eId, YII_CSRF_TOKEN: csfr},
				success: function (data) {
					window.location = window.location;
				}
			});

			return false;
		});

		$('.manager').on('change', function () {
			if ($(this).val()) {
				$(this).closest('.escalate').find('.group').prop('disabled', true);
			} else {
				$(this).closest('.escalate').find('.group').prop('disabled', false);
			}
		});
		$('.group').on('change', function () {
			if ($(this).val()) {
				$(this).closest('.escalate').find('.manager').prop('disabled', true);
			} else {
				$(this).closest('.escalate').find('.manager').prop('disabled', false);
			}
		});
	});
</script>
