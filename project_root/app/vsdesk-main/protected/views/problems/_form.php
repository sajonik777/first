<?php


Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>
<div class="box">
    <div class="box-body">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'problems-form',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'enableAjaxValidation' => false,
        )); ?>
        <div class="row-fluid">
            <div class="span4">
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->dropDownListRow($model, 'status', Pstatus::all(), array('class' => 'span12')); ?>
                <?php echo $form->dropDownListRow($model, 'priority', Zpriority::all(), array('class' => 'span12')); ?>
            </div>
            <div class="span4">
                <?php echo $form->dropDownListRow($model, 'category', ProblemCats::all(),
                    array('class' => 'span12')); ?>
                <?php echo $form->dropDownListRow($model, 'service', Service::sall(), array('class' => 'span12')); ?>
            </div>
            <div class="span4">
                <?php echo $form->dropDownListRow($model, 'influence', Influence::all(), array('class' => 'span12')); ?>
                <label><?php echo Yii::t('main-ui', 'Service downtime (hh:mm)'); ?>:</label>
                <?php $this->widget(
                    'bootstrap.widgets.TbTimePicker',
                    array(
                        'model' => $model,
                        'attribute' => 'downtime',
                        'options' => array(
                            'showMeridian' => false,
                        ),
                        'htmlOptions' => array('class' => 'input-small',)
                    )); ?>
            </div>
        </div>
        <div class="row-fluid">
            <?php
            echo $form->textAreaRow($model, 'description', array('id' => 'description', 'rows' => 5));
            ?>
            <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     function addField(id) {
                        if(id){
                            $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Problems[files][]\">');
                        }
                     }
                     $(function () {
                            $('#description').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen', 'video'],
                                linkValidation: false,
                                linkSize: 200,
                                imageResizable: true,
                                imagePosition: true,
                                multipleUpload: false,
                                imageUpload: '" . $this->createUrl('/files/upload2') . "',
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
            <?php /* echo $form->textAreaRow($model, 'description', array('class' => 'span12', 'rows' => 4, 'cols' => 50)); */ ?>
            <br/>
        </div>
        <div class="row-fluid">
            <p><?php echo Yii::t('main-ui', 'Assign incidents'); ?></p>
            <?php $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'incidents',
                    'data' => Request::all(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'style' => 'width:100%',
                    ),
                )
            ); ?>
            <br/>
            <br/>
            <?php if (Yii::app()->user->checkAccess('uploadFilesProblem')): ?>
            <?php if ($model->image == null) {
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
        </div>
    </div>
    <?php endif; ?>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
