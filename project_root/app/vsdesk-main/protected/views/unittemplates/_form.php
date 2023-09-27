
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'unit-templates-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>
<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <?php echo $form->dropdownListRow($model,'type', array('1'=>Yii::t('main-ui','Unit'),'2'=>Yii::t('main-ui','Asset'),'3'=>Yii::t('main-ui','Request'),'4'=>Yii::t('main-ui','Contract'), '5'=>Yii::t('main-ui','Knowledge')),
        array(
            'class'=>'span12',
            'maxlength'=>100,
            'prompt' => Yii::t('main-ui', 'Select item'),
            'ajax' => array(
                'type' => 'POST',
                'dataType' => 'text',
                'url' => CController::createUrl('unittemplates/SelectType'),
                'update' => '#form',
                'error' => 'function(data) {
                console.log(data);
                }'
            )
            )); ?>
    <?php echo $form->dropdownListRow($model,'format', array('L'=>Yii::t('main-ui','Landscape'),'P'=>Yii::t('main-ui','Portrait')),array('class'=>'span12','maxlength'=>100)); ?>
    <?php echo $form->dropdownListRow($model,'page_format', array(
        'A0' => 'A0 (841x1189 mm)',
        'A1' => 'A1 (594x841 mm)',
        'A2' => 'A2 (420x594 mm)',
        'A3' => 'A3 (297x420 mm)',
        'A4' => 'A4 (210x297 mm)',
        'A5' => 'A5 (148x210 mm)',
        'A6' => 'A6 (105x148 mm)',
        'A7' => 'A7 (74x105 mm)',
        'A8' => 'A8 (52x74 mm)',
        'A9' => 'A9 (37x52 mm)',
        'A10' => 'A10 (26x37 mm)',
        'A11' => 'A11 (18x26 mm)',
        'A12' => 'A12 (13x18 mm)',
        'B0' => 'B0 (1000x1414 mm)',
        'B1' => 'B1 (707x1000 mm)',
        'B2' => 'B2 (500x707 mm)',
        'B3' => 'B3 (353x500 mm)',
        'B4' => 'B4 (250x353 mm)',
        'B5' => 'B5 (176x250 mm)',
        'B6' => 'B6 (125x176 mm)',
        'B7' =>'B7 (88x125 mm)',
        'B8' => 'B8 (62x88 mm)',
        'B9' => 'B9 (44x62 mm)',
        'B10' => 'B10 (31x44 mm)',
        'B11' => 'B11 (22x31 mm)',
        'B12' => 'B12 (15x22 mm)',
        'C0' => 'C0 (917x1297 mm)',
        'C1' => 'C1 (648x917 mm)',
        'C2' => 'C2 (458x648 mm)',
        'C3' => 'C3 (324x458 mm)',
        'C4' => 'C4 (229x324 mm)',
        'C5' => 'C5 (162x229 mm)',
        'C6' => 'C6 (114x162 mm)',
        'C7' => 'C7 (81x114 mm)',
        'C8' => 'C8 (57x81 mm)',
        'C9' => 'C9 (40x57 mm)',
        'C10' => 'C10 (28x40 mm)',
        'C11' => 'C11 (20x28 mm)',
        'C12' => 'C12 (14x20 mm)',
        'C76' => 'C76 (81x162 mm)',
        'DL' => 'DL (110x220 mm)',
        'E0' => 'E0 (879x1241 mm)',
        'E1' => 'E1 (620x879 mm)',
        'E2' => 'E2 (440x620 mm)',
        'E3' => 'E3 (310x440 mm)',
        'E4' => 'E4 (220x310 mm)',
        'E5' => 'E5 (155x220 mm)',
        'E6' => 'E6 (110x155 mm)',
        'E7' => 'E7 (78x110 mm)',
        'E8' => 'E8 (55x78 mm)',
        'E9' => 'E9 (39x55 mm)',
        'E10' => 'E10 (27x39 mm)',
        'E11' => 'E11 (19x27 mm)',
        'E12' => 'E12 (13x19 mm)',
        'G0' => 'G0 (958x1354 mm)',
        'G1' => 'G1 (677x958 mm)',
        'G2' => 'G2 (479x677 mm)',
        'G3' => 'G3 (338x479 mm)',
        'G4' => 'G4 (239x338 mm)',
        'G5' => 'G5 (169x239 mm)',
        'G6' => 'G6 (119x169 mm)',
        'G7' => 'G7 (84x119 mm)',
        'G8' => 'G8 (59x84 mm)',
        'G9' => 'G9 (42x59 mm)',
        'G10' => 'G10 (29x42 mm)',
        'G11' => 'G11 (21x29 mm)',
        'G12' => 'G12 (14x21 mm)',
    ),array('class'=>'span12','maxlength'=>50)); ?>
    <?php echo $form->textFieldRow($model,'page_width',array('class'=>'span2','maxlength'=>50)); ?>
    <?php echo $form->textFieldRow($model,'page_height',array('class'=>'span2','maxlength'=>100)); ?>
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span12','maxlength'=>100)); ?>
    <div id="form"></div>
    <?php
    echo $form->textAreaRow($model, 'content', array('id' => 'content', 'rows' => 5));
    ?>
    <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     $(function () {
                            $('#content').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen', 'video'],
                            });
                        });
                    ");
    ?>
</div>
</div>
<div class="box-footer">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'      =>'primary',
			'label'     =>$model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
		)); ?>
</div>

<?php $this->endWidget(); ?>
</div>
<script>
$(document).ready(function () {
  var id = $("#UnitTemplates_type").val();
  var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
  $.ajax({
    type: "POST",
    url: "/unittemplates/SelectType",
    data: {"id": id, "YII_CSRF_TOKEN": csrf},
    dataType: "text",
    cache: false,
    update: "#form",
    error: function (e) {
      console.log(e);
    },
    success: function (data) {
        $("#form").html(data);
    }
  });
});
</script>