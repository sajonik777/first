<?php


Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

if (Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
    <?php //Yii::app()->clientScript->registerScriptFile('/js/lisshot.js');?>
<?php endif; ?>
<div class="box">
    <div class="box-body">
        <div class="row-fluid">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'onSubmit' => 'document.getElementById("create_btn").disabled=true;'
                ),
                'id' => 'request-form',
                'enableAjaxValidation' => false,
            )); ?>

            <div class="row-fluid">
                <?php echo $form->errorSummary($model); ?>

                <?php echo $form->textFieldRow($model, 'Name', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->hiddenField($model, 'CUsers_id', array('value' => Yii::app()->user->name)); ?>

            </div>

            <div class="row-fluid">
                <?php
                echo $form->textAreaRow($model, 'Content', array('id' => 'Content', 'rows' => 5));
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
                <?php /* echo $form->redactorRow($model, 'Content', array('rows' => 5, 'options' => array(
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
                ))); */ ?>
                <div id="screens"></div>
                <br/>
                <?php if (Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
                    <?php if ($model->image == null) {
                        echo '
                            <div class="row-fluid">
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
                          </div>
                          </div>';

                    }
                    ?>
                <?php endif; ?>
                <div class="box-footer">
                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'id' => 'create_btn',
                        'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
                    )); ?>
                </div>

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

