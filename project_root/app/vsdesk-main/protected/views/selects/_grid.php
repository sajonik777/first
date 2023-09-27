<?php

$view = null;
$update = null;
$delete = null;
if (Yii::app()->user->checkAccess('viewSelects')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateSelects')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteSelects')) {
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
        'gridId' => 'selects-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui',
            'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            'select_name',
            'select_value',
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