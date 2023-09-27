<?php

Yii::app()->clientScript->registerScript('advanced', "
$('.contact-button').click(function(){
$('#advanced').toggle();
return false;
});
");
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
    <div class="box-body">
        <ul id="yw0" class="nav nav-pills">
            <li><a title="<?php echo Yii::t('main-ui', 'List companies'); ?>" href="/companies"><i
                            class="fa-solid fa-list-ul fa-xl"></i></a></li>
            <li><a title="<?php echo Yii::t('main-ui', 'Edit company details'); ?>" href="javascript:void(0);" class="contact-button"><i
                            class="fa-solid fa-users fa-xl"></i></a></li>
        </ul>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'companies-form',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'enableAjaxValidation' => true,

        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <h4><?php echo Yii::t('main-ui', 'Main information'); ?></h4>
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'director', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php //echo $form->textFieldRow($model, 'head_name_writeable', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php //echo $form->textFieldRow($model, 'head_position', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->dropDownListRow($model, 'city', Cities::model()->all_id(), array('empty' => '--- Выберите город ---', 'class' => 'span12')); ?>
            <label for="Companies_street"><?php echo Yii::t('main-ui', 'Street')?></label>
            <select class="span12" id="Companies_street" name="Companies[street]">
            </select>
            <?php //echo $form->dropDownListRow($model, 'street', Streets::model()->all_id(), array('class' => 'span12')); ?>
            <?php echo $form->textFieldRow($model, 'building', array('class' => 'span12', 'maxlength' => 500)); ?>
            <?php echo $form->textFieldRow($model, 'bcorp', array('class' => 'span12', 'maxlength' => 500)); ?>
            <?php echo $form->textFieldRow($model, 'bblock', array('class' => 'span12', 'maxlength' => 500)); ?>
            <?php //echo $form->textFieldRow($model, 'uraddress', array('class' => 'span12', 'maxlength' => 500)); ?>
            <?php //echo $form->textFieldRow($model, 'faddress', array('class' => 'span12', 'maxlength' => 500)); ?>
            <?php echo $form->textAreaRow($model, 'domains', array('class' => 'span12', 'rows' => 1)); ?>
        </div>
        <?php
        if(isset($update) AND $update == 1){
            $this->renderPartial('_ajaxform2', array('fields' => $fields));    
        } else {
            $criteria = new CDbCriteria(array('order' => 'sid ASC'));
            $fields = CompanyFieldset::model()->findAll($criteria);
            $this->renderPartial('_ajaxform', array('fields' => $fields));
        }
        
        ?>
        <br>
        <?php if (Yii::app()->user->checkAccess('uploadFilesCompany')): ?>
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
        <hr>
        <div id="advanced" style="display: none">
        <div class="row-fluid">
            <h4><?php echo Yii::t('main-ui', 'Contact information'); ?></h4>
            <?php echo $form->textFieldRow($model, 'contact_name', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'phone', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->dropDownListRow($model, 'manager', CUsers::all(), array('class' => 'span12', 'empty'=>Yii::t('main-ui', 'Select item'))); ?>
        </div>
        <hr>
        <div class="row-fluid">
            <h4><?php echo Yii::t('main-ui', 'Details'); ?></h4>
            <?php echo $form->textFieldRow($model, 'inn', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->textFieldRow($model, 'kpp', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->textFieldRow($model, 'ogrn', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->textFieldRow($model, 'bank', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'bik', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->textFieldRow($model, 'korschet', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'schet', array('class' => 'span12', 'maxlength' => 100)); ?>
        </div>
        <hr>
        <div class="row-fluid">
            <?php
            echo $form->textAreaRow($model, 'add1', array('id' => 'add1', 'rows' => 5));
            ?>
            <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     $(function () {
                            $('#add1').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen'],
                                imageResizable: true,
                                imagePosition: true, 
                            });
                        });
                    ");
            ?>
            <br>
            <?php
            echo $form->textAreaRow($model, 'add2', array('id' => 'add2', 'rows' => 5));
            ?>
            <?php Yii::app()->clientScript->registerScript('redactor2-init', "
                     $(function () {
                            $('#add2').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen'],
                                imageResizable: true,
                                imagePosition: true, 
                            });
                        });
                    ");
            ?>
        </div>
        </div>
        <?php if (!$model->isNewRecord): ?>
            <div class="row-fluid">
                <?php
                echo CHtml::label(Yii::t('main-ui', 'Services'), 'service');
                echo CHtml::DropDownList('service', null, CHtml::listData(Service::model()->findAll(), 'id', 'name'),
                    array(
                        'class' => 'span12',
                        'empty' => '',
                        'ajax' => array(
                            'type' => 'POST',
                            //тип запроса
                            'url' => CController::createUrl('/companies/serviceadd', array("company_id" => $model->id)),
                            //вызов контроллера c Ajax
                            'update' => '#services',
                            //id DIV - а в котором надо обновить данные
                        )
                    ));
                ?>
            </div>
            <div class="row-fluid">
                <div class="span12" id="services">
                    <?php $this->widget('bootstrap.widgets.TbGridView', array(
                        'id' => 'services-grid',
                        'dataProvider' => new CArrayDataProvider($model->services),
                        'type' => 'striped bordered condensed',
                        'htmlOptions' => array('style' => 'cursor: pointer'),
                        'columns' => array(
                            'name:text:'.Yii::t('main-ui', 'Services'),
                            array(
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                'template' => '{delete}',
                                'deleteButtonUrl' => 'Yii::app()->createUrl("/companies/servicedelete", array("service_id"=>$data->id, "company_id"=>"' . $model->id . '"))',
                            ),
                        ),
                    )); ?>
                </div>
            </div>
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

<script>
    $(document).ready(function () {

        const getStreet = () => {
            let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
            $.ajax({
                    type: "GET",
                    url: "/companies/getStreetsAndSelected",
                    data: {"city_id":  $("#Companies_city").find(":selected").val(), "company_id": 2, "YII_CSRF_TOKEN": csrf},
                    dataType: "text",
                    cache: false,
                    error: function (e) {
                        console.log('error', e);
                    },
                    success: function (data) {
                        let result = $.parseJSON(data);
                        $('#Companies_street').empty();
                        $.each(result[0], function (i, item) {
                            $('#Companies_street').append($('<option>', { 
                                value: i,
                                text : item,
                                selected : i == result[1] ? true : false
                            }));
                        });

                    }
                });
        }

        getStreet();
            
        $("#Companies_city").on("change", (s)=>{
            let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
            $.ajax({
                type: "GET",
                url: "/companies/getStreets",
                data: {"city_id":  s.target.value, "YII_CSRF_TOKEN": csrf},
                dataType: "text",
                cache: false,
                error: function (e) {
                    console.log('error', e);
                },
                success: function (data) {
                    let result = $.parseJSON(data);
                    $('#Companies_street').empty();
                    $('#Companies_street').append($('<option>', { 
                            value: "",
                            text : "--- Выберите улицу ---", 
                        }));
                    // <option disabled selected value> -- select an option -- </option>
                    $.each(result, function (i, item) {
                        $('#Companies_street').append($('<option>', { 
                            value: i,
                            text : item 
                        }));
                    });

                }
            });
        });
            
                
            });

</script>

