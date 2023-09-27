<?php if(Yii::app()->params['WidgetEnabled'] == 1): ?>
    <?php Yii::app()->clientScript->registerCssFile('/css/AdminLTE.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile('/css/sweetalert2.min.css'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/sweetalert2.min.js'); ?>
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
    Yii::app()->bootstrap->registerAssetCss('bootstrap-toggle-buttons.css');
    Yii::app()->bootstrap->registerAssetJs('jquery.toggle.buttons.js');
    ?>
    <div class="row-fluid">
        <!-- quick email widget -->
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'onSubmit' => 'document.getElementById("create_btn").disabled=true;'
            ),
            'id' => 'request-form',
            'action' => Yii::app()->createUrl('/portal/createwidget'),
            'enableAjaxValidation' => false,
            )); ?>
            <div class="box box-default" style="height: 100%; overflow:auto">
                <div class="box-header"  style="text-align: center">
                    <i class="fa-solid fa-envelope fa-xl"> </i><h4 class="box-title" style="margin: 5px 0"> <?php echo Yii::app()->params['WidgetHeader']; ?></h4>
                    <hr style="margin: 5">
                </div>
                <div id="create" class="box-body" style="margin-left: 3px; margin-right: 3px">
                    <div class="row-fluid">
                        <?php $this->widget('bootstrap.widgets.TbAlert', array(
                            'block' => true,
                            'fade' => true,
                            'closeText' => '×',
                            )); ?>
                        <?php echo $form->textFieldRow($model, 'depart', array(
                            'maxlength' => 100,
                            'class' => 'span12',
                            'placeholder' => Yii::t('main-ui', 'Here your email')
                            )); ?>
                        </div>
                    <?php if(isset(Yii::app()->params['WidgetService']) AND Yii::app()->params['WidgetService'] == 1): ?>
                        <div class="row-fluid">
                            <?php
                            $services = Service::getAllShared();
                            foreach ($services as $key => $value) {
                                if (!isset($allServices[$key])) {
                                    $allServices[$key] = $value;
                                }
                            }
                            asort($allServices);
                            echo $form->select2Row($model, 'service_id', [
                                'data' => $allServices,
                                'multiple' => false,
                                'id' => 'service',
                                'options' => ['width' => '100%'],
                                'empty' => '',
                                'ajax' => [
                                    'type' => 'POST',
                                    'dataType' => 'json',
                                    'url' => CController::createUrl('Portal/SelectService'),
                                    'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                if (data.description || data.content){
                  if ($("#PortalRequest_Name").val() || $("textarea").val()){
                      swal({
                        title: "Внимание! Будут заменены введеные данные в поля Наименование и Содержание на шаблонные. Заменить значения?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "'.Yii::t('main-ui', 'Yes').'",
                        cancelButtonText: "'.Yii::t('main-ui', 'No').'",
                      }).then(function (result) {
                        if (result.value) {
                          if (data.description) $("#PortalRequest_Name").val(data.description);
                          if (data.content) $(".redactor-in-0").html(data.content);
                          if (data.content) $("textarea").val(data.content);
                        }
                      });
                  } else {
                    if (data.description) $("#PortalRequest_Name").val(data.description);
                    if (data.content) $(".redactor-in-0").html(data.content);
                    if (data.content) $("textarea").val(data.content);
                  }
                }
                $.ajax({
                  type: "POST",
                  url:  "/Portal/SetFields",
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
                        </div>
                    <?php endif; ?>
                        <div class="row-fluid">
                            <?php echo $form->textFieldRow($model, 'Name',
                            array('maxlength' => 100, 'class' => 'span12', 'placeholder' => Yii::t('main-ui', 'Ticket subject'))); ?>
                        </div>

                        <div class="row-fluid">
                            <small><?php echo Yii::t('main-ui', 'You can make screenshot by PrintScreen button and paste by Ctrl+V.'); ?> </small>
                            <?php
                            echo $form->textAreaRow($model, 'Content', array('id' => 'Content', 'rows' => 2));
                            echo '<div id="fields" class="row-fluid" style="display: none"></div>';
                            ?>
                            <?php Yii::app()->clientScript->registerScript('redactor-init', "
                               function addField(id) {
                                if(id){
                                    $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"PortalRequest[files][]\">');
                                }
                            }
                            $(function () {
                                $('#Content').redactor({
                                    lang: 'ru',
                                    maxHeight: 150,
                                    plugins: ['alignment', 'table', 'fullscreen', 'video'],
                                    buttons: ['format', 'bold', 'italic', 'image', 'lists', 'link'],
                                    imageResizable: true,
                                    imagePosition: true,
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
                        </div>

                        <br>
                        <div class="row-fluid">
                            <?php if (Yii::app()->params['WidgetFiles'] == 1) {
                                echo '<div class="form-group">
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
                                                                  "'. Yii::app()->params->max_file_msg . '",
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

                            }
                            ?>
                            <div class="span3">
                                <?php $this->widget('CCaptcha', array('clickableImage' => true, 'imageOptions' => array('width' => '150px'), 'showRefreshButton' => false)) ?>
                                <br/><br/>
                                <?php echo CHtml::activeTextField($model, 'verifyCode',
                                array('placeholder' => Yii::t('main-ui', 'Verify code'), 'class' => 'span12')); ?>
                                <?php echo $form->error($model, 'verifyCode'); ?>
                            </div>
                        </div>
                        
                        <?php if(Yii::app()->params['WidgetShowPersonal'] == 1): ?>
                            <div class="span12">
                                <input type="checkbox" checked/> <small>Я даю свое согласие на обработку персональных данных.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- <div class="box-footer clearfix" style="position: fixed; left: 0; bottom: 0; width: 95%; height: 30px"> -->
                        <div class="box-footer clearfix">
                            <?php $this->widget('bootstrap.widgets.TbButton', array(
                                'buttonType' => 'submit',
                                'id' => 'create_btn',
                                'type' => 'primary',
                                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
                                )); ?>
                            </div>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                <?php else: ?>
                    <h1>Виджет отключен!</h1>
                <?php endif; ?>