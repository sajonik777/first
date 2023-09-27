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
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
            )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'asset-form',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'clientOptions' => array(
             'validateOnSubmit' => true,
         )
         )); ?>
         <div class="row-fluid">
            <div class="span6">
                <h4><?php echo Yii::t('main-ui', 'Enter asset main info'); ?></h4>
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->dropDownListRow($model, 'asset_attrib_id', AssetAttrib::All(), array(
                    'prompt' => Yii::t('main-ui', 'Select item'),
                    'class' => 'span12',
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('Asset/UpdateAjax'),//вызов контроллера c Ajax
                        'update' => '#data',//id DIV - а в котором надо обновить данные
                        ))); ?>
                        <?php echo $form->hiddenField($model, 'id', array('value' => $model->id)); ?>
                        <?php echo $form->dropDownListRow($model, 'status', Astatus::All(), array('class' => 'span12')); ?>
                        <?php echo $form->textFieldRow($model, 'name', array('size' => '10', 'maxlength' => 50, 'class' => 'span12')); ?>
                        <?php echo $form->textFieldRow($model, 'location', array('size' => '10', 'maxlength' => 50, 'class' => 'span12')); ?>
                        <?php echo $form->textFieldRow($model, 'inventory', array('size' => '10', 'maxlength' => 50, 'class' => 'span12')); ?>
                        <?php echo $form->textFieldRow($model, 'cost', array('append' => Yii::t('main-ui', '.usd'), 'class' => 'span12', 'maxlength' => 50)); ?>
                        <?php echo $form->textareaRow($model, 'description', array('class' => 'span12', 'cols' => 6, 'rows' => 8)); ?>
                        <?php Yii::app()->clientScript->registerScript('redactor-init', "
                          $(function () {
                            $('#Asset_description').redactor({
                              lang: 'ru',
                              plugins: ['alignment', 'table', 'fullscreen', 'video'],
                              imageResizable: true,
                              linkValidation: false,
                              linkSize: 200,
                              imagePosition: true,
                        });
                    });
                    ");

?>
                </div>
                <div class="span6">
                    <div id="data">

                    </div>
                </div>
            </div>
        <br>
        <?php if (Yii::app()->user->checkAccess('uploadFilesAsset')): ?>
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
        <?php endif; ?>
        </div>
        <div class="row-fluid">
            <div class="box-footer">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
                    )); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        <?php $imp = json_encode($_POST['Asset']);
        ?>
        <script>
            $(document).ready(function () {
                var id  = $("#Asset_asset_attrib_id").val();
                var items = '<?php echo $imp; ?>';
                var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
                $.ajax({
                    type: "POST",
                    url: "/asset/UpdateAjax2",
                    data: {"id": id, "items": items, "YII_CSRF_TOKEN": csrf},
                    dataType: "text",
                    cache: false,
                    update: "#data",
                    error: function (e) {
                        console.log(e);
                    },
                    success: function (data) {
                        $("#data").css({'display': 'block'});
                        $("#data").html(data);
                    }
                });
            });
        </script>
