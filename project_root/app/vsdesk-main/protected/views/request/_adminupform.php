<?php


if (isset($fields)) {
    Yii::app()->bootstrap->registerAssetCss('bootstrap-toggle-buttons.css');
    Yii::app()->bootstrap->registerAssetJs('jquery.toggle.buttons.js');
} ?>
<?php
if (Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
    <?php

    Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
    Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
    Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
    Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
    Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
    Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
    Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

    ?>
<?php
endif; ?>
<style>
    #pending_time input[type="text"] {
        height: 24px;
    }
</style>
<?php
$s = Status::model()->findByAttributes(['close' => 8]);
$statusMatching = Status::model()->findByAttributes(['close' => 7]);
?>
<div class="row-fluid">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'htmlOptions' => [
            'enctype' => 'multipart/form-data',
        ],
        'id' => 'request-form',
        'enableAjaxValidation' => false,
    ]); ?>
    <?php
    echo $form->errorSummary($model); ?>
	<div class="row-fluid">
		<div class="span4">
            <?php
            echo $form->hiddenField($model, 'CUsers_id'); ?>
            <?php
            $role = Roles::model()->findByAttributes(['value' => strtolower(Yii::app()->user->role)]);
            echo '<label for="Request_Status">' . Yii::t('main-ui', 'Status') . '</label>';
            echo '<select class="span12" name="Request[Status]" id="Request_Status" onchange="pendingShow(this.options[this.selectedIndex].value)">';
            $service = Service::model()->findByPk($model->service_id);
            $sogls = explode(',', $service->matchings);
            foreach ($role->status_rl as $status) {
                if ($status->name == 'Согласовано'){
                    
                    if (in_array(Yii::app()->user->getId(), $sogls)){
                        echo "<option data-need_comment='{$status->is_need_comment}' data-need_rating='{$status->is_need_rating}' " . ($status->name != $model->Status ?: 'selected') . " value=\"{$status->name}\">{$status->name}</option>";
                    }
                }else{
                echo "<option data-need_comment='{$status->is_need_comment}' data-need_rating='{$status->is_need_rating}' " . ($status->name != $model->Status ?: 'selected') . " value=\"{$status->name}\">{$status->name}</option>";
                }
            }
            echo '</select>';

            echo '<div  class="row-fluid" id="pending_time" style="display: none;">';
            //echo $form->textFieldRow($model, 'pendingTime', array('class' => 'span12'));
            echo '<div class="dtpicker2">' . $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'pendingTime',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(

                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                    ),
                ),
                    true) . '</div>';
            echo '<div class="dtpicker2">';
            $this->widget(
                'bootstrap.widgets.TbTimePicker',
                array(
                    'model' => $model,
                    'attribute' => 'pTime',
                    'options' => array(
                        'showMeridian' => false,
                    ),
                    'htmlOptions' => array('class' => 'input-small',)
                ));
            echo '</div>';
            echo '</div>';
            ?>
		</div>
		<div class="span4">
            <?php
            if (!empty($fields)) {
                $disabled = false;
            } else {
                $disabled = false;
            }
            /** @var CUsers $user */
            $user = CUsers::model()->findByAttributes(['Username' => $model->CUsers_id]);
            if ($user) {
                $services = $user->getServicesArray();
            } else {
                $services = Service::getAllShared();
            }
            asort($services);
            if ($model->service_id == null) {
                $nservice = array(null => Yii::t('main-ui', 'Select service')) + $services;
            } else {
                $nservice = $services;
            }
            $criteria = new CDbCriteria(array('order' => 'name ASC'));
            ?>
            <?php
            echo $form->select2Row($model, 'service_id', [
                'data' => $disabled ? CHtml::listData(Service::model()->findAllByAttributes(array('id' => $model->service_id), $criteria), 'id', 'name') : $nservice,
                'multiple' => false,
                'id' => 'service',
                'disabled' => $disabled,
                'options' => ['width' => '100%'],
                'ajax' => array(
                    'type' => 'POST',
                    'dataType' => 'json',
                    'url' => CController::createUrl('Request/SelectPriority'),
                    'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                $("#Priority").html(data.options);
                $.ajax({
                  type: "POST",
                  url:  "/request/setfields",
                  data: {"id":id, "YII_CSRF_TOKEN":csrf},
                  dataType: "text",
                  cache: false,
                  update: "#fields",
                  error: function(e) {
                    console.log(e);
                  },
                  success: function(data) {
                    $("#fields").html(data);
                  }
                });

                $.ajax(
                    {
                      type: "POST",
                      url: "/request/selectSLA",
                      data: {
                        "service_id": $("#service").find(":selected").val(),
                        "YII_CSRF_TOKEN": csrf
                      },
                      dataType: "text",
                      cache: false,
                      error: function(e) {
                        console.log("error", e);
                      },
                      success: function(data) {
                        console.log("data", data);
                        let result = $.parseJSON(data);
                        $("#request_sla").empty();
                    
                        $.each(result, function(i, item) {
                          $("#request_sla").append($("<option>", {
                            value: i,
                            text: item
                          }));
                        });
            
                      }
                    });

                }',
                )
            ]); ?>
		</div>
        <div class="span2">

            <label for="request_sla"><?php echo Yii::t('main-ui', 'SLA') ?></label>
            <select class="span12" id="request_sla" name="Request[sla]">
            </select>


            
            &nbsp;
            <?php
            /*
                                   $this->widget('bootstrap.widgets.TbButton', array(
                                   'icon' => 'folder-open',
                                   'htmlOptions' => array(
                                   'data-toggle' => 'modal',
                                   'data-target' => '#myModal2',
                                 ),
                               ));
                               */ ?>
          </nobr>
        </div>
		<div class="span2">
            <?php
            echo $form->dropDownListRow($model, 'Priority', Zpriority::all(),
                array('id' => 'Priority', 'class' => 'span12')); ?>
		</div>
	</div>
	<div class="row-fluid" id="matching_block" style="display: none">
		<div class="span12">
            <?php

            if ($statusMatching->name === $model->Status) {
                $matchings = json_encode($model->getMatchingIds());
                $model->matchings = $model->getMatchingIds();
            } else {
                if ($model->service_rl) {
                    $matchings = explode(',', $model->service_rl->matchings);
                    $model->matchings = $matchings;
                    $matchings = json_encode($matchings);
                }
            }
            echo $form->select2Row($model, 'matchings', [
                'data' => CHtml::listData(CUsers::model()->findAllByAttributes(['active' => 1]), 'id', 'fullname'),
                'multiple' => true,
                'id' => 'matchings',
                'disabled' => $statusMatching->name === $model->Status,
                'options' => ['width' => '100%']
            ]);
            ?>
		</div>
	</div>
    <?php
    if (Yii::app()->user->checkAccess('canSetFieldsRequest') and !Yii::app()->user->checkAccess('downfieldsRequest')) {
        echo '<div id="fields" class="row-fluid">';
        $this->renderPartial('_ajaxform2', array('fields' => $fields));
        echo '</div>';
    } ?>
	<div class="row-fluid">
		<div class="span12">
            <?php
            if (Yii::app()->user->checkAccess('canEditContent')): ?>
                <?php
                echo $form->textAreaRow($model, 'Content', array('id' => 'Content', 'rows' => 5));
                ?>
                <?php
                Yii::app()->clientScript->registerScript('redactor-init', "
              function addField(id) {
                if(id){
                  $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Request[files][]\">');
                }
              }
              $(function () {
                $('#Content').redactor({
                  lang: 'ru',
                  plugins: ['alignment', 'table', 'fullscreen', 'video'],
                  linkValidation: false,
                  linkSize: 200,
                  imageResizable: true,
                  imagePosition: true,
                  multipleUpload: false,
                  imageUpload: '" . $this->createUrl('files/upload2') . "',
                  imageData: {'YII_CSRF_TOKEN': '" . Yii::app()->request->csrfToken . "'},
                  fileData: {'YII_CSRF_TOKEN': '" . Yii::app()->request->csrfToken . "'},
                    callbacks: {
                        image: {
                            uploaded: function (image, response) {
                                addField(response['file-0'].id);
                            },
                            uploadError: function (response) {
                                swal(response.message, 'ERROR!', 'error');
                            }
                        },
                        file: {
                            uploaded: function (file, response) {
                                addField(response['file-0'].id);
                            },
                            uploadError: function (response) {
                                swal(response.message, 'ERROR!', 'error');
                            }
                        }
                    }
                });
              });
              ");
                ?>
				<small><?php
                    echo Yii::t('main-ui', 'You can make screenshot by PrintScreen button and paste by Ctrl+V.'); ?> </small>
            <?php
            else: ?>
				<div class="row-fluid">
					<h3><?php
                        echo CHtml::encode($model->getAttributeLabel('Content')); ?>:</h3>
                    <?php
                    echo $model->Content; ?>
					<hr>
				</div>
                <?php
                echo $form->hiddenField($model, 'Content'); ?>
            <?php
            endif; ?>
            <?php
            if (Yii::app()->user->checkAccess('canSetFieldsRequest') and Yii::app()->user->checkAccess('downfieldsRequest')) {
                echo '<div id="fields" class="row-fluid">';
                $this->renderPartial('_ajaxform2', array('fields' => $fields));
                echo '</div>';
            } ?>
			<div id="screens"></div>
            <?php
            if (!empty($model->files)) {
                foreach ($model->files as $fileId => $fileName) {
                    echo '<input id="file' . $fileId . '" type="hidden" value="' . $fileId . '" name="Request[files][]">';
                }
            }
            ?>
			<br/>
            <?php
            if (Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
                <?php
                echo '
                <div class="form-group">
                <div class="btn btn-default btn-file">
                <i class="fa-solid fa-paperclip"></i> ' . Yii::t('main-ui', 'Upload files');
                $this->widget('CMultiFileUpload', array(
                    'name' => 'image',
                    'accept' => Yii::app()->params->extensions,
                    'duplicate' => Yii::app()->params->duplicate_message,
                    'denied' => Yii::app()->params->denied_message,
                    'htmlOptions' => [
                        'multiple' => true
                    ],
                    'options' => [
                        'list' => '#image_wrap',
                        'onFileSelect' => 'function(e ,v ,m){
                      var fileSize=e.files[0].size;
                      if(fileSize>' . (Yii::app()->params->max_file_size * 1024) . '){
                        swal(
                                                                  "' . Yii::app()->params->max_file_msg . '",
                                                                  "ERROR!",
                                                                  "error");     
                                                              return false;
                      }
                    }'
                    ],
                ));
                echo '</div>
                <p class="help-block">' . Yii::t('main-ui',
                        'Max.') . ' ' . Yii::app()->params->max_file_size . ' Kb</p>
                <div class="MultiFile-list" id="image_wrap"></div>
                </div>';

                ?>
            <?php
            endif; ?>
            <?php
            echo '</div>
              </div>';
            ?>
		</div>
	</div>

	<div class="box-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'id' => 'create_btn',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
	</div>

    <?php
    $this->endWidget(); ?>

</div>

<?php
if ($model->Managers_id == null and !Yii::app()->user->checkAccess('systemAdmin')): ?>
	<script>
		window.onbeforeunload = function () {
			var is_data_changed = true;
			var id              = "<?php echo $model->id; ?>";
			var csrf            = "<?php echo Yii::app()->request->csrfToken; ?>";
			$.ajax({
				type:     'POST',
				url:      '/msg/release',
				data:     {'id': id, 'YII_CSRF_TOKEN': csrf},
				dataType: 'text',
				cache:    false,
				error:    function (e) {
					console.log(e);
				}
			});
			return (is_data_changed ? "<?php echo Yii::t('main-ui',
                'You are exit from exclusive mode, other user can update this request!');?>" : null);
		};

		$('#request-form').bind('submit', function () {
			window.onbeforeunload = null;
		});
	</script>
<?php
endif; ?>
<?php
if (empty($fields)): ?>
	<script>
		$(document).ready(function () {
			var id   = $('#service').val();
			var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
			$.ajax({
				type:     'POST',
				url:      '/request/setfields2',
				data:     {'id': id, 'YII_CSRF_TOKEN': csrf},
				dataType: 'text',
				cache:    false,
				update:   '#fields',
				error:    function (e) {
					console.log(e);
				},
				success:  function (data) {
					$('#fields').css({'display': 'block'});
					$('#fields').html(data);
				}
			});
		});
	</script>
<?php
endif; ?>
<?php
$this->beginWidget('bootstrap.widgets.TbModal', ['id' => 'batchCommentModal', 'htmlOptions' => ['style' => 'height:auto;width:auto;']]); ?>
<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4><?php
        echo Yii::t('main-ui', 'Комментарий'); ?></h4>
</div>
<div class="modal-body" style="min-height: 80%;">
    <?php
    $this->renderPartial('_commentBatch', ['model' => $model]); ?>
</div>
<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', ['buttonType' => 'submit', 'label' => Yii::t('main-ui', 'Add comment'), 'type' => 'primary', 'id' => 'btnBatchComment']); ?>
</div>
<?php
$this->endWidget(); ?>
<script>


    $(document).ready(function() {
        // console.log($("#service").find(":selected").val());
        let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        $.ajax(
            {
                type: "POST",
                url: "/request/selectSLA",
                data: {
                "service_id": $("#service").find(":selected").val(),
                "YII_CSRF_TOKEN": csrf
                },
                dataType: "text",
                cache: false,
                error: function(e) {
                    console.log("error", e);
                },
                success: function(data) {
                    console.log("data", data);
                    let result = $.parseJSON(data);
                    $("#request_sla").empty();
            
                    $.each(result, function(i, item) {
                        if (parseInt(i) === parseInt(<?php echo $model->sla ?>)){
                            $("#request_sla").append($("<option>", {
                            selected: "selected",
                            value: i,
                            text: item
                        }));
                        }else{
                            $("#request_sla").append($("<option>", {
                                value: i,
                                text: item
                            }));
                        }
                        
                });

                }
            });

    });

	$(document).on('submit', '#request-form', function (e) {

		if ($('#Request_Status').find('option:selected').data('need_comment') == 1) {
			e.preventDefault();
			e.stopPropagation();
			$('#url').val("<?php echo CHtml::normalizeUrl(['Request/addsubs', 'id' => $model->id, 'nonredirect' => true]) ?>");
			jQuery('#batchCommentModal').modal({'show': true});
			if ($('#Request_Status').find('option:selected').data('need_rating') == 1) {
				jQuery('#batchCommentModal').find('.modal-rating').show();
			} else {
				jQuery('#batchCommentModal').find('.modal-rating').hide();
			}
			return false;
		}
		document.getElementById('create_btn').disabled = true;
	});

	$('#btnBatchComment').click(function (e) {

		if ($('#batchCommentModal .star-rating').hasClass('star-rating-on')) {
			text = $('#comment').val();
			if (text == '') {
				e.preventDefault();
				swal(
					'Вам необходимо добавить комментарий!',
					'ERROR!',
					'error');
			} else {
				e.preventDefault();
				$('#btnBatchComment').prop('disabled', true);
				jQuery.ajax({
					'type':    'POST',
					'url':     $('#url').val(),
					'cache':   false,
					'data':    $('#batchCommentForm').serialize(),
					'success': function (html) {
						jQuery('#batchCommentModal').modal('toggle');
						$
							.post('<?php echo CHtml::normalizeUrl(['Request/update', 'id' => $model->id]) ?>', $('#request-form').serialize())
							.then(() => {
								window.location = '<?php echo CHtml::normalizeUrl(['Request/view', 'id' => $model->id]) ?>';
							});
					}
				});
			}
		} else {
			if ($('#batchCommentModal .modal-rating').is(':visible')) {
				swal(
					'Вам необходимо поставить оценку!',
					'ERROR!',
					'error');
			} else {
				text = $('#comment').val();
				if (text == '') {
					e.preventDefault();
					swal(
						'Вам необходимо добавить комментарий!',
						'ERROR!',
						'error');
				} else {
					e.preventDefault();
					$('#btnBatchComment').prop('disabled', true);
					jQuery.ajax({
						'type':    'POST',
						'url':     $('#url').val(),
						'cache':   false,
						'data':    $('#batchCommentForm').serialize(),
						'success': function (html) {
							jQuery('#batchCommentModal').modal('toggle');
							$
								.post('<?php echo CHtml::normalizeUrl(['Request/update', 'id' => $model->id]) ?>', $('#request-form').serialize())
								.then(() => {
									window.location = '<?php echo CHtml::normalizeUrl(['Request/view', 'id' => $model->id]) ?>';
								});
						}
					});
				}
			}

		}

	});

	function pendingShow(index) {
		if (index == '<?= $s->name ?>')
			$('#pending_time').show();
		else
			$('#pending_time').hide();

		if (index == '<?= $statusMatching->name ?>')
			$('#matching_block').show();
		else
			$('#matching_block').hide();
	}

	$(document).ready(function () {
		pendingShow($('#Request_Status').val());
	});
</script>
