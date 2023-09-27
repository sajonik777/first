<?php


if (isset($fields)) {
    Yii::app()->bootstrap->registerAssetCss('bootstrap-toggle-buttons.css');
    Yii::app()->bootstrap->registerAssetJs('jquery.toggle.buttons.js');
}

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
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'htmlOptions' => [
            'enctype' => 'multipart/form-data',
            'onSubmit' => 'document.getElementById("create_btn").disabled=true;'
        ],
        'id' => 'request-form',
        'enableAjaxValidation' => false,
    ]); ?>

    <div class="box-body">
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <div class="row-fluid">
                <?php if (Yii::app()->user->checkAccess('canSetPriority')) : ?>
                    <?php if (Yii::app()->user->checkAccess('doNotSelectServiceCategories')): ?>
                        <div class="span6">
                            <?php
                            $user = CUsers::model()->findByPk(Yii::app()->user->id);
                            $services = $user->getServicesArray();
                            asort($services);
                            echo $form->select2Row($model, 'service_id', [
                                'data' => ['0' => ''] + $services,
                                'multiple' => false,
                                'id' => 'service',
                                'options' => ['width' => '100%'],
                                'empty' => '',
                                'ajax' => [
                                    'type' => 'POST',
                                    'dataType' => 'json',
                                    'url' => CController::createUrl('Request/SelectPriority'),
                                    'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                $("#Priority").html(data.options);
                if($("*").is("#watchers")) {
                  $("#watchers").select2().select2("val", data.watcher);
                  $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                }
                if (data.description || data.content){
                  if ($("#Request_Name").val() || $("textarea").val()){
                      swal({
                        title: "Внимание! Будут заменены введеные данные в поля Наименование и Содержание на шаблонные. Заменить значения?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                      }).then(function (result) {
                        if (result.value) {
                          if (data.description) $("#Request_Name").val(data.description);
                          if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                          if (data.content) $("textarea").val(data.content);
                        }
                      });
                  } else {
                    if (data.description) $("#Request_Name").val(data.description);
                    if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                    if (data.content) $("textarea").val(data.content);
                  }
                }
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
                    if (data){
                      $("#fields").show();
                    }else{
                      $("#fields").hide();
                    }
                  }
                });
              }',
                                ]
                            ]); ?>
                            <?php echo $form->hiddenField($model, 'CUsers_id', ['value' => Yii::app()->user->name]); ?>
                        </div>
                    <?php else: ?>
                        <div class="span3">
                            <?php echo $form->select2Row($model, 'service_category_id', [
                                'data' => ServiceCategories::allForExistingServices(),
                                'multiple' => false,
                                'id' => 'service_category_id',
                                'prompt' => '',
                                'options' => ['width' => '100%']
                            ]); ?>
                        </div>
                        <div class="span3">
                            <?php
                            $user = CUsers::model()->findByPk(Yii::app()->user->id);
                            $services = $user->getServicesArray();
                            asort($services);
                            echo $form->select2Row($model, 'service_id', [
                                'data' => ['0' => ''] + $services,
                                'multiple' => false,
                                'id' => 'service',
                                'options' => ['width' => '100%'],
                                'empty' => '',
                                'ajax' => [
                                    'type' => 'POST',
                                    'dataType' => 'json',
                                    'url' => CController::createUrl('Request/SelectPriority'),
                                    'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                $("#Priority").html(data.options);
                if($("*").is("#watchers")) {
                  $("#watchers").select2().select2("val", data.watcher);
                  $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                }
                if (data.description || data.content){
                  if ($("#Request_Name").val() || $("textarea").val()){
                      swal({
                        title: "Внимание! Будут заменены введеные данные в поля Наименование и Содержание на шаблонные. Заменить значения?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                      }).then(function (result) {
                        if (result.value) {
                          if (data.description) $("#Request_Name").val(data.description);
                          if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                          if (data.content) $("textarea").val(data.content);
                        }
                      });
                  } else {
                    if (data.description) $("#Request_Name").val(data.description);
                    if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                    if (data.content) $("textarea").val(data.content);
                  }
                }
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
                    if (data){
                      $("#fields").show();
                    }else{
                      $("#fields").hide();
                    }
                  }
                });
              }',
                                ]
                            ]); ?>
                            <?php echo $form->hiddenField($model, 'CUsers_id', ['value' => Yii::app()->user->name]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="span3">
                        <?php echo $form->dropDownListRow($model, 'ZayavCategory_id', Category::all(),
                            ['class' => 'span12']); ?>
                    </div>
                    <div class="span3">
                        <?php echo $form->dropDownListRow($model, 'Priority', Zpriority::model()->all(),
                            ['id' => 'Priority', 'class' => 'span12']); ?>
                    </div>
                <?php else : ?>
                    <?php if (Yii::app()->user->checkAccess('doNotSelectServiceCategories')): ?>
                        <div class="span8">
                            <?php
                            $user = CUsers::model()->findByPk(Yii::app()->user->id);
                            $services = $user->getServicesArray();
                            asort($services);
                            echo $form->select2Row($model, 'service_id', [
                                'data' => ['0' => ''] + $services,
                                'multiple' => false,
                                'id' => 'service',
                                'options' => ['width' => '100%'],
                                'empty' => '',
                                'ajax' => [
                                    'type' => 'POST',
                                    'dataType' => 'json',
                                    'url' => CController::createUrl('Request/SelectPriority'),
                                    'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                $("#Priority").html(data.options);
                if($("*").is("#watchers")) {
                  $("#watchers").select2().select2("val", data.watcher);
                  $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                }
                if (data.description || data.content){
                  if ($("#Request_Name").val() || $("textarea").val()){
                      swal({
                        title: "Внимание! Будут заменены введеные данные в поля Наименование и Содержание на шаблонные. Заменить значения?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                      }).then(function (result) {
                        if (result.value) {
                          if (data.description) $("#Request_Name").val(data.description);
                          if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                          if (data.content) $("textarea").val(data.content);
                        }
                        if (result.dismiss) {
													
                       									if (prev_fields) {
                       										$(prev_fields).each(function(index, input) {
                       											let $input = $("#" + input.id);
                       			
                       										
                       											if ($input) {
                       												$input.prop("value", input.value);
                       			
																	if (input.checked) {
																	$input.parent().parent().siblings("label")
																		.trigger("mousedown")
																		.trigger("mouseup")
																		.trigger("click");
                       												}
                       											}
                       										})
                       									}
                       								}
                      });
                  } else {
                    if (data.description) $("#Request_Name").val(data.description);
                    if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                    if (data.content) $("textarea").val(data.content);
                  }
                }
                
                window.prev_fields = [];
                						
                						$("#fields input").each(function(index, input) {
                							window.prev_fields.push({
                								id: input.id,
                								value: input.value,
                								checked: input.type === "checkbox" ? input.checked : null
                							})
                						});
                
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
                    if (data){
                      $("#fields").show();
                    }else{
                      $("#fields").hide();
                    }
                  }
                });
              }',
                                ]
                            ]); ?>
                            <?php echo $form->hiddenField($model, 'CUsers_id', ['value' => Yii::app()->user->name]); ?>
                        </div>
                    <?php else: ?>
                        <div class="span4">
                            <?php echo $form->select2Row($model, 'service_category_id', [
                                'data' => ServiceCategories::allForExistingServices(),
                                'multiple' => false,
                                'id' => 'service_category_id',
                                'prompt' => '',
                                'options' => ['width' => '100%']
                            ]); ?>
                        </div>
                        <div class="span4">
                            <?php
                            $user = CUsers::model()->findByPk(Yii::app()->user->id);
                            $services = $user->getServicesArray();
                            asort($services);
                            echo $form->select2Row($model, 'service_id', [
                                'data' => ['0' => ''] + $services,
                                'multiple' => false,
                                'id' => 'service',
                                'options' => ['width' => '100%'],
                                'empty' => '',
                                'ajax' => [
                                    'type' => 'POST',
                                    'dataType' => 'json',
                                    'url' => CController::createUrl('Request/SelectPriority'),
                                    'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                $("#Priority").html(data.options);
                if($("*").is("#watchers")) {
                  $("#watchers").select2().select2("val", data.watcher);
                  $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                }
                if (data.description || data.content){
                  if ($("#Request_Name").val() || $("textarea").val()){
                      swal({
                        title: "Внимание! Будут заменены введеные данные в поля Наименование и Содержание на шаблонные. Заменить значения?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                      }).then(function (result) {
                        if (result.value) {
                          if (data.description) $("#Request_Name").val(data.description);
                          if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                          if (data.content) $("textarea").val(data.content);
                        }
                        if (result.dismiss) {
                       									if (prev_fields) {
                       										$(prev_fields).each(function(index, input) {
                       											let $input = $(input.id);
                       			
                       											if ($input) {
                       												$input.prop("value", input.value);
                       			
																	if (input.checked) {
                       													$input.prop("value", true);
                       												}
                       											}
                       										})
                       									}
                       								}
                      });
                  } else {
                    if (data.description) $("#Request_Name").val(data.description);
                    if (data.content) {
                          if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                          }
                    if (data.content) $("textarea").val(data.content);
                  }
                }
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
                    if (data){
                      $("#fields").show();
                    }else{
                      $("#fields").hide();
                    }
                  }
                });
              }',
                                ]
                            ]); ?>
                            <?php echo $form->hiddenField($model, 'CUsers_id', ['value' => Yii::app()->user->name]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="span4">
                        <?php echo $form->dropDownListRow($model, 'ZayavCategory_id', Category::all(),
                            ['class' => 'span12']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row-fluid">
                <?php if (Yii::app()->user->checkAccess('canSetFieldsRequest') AND !Yii::app()->user->checkAccess('downfieldsRequest')) {
                    echo '<div id="fields" class="row-fluid" style="display: none"></div>';
                } ?>
            </div>
            <div class="row-fluid">
                <?php echo $form->textFieldRow($model, 'Name', ['maxlength' => 100, 'class' => 'span12']); ?>
            </div>
            <div class="row-fluid">
                <?php
                echo $form->textAreaRow($model, 'Content', ['id' => 'Content', 'rows' => 5]);
                ?>
                <?php Yii::app()->clientScript->registerScript('redactor-init', "
                    function addField(id) {
                      if(id){
                        $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Request[files][]\">');
                      }
                    }
                    $(function () {
                      $('#Content').redactor({
                          lang: 'ru',
                          plugins: ['alignment', 'table','fullscreen', 'video'],
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
                <small><?php echo Yii::t('main-ui', 'You can make screenshot by PrintScreen button and paste by Ctrl+V.'); ?> </small>
                <?php /* echo $form->redactorRow($model, 'Content', array(
                                                      'rows' => 5,
                                                      'options' => array(
                                                      'fileUpload' => Yii::app()->user->checkAccess('uploadFilesRequest') ? $this->createUrl('site/fileUpload') : false,
                                                      'imageUpload' => Yii::app()->user->checkAccess('uploadFilesRequest') ? $this->createUrl('site/imageUpload') : false,
                                                      'uploadImageFields' => [
                                                      'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken,
                                                    ],
                                                    'uploadFileFields' => [
                                                    'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken,
                                                  ],
                                                  'lang' => 'ru',
                                                  'plugins' => array('fullscreen', 'fontsize', 'fontfamily', 'table', 'video'),
                                                  )
                                                  )); */ ?>
                <?php if (Yii::app()->user->checkAccess('canSetFieldsRequest') AND Yii::app()->user->checkAccess('downfieldsRequest')) {
                    echo '<div id="fields" class="row-fluid" style="display: none"></div>';
                } ?>
                <div id="screens"></div>
                <br/>
                <?php if (Yii::app()->user->checkAccess('canSetObserversRequest')) : ?>
                    <?php echo $form->select2Row($model, 'watchers', [
                        'data' => CUsers::model()->wall(),
                        'id' => 'watchers',
                        'multiple' => 'multiple',
                        'options' => [
                            'maxlength' => 500,
                            'width' => '100%',
                            'tokenSeparators' => [','],
                        ],
                    ]);
                    ?>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('canSetUnitRequest')) : ?>
                    <br/>
                    <?php echo $form->select2Row($model, 'cunits', [
                        //'data' => Cunits::uall(),
                        'data' => Yii::app()->user->checkAccess('unitByUserRequest') ? Cunits::uall() : Cunits::call(),
                        'multiple' => 'multiple',
                        'options' => [
                            'maxlength' => 500,
                            'width' => '100%',
                            'tokenSeparators' => [','],
                        ],
                    ]);
                    ?>
                <?php endif; ?>
                <br/>

                <?php if (Yii::app()->user->checkAccess('uploadFilesRequest')) : ?>
                    <?php if ($model->image == null) {
                        echo '
                                                      <div class="form-group">
                                                      <div class="btn btn-default btn-file">
                                                      <i class="fa-solid fa-paperclip"></i> ' . Yii::t('main-ui', 'Upload files');
                        $this->widget('CMultiFileUpload', [
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
                        ]);
                        echo '</div>
                                                      <p class="help-block">' . Yii::t('main-ui',
                                'Max.') . ' ' . Yii::app()->params->max_file_size . ' Kb</p>
                                                      <div class="MultiFile-list" id="image_wrap"></div>
                                                      </div>';
                    }
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'buttonType' => 'submit',
            'type' => 'primary',
            'id' => 'create_btn',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        ]); ?>
    </div>
    <?php echo CHtml::hiddenField('merged-items', $merged_items); ?>

    <?php $this->endWidget(); ?>
</div>
<script>
    $(document).ready(function () {
        var id = $("#service").val();
        var serviceCategoryId = $("#service_category_id").val();
        var user = $("#CUsers_id").val();
        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        var lang = "<?php echo Yii::app()->params["languages"]; ?>";
        var copy = "<?php echo $copy; ?>";
        $.ajax({
            type: "POST",
            url: "/request/setfields2",
            data: {"id": id, "YII_CSRF_TOKEN": csrf},
            dataType: "text",
            cache: false,
            update: "#fields",
            error: function (e) {
                console.log(e);
            },
            success: function (data) {
                $("#fields").css({'display': 'block'});
                $("#fields").html(data);
                if (copy != true && user) {
                    $.ajax({
                        type: "POST",
                        url: "/request/getservices",
                        data: {"user": user, "YII_CSRF_TOKEN": csrf, "category_id": serviceCategoryId},
                        dataType: "json",
                        cache: false,
                        error: function (e) {
                            console.log(e);
                        },
                        success: function (json) {
                            $("#service").html("");
                            $("#service").append("<option></option>");
                            $.each(json, function (index, value) {
                                $("#service").append(
                                    "<option value=\"" + json[index].id + "\">" + json[index].text + "</option>"
                                );
                            });
                            $("#service").val(id);
                        }
                    });
                }
            }
        });
    });
    $("#service_category_id").change(function () {
        let serviceCategoryId = $("#service_category_id").val();
        let user = "<?php echo Yii::app()->user->name ?>";
        let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        $.ajax({
            type: "POST",
            url: "/request/getservices",
            data: {"user": user, "YII_CSRF_TOKEN": csrf, "category_id": serviceCategoryId},
            dataType: "json",
            cache: false,
            error: function (e) {
                console.log(e);
            },
            success: function (json) {
                $("#service").html("");
                $("#service").append("<option></option>");
                $.each(json, function (index, value) {
                    $("#service").append(
                        "<option value=\"" + json[index].id + "\">" + json[index].text + "</option>"
                    );
                });
                $("#service").change();
            }
        });
    });
</script>
