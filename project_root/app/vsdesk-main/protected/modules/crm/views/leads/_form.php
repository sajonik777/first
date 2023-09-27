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
            'id' => 'leads-form',
            'enableAjaxValidation' => false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <div class="span6">
                <?php

$list_data = CHtml::listData(Pipeline::model()->findAll(), 'id', 'name');
                echo $form->dropDownListRow($model, 'status_id', $list_data, array('class' => 'span12'));
                ?>
                <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 200, 'placeholder' => 'Название')); ?>
                <div class="input-append">
                    <?php
                    echo $form->labelEx($model, 'manager');
                    $this->widget(
                        'bootstrap.widgets.TbTypeahead',
                        array(
                            'model' => $model,
                            'attribute' => 'manager',
                            'options' => array(
                                'source' => CUsers::model()->eall(),
                                'items' => 4,
                                'matcher' => <<<ENDL
js:function(item) {
    return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
                            ),
                            'htmlOptions' => array(
                                'class' => 'span12', 'placeholder' => 'Ответственный'
                            ),
                        )
                    );
                    ?>
                    <span class="add-on"><i class="icon icon-user"></i></span>
                </div>
                <?php echo $form->textFieldRow($model, 'cost', array('class' => 'span12', 'maxlength' => 100, 'placeholder' => '0.00', 'append' => '<i class="icon icon-rub"></i>')); ?>
                <?php echo $form->textFieldRow($model, 'tag', array('class' => 'span12', 'maxlength' => 200)); ?>

            </div>
            <div class="span6">
                <div class="input-append">
                    <?php
                    echo $form->labelEx($model, 'contact');
                    $this->widget(
                        'bootstrap.widgets.TbTypeahead',
                        array(
                            'model' => $model,
                            'attribute' => 'contact',
                            'options' => array(
                                'source' => CUsers::model()->eall(),
                                'items' => 4,
                                'matcher' => <<<ENDL
js:function(item) {
    return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
                            ),
                            'htmlOptions' => array(
                                'class' => 'span12', 'placeholder' => 'Контакт'
                            ),
                        )
                    );
                    ?>
                    <span class="add-on"><i class="icon icon-user"></i></span>
                </div>
<br>
                <div class="input-append">
                    <?php
                    echo $form->labelEx($model, 'company');
                    $this->widget(
                        'bootstrap.widgets.TbTypeahead',
                        array(
                            'model' => $model,
                            'attribute' => 'company',
                            'options' => array(
                                'source' => Companies::model()->eall(),
                                'items' => 4,
                                'matcher' => <<<ENDL
js:function(item) {
    return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
                            ),
                            'htmlOptions' => array(
                                    'class' => 'span12', 'placeholder' => 'Компания'
                            ),
                        )
                    );
                    ?>
                    <span class="add-on"><i class="icon icon-users"></i></span>
                </div>
                <?php //echo $form->textFieldRow($model, 'contact', array('class' => 'span5', 'maxlength' => 200)); ?>

                <?php echo $form->textFieldRow($model, 'contact_phone', array('class' => 'span12', 'maxlength' => 200, 'placeholder' => 'Телефон', 'append' => '<i class="icon icon-phone"></i>')); ?>

                <?php echo $form->textFieldRow($model, 'contact_email', array('class' => 'span12', 'maxlength' => 200, 'placeholder' => 'Email', 'append' => '<i class="icon icon-envelope"></i>')); ?>

                <?php echo $form->textFieldRow($model, 'contact_position', array('class' => 'span12', 'maxlength' => 200, 'placeholder' => 'Должность', 'append' => '<i class="icon icon-sitemap"></i>')); ?>
            </div>
        </div>

        <?php
        echo $form->textAreaRow($model, 'description', array('id' => 'description', 'rows' => 5));
        ?>
        <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     $(function () {
                            $('#description').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen', 'video'],
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
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'info',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(function () {
        $("#Leads_contact").live("change", function () {
            var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
            var user = $("#Leads_contact").val();
            $.ajax({
                type: "POST",
                url: "/cusers/get_attr",
                data: {user: user,YII_CSRF_TOKEN: csrf},
                dataType: "json",
                cache: false,
                success: function(response) {
                    $("#Leads_contact_phone").val(response.phone);
                    $("#Leads_contact_email").val(response.email);
                    $("#Leads_contact_position").val(response.position);
                    $("#Leads_company").val(response.company);
                }
            });
            return false;
        });
    });
</script>