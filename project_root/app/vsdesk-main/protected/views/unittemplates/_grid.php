<?php

$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewUnitTemplates')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateUnitTemplates')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteUnitTemplates')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
?>

<?php $dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'unit-templates-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            array(
                'name' => 'name',
                'header' => Yii::t('main-ui', 'Name'),
            ),
            array(
                'name' => 'type_name',
                'header' => Yii::t('main-ui', 'Type'),
                'filter' => array(Yii::t('main-ui','Unit')=>Yii::t('main-ui','Unit'),Yii::t('main-ui','Asset')=>Yii::t('main-ui','Asset'), Yii::t('main-ui','Request')=>Yii::t('main-ui','Request'), Yii::t('main-ui','Contract')=>Yii::t('main-ui','Contract')),
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            )
        )
    ),
));
$fixed_columns = array_filter(array(
    )); ?>