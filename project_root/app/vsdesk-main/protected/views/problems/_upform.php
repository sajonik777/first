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
        <div class="row-fluid">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'problems-form',
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                'enableAjaxValidation' => false,
            )); ?>
            <div class="row-fluid">
                <div class="span4">
                    <?php echo $form->errorSummary($model); ?>
                    <?php echo $form->dropDownListRow($model, 'status', Pstatus::all(), array('class' => 'span12')); ?>
                    <?php echo $form->dropDownListRow($model, 'priority', Zpriority::all(),
                        array('class' => 'span12')); ?>
                </div>
                <div class="span4">
                    <?php echo $form->dropDownListRow($model, 'category', ProblemCats::all(),
                        array('class' => 'span12')); ?>
                    <?php echo $form->dropDownListRow($model, 'service', Service::sall(),
                        array('class' => 'span12')); ?>
                </div>
                <div class="span4">
                    <?php echo $form->dropDownListRow($model, 'influence', Influence::all(),
                        array('class' => 'span12')); ?>
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
                            $('textarea').redactor({
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
                <?php
                echo $form->textAreaRow($model, 'workaround', array('id' => 'workaround', 'rows' => 5));
                ?>
                <?php
                echo $form->textAreaRow($model, 'decision', array('id' => 'decision', 'rows' => 5));
                ?>
                <?php /* echo $form->textAreaRow($model, 'description', array('class' => 'span12', 'rows' => 4, 'cols' => 50)); */ ?>
                <?php /* echo $form->redactorRow($model, 'workaround', array(
                    'rows' => 5,
                    'options' => array(
                        'fileUpload' => Yii::app()->user->checkAccess('uploadFilesProblem') ? $this->createUrl('site/fileUpload') : false,
                        'imageUpload' => Yii::app()->user->checkAccess('uploadFilesProblem') ? $this->createUrl('site/imageUpload') : false,
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

                <?php /* echo $form->redactorRow($model, 'decision', array(
                    'rows' => 5,
                    'options' => array(
                        'fileUpload' => Yii::app()->user->checkAccess('uploadFilesProblem') ? $this->createUrl('site/fileUpload') : false,
                        'imageUpload' => Yii::app()->user->checkAccess('uploadFilesProblem') ? $this->createUrl('site/imageUpload') : false,
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

                <?php if ($model->knowledge_trigger == 0): ?>
                    <?php echo $form->toggleButtonRow($model, 'knowledge_trigger'); ?>
                <?php endif; ?>
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
                                    var formData = new FormData($(\'form\')[0]);
                                    $.ajax({
                                        type: "POST",
                                        dataType : "json",
                                        processData: false,
                                        contentType: false,
                                        url: "' . $this->createUrl('files/upload') . '",
                                        data: formData
                                        })
                                        .done(function( data ) {
                                            addField(data.id);
                                            console.log("файл отправлен");
                                        });
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
                <?php endif; ?>
                <?php
                if (!empty($model->files)) {
                    foreach ($model->files as $fileId => $fileName) {
                        echo '<input id="file' . $fileId . '" type="hidden" value="' . $fileId . '" name="Requests[files][]">';
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Выберите пользователя'); ?></h4>
</div>

<div class="modal-body">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'adduser-form',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/problems/assign', array('id' => $model->id)),
    )); ?>
    <div class="row-fluid">
        <?php $this->widget(
            'bootstrap.widgets.TbSelect2',
            array(
                'model' => $model,
                'name' => 'users',
                'data' => CUsers::all(),
                'htmlOptions' => array(
                    'class' => 'span12',
                ),
            )
        ); ?>
    </div>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('main-ui', 'Assign'),
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
