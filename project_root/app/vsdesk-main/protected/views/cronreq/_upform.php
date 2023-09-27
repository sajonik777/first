<style>
    input[type="text"] {
        height: 24px;
    }
</style>
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
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
        'id' => 'request-form',
        'enableAjaxValidation' => false,
    )); ?>
    <div class="box-body">
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <div class="span2">
                <?php echo $form->toggleButtonRow($model, 'enabled'); ?>
            </div>
            <div class="span2">
                <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                echo $form->labelEx($model, 'Date');
                echo '<div class="dtpicker">';
                $this->widget('CJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'Date',
                    'mode' => 'datetime', //use "time","date" or "datetime" (default)
                    'language' => 'ru',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    ),
                    'htmlOptions' => array('class' => 'betweenDatepicker'),
                ));
                echo '</div>';
                ?>

            </div>
            <div class="span2">
                <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                echo $form->labelEx($model, 'Date_end');
                echo '<div class="dtpicker">';
                $this->widget('CJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'Date_end',
                    'mode' => 'datetime', //use "time","date" or "datetime" (default)
                    'language' => 'ru',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    ),
                    'htmlOptions' => array('class' => 'betweenDatepicker'),
                ));
                echo '</div>';
                ?>

            </div>
            <div class="span3">
                <?php echo $form->colorpickerRow($model, 'color', array('class' => 'span12')); ?>
            </div>
            <div class="span3">
                <?php echo $form->dropDownListRow($model, 'repeats', [0 => 'Не повторять', 1 => 'Каждый день', 5 => 'Раз в 2 дня', 6 => 'Раз в 3 дня', 7 => 'Раз в 4 дня', 8 => 'Раз в 5 дней', 9 => 'Раз в 6 дней', 2 => 'Раз в неделю', 10 => 'Раз в 2 недели', 11 => 'Раз в 3 недели', 3 => 'Раз в месяц', 12 => 'Раз в 2 месяца', 13 => 'Раз в 3 месяца', 14 => 'Раз в 4 месяца', 15 => 'Раз в 5 месяцев', 16 => 'Раз в 6 месяцев', 4 => 'Раз в год'], array('class' => 'span12')); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <div>
                    <nobr>
                        <?php echo $form->select2Row($model, 'CUsers_id', [
                            'data' => CHtml::listData(CUsers::model()->findAllByAttributes(array('active' => 1)), 'Username', 'fullname'),
                            'multiple' => false,
                            'id' => 'CUsers_id',
                            'prompt' => '',
                            'options' => ['width' => '100%'],
                            'ajax' => [
                                'type' => 'POST',
                                'url' => CController::createUrl('Request/SelectAdmObject'),
                                'update' => '#cunits',
                                'success' => 'function(data) {
                              var csrf = "' . Yii::app()->request->csrfToken . '";
                              var user = $("#CUsers_id").val();
                              $.ajax({
                                    type: "POST",
                                    url:  "/request/getservices",
                                    data: {"user":user, "YII_CSRF_TOKEN":csrf},
                                    dataType: "json",
                                    cache: false,
                                    error: function(e) {
                                        console.log(e);
                                    },
                                    success: function(json) {
                                        $("#service_id").html("");
                                      $("#service_id").append("<option></option>");
                                      $.each(json, function(index, value) {
                                          $("#service_id").append(
                                              "<option value=\"" + json[index].id + "\">" + json[index].text + "</option>"
                                          );
                                      });
                                      $("#service_id").change();
              }
            });
                              }'
                            ]
                        ]);
                        ?>
                    </nobr>

                    
                </div>
            </div>
            <div class="span3">
                <?php echo CHtml::hiddenField('merged-items', $merged_items); ?>
                <nobr>
                    <?php echo $form->select2Row($model, 'service_id', [
                        'data' => array('0' => '') + Service::model()->all(),
                        'multiple' => false,
                        'id' => 'service_id',
                        'options' => ['width' => '100%'],
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('cronreq/SelectPriority'),
                            'success' => 'function(data) {
                                var id = data.fid;
                                var csrf = data.csrf;
                                $("#Priority").html(data.options);
                                    $(".redactor-in-0").html(data.content);
                                    if($("*").is("#watchers")) {
                                        $("#watchers").select2().select2("val", data.watcher);
                                        $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                                    }
                                    $("#CronReq_Name").val(data.description);

                                    $("textarea").val(data.content);
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
                                    $.ajax(
                                        {
                                        type: "POST",
                                        url: "/request/selectSLA",
                                        data: {
                                            "service_id": $("#service_id").find(":selected").val(),
                                            "YII_CSRF_TOKEN": csrf
                                        },
                                        dataType: "text",
                                        cache: false,
                                        error: function(e) {
                                            console.log("error", e);
                                        },
                                        success: function(data) {
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
                                        $.ajax({
                                            type: "POST",
                                            url:  "/request/getservices",
                                            data: {"user":user, "YII_CSRF_TOKEN":csrf, "category_id":serviceCategoryId},
                                            dataType: "json",
                                            cache: false,
                                            error: function(e) {
                                              console.log(e);
                                            },
                                            success: function(json) {
                                              $("#service_id").html("");
                                              $("#service_id").append("<option></option>");
                                              $.each(json, function(index, value) {
                                                $("#service_id").append(
                                                  "<option value=\"" + json[index].id + "\">" + json[index].text + "</option>"
                                                );
                                              });
                                              
                                              $("#service_id option").each(function(index, option) {
                                                  if ($(option).text() === selectedText) {
                                                      $(option).prop("selected", true);
                                                  }
                                              })
                                              
                                              $("#service_id").change();
                                            }
                                          });
                        }',
                        )
                    ]); ?>
                </nobr>
            </div>
            <div class="span3">

                <label for="cronreq_sla"><?php echo Yii::t('main-ui', 'SLA') ?></label>
                <select class="span12" id="cronreq_sla" name="CronReq[sla]">
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
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="span6">
                    <?php
                    $role = Roles::model()->findByAttributes(array('value' => strtolower(Yii::app()->user->role)));
                    $list_data = CHtml::listData($role->status_rl, 'name', 'name');
                    echo $form->dropDownListRow($model, 'Status', $list_data, array('class' => 'span12'));
                    ?>
                </div>
                <div class="span3">
                    <?php echo $form->dropDownListRow($model, 'ZayavCategory_id', Category::model()->all(), array('class' => 'span12')); ?>
                </div>
                <div class="span3">
                    <?php echo $form->dropDownListRow($model, 'Priority', Zpriority::model()->all(), array('id' => 'Priority', 'class' => 'span12')); ?>
                </div>
            </div>
            <?php if (Yii::app()->user->checkAccess('canSetFieldsRequest')) {
                if ($model->fields && $model->fields != "null") {
                    $fields = json_decode($model->fields);
                    foreach ($fields as $field) {
                        $required = $field->req ? ' <span class="required"> *</span>' : '';
                        if ($field->type == 'textFieldRow') {
                            echo '<div class="row-fluid"><label for="FieldsetsFields_' . $field->id . '">' . $field->name . $required . '</label>
            <input class="span12" maxlength="500" name="Request[' . $field->id . ']" id="Request_' . $field->id . '" type="text" value="' . $field->value . '"></div>';
                        }
                        if ($field->type == 'date') {
                            $model2 = new FieldsetsFields;
                            echo '<div class="row-fluid">';
                            echo '<label for="Request_' . $field->id . '">' . $field->name . $required . '</label>';
                            echo '<div class="dtpicker2">';
                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'name' => "Request[" . $field->id . "]",
                                'value' => $field->value,
                                'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                                'defaultOptions' => array(
                                    'dateFormat' => 'dd.mm.yy',
                                    'showButtonPanel' => true,
                                    'changeYear' => true,
                                )
                            ));
                            echo '</div>';
                            echo '</div>';
                            echo '<script type="text/javascript" src="/js/jquery.ui.datepicker-ru.js"></script>';
                            echo '<script>
                    jQuery.datepicker.setDefaults({\'dateFormat\':\'dd.mm.yy\',\'showButtonPanel\':true,\'changeYear\':true});
                    jQuery(\'#Request_' . $field->id . '\').datepicker([]);
                </script>';
                        }
                        if ($field->type == 'toggle') {
                            echo '<div class="row-fluid">';
                            echo '<label for="Request_' . $field->id . '">' . $field->name . $required . '</label>';
                            $this->widget(
                                'bootstrap.widgets.TbToggleButton',
                                array('name' => "Request[" . $field->id . "]", 'value' => $field->value)
                            );
                            echo '</div>';
                        }
                        if ($field->type == 'select') {
                            $model2 = new FieldsetsFields;
                            $fields_v = FieldsetsFields::model()->findByPk(array('id' => $field->id));
                            $list = explode(",", $fields_v->value);
                            $selected = array($field->value => array('selected' => 'selected'));
                            $arr = array();
                            foreach ($list as $value) {
                                $arr[$value] = $value;
                            }
                            echo '<div class="row-fluid">';
                            echo '<label for="Request_' . $field->name . '">' . $field->name . $required . '</label>';
                            echo CHtml::activeDropDownList(
                                $model2,
                                'value',
                                $arr,
                                array(
                                    'name' => "Request[" . $field->id . "]",
                                    'class' => 'span12',
                                    'options' => $selected,
                                )
                            );
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<div id="fields" class="row-fluid" style="display: none"></div>';
                }
            } ?>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php echo $form->textFieldRow($model, 'Name', array('maxlength' => 100, 'class' => 'span12')); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php
                echo $form->textAreaRow($model, 'Content', array('id' => 'Content', 'rows' => 5));
                ?>
                <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     $(function () {
                            $('#Content').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'video', 'fullscreen'],
                            });
                        });
                    ");
                ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php if (Yii::app()->user->checkAccess('canSetObserversRequest')) : ?>
                    <?php echo $form->select2Row($model, 'watchers', array(
                        'id' => 'watchers',
                        'data' => CUsers::model()->wall(),
                        'multiple' => 'multiple',
                        'options' => array(
                            'width' => '100%',
                            'tokenSeparators' => array(','),
                        ),
                    ));
                    ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <?php if (Yii::app()->user->checkAccess('canSetUnitRequest')) : ?>
                    <?php echo $form->select2Row($model, 'cunits', array(
                        'data' => Cunits::model()->all(),
                        'multiple' => 'multiple',
                        'id' => 'cunits',
                        'options' => array(
                            'width' => '100%',
                            'tokenSeparators' => array(','),
                        ),
                    ));
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="box-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'info',
        'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
</div>

<script>
    $(document).ready(function() {
        var id = $("#service_id").val();
        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        var lang = "<?php echo Yii::app()->params["languages"]; ?>";
        $.ajax({
            type: "POST",
            url: "/request/setfields2",
            data: {
                "id": id,
                "YII_CSRF_TOKEN": csrf
            },
            dataType: "text",
            cache: false,
            update: "#fields",
            error: function(e) {
                console.log(e);
            },
            success: function(data) {
                $("#fields").html(data);
                if (data) {
                    $("#fields").show();
                } else {
                    $("#fields").hide();
                }

            }
        });
        const getSla = () => {
            // console.log("a;sdhklahkflhka");
            let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
            console.log($("#service_id").find(":selected").val());

            $.ajax({
                type: "POST",
                url: "/request/selectSLA",
                data: {
                    "service_id": $("#service_id").find(":selected").val(),
                    "YII_CSRF_TOKEN": csrf
                },
                dataType: "text",
                cache: false,
                error: function(e) {
                    console.log("error", e);
                },
                success: function(data) {
                    let result = $.parseJSON(data);
                    $("#cronreq_sla").empty();

                    $.each(result, function(i, item) {
                        if (parseInt(<?php echo $model->sla; ?>) === parseInt(i)) {
                            $('#cronreq_sla').append($('<option>', {
                                value: i,
                                text: item,
                                selected: "selected"
                            }));
                        } else {
                            $('#cronreq_sla').append($('<option>', {
                                value: i,
                                text: item
                                // selected: selected
                            }));
                        }
                    });

                }
            });
        }
        getSla();
        $('#service_id').select2();
        $("#service_id").on("change", (s) => {
            let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
            console.log('s.target.value', s.target.value);
            $.ajax({
                type: "POST",
                url: "/request/selectSLA",
                data: {
                    "service_id": s.target.value,
                    "YII_CSRF_TOKEN": csrf
                },
                dataType: "text",
                cache: false,
                error: function(e) {
                    console.log('error', e);
                },
                success: function(data) {
                    console.log('data',$.parseJSON(data));
                    let result = $.parseJSON(data);
                    $('#cronreq_sla').empty();
                    // $('#cronreq_sla').append($('<option>', {
                    //     value: "",
                    //     text: "--- Выберите соглашение ---",
                    // }));
                    // <option disabled selected value> -- select an option -- </option>
                    $.each(result, function(i, item) {
                        if (parseInt(<?php echo $model->sla; ?>) === parseInt(i)) {
                            $('#cronreq_sla').append($('<option>', {
                                value: i,
                                text: item,
                                selected: "selected"
                            }));
                        } else {
                            $('#cronreq_sla').append($('<option>', {
                                value: i,
                                text: item
                                // selected: selected
                            }));
                        }
                    });
                    
                }
            });
        });

    });
</script>