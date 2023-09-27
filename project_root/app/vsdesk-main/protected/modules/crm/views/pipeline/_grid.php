<?php

$view = NULL;
$update = NULL;
$delete = NULL;
//if (Yii::app()->user->checkAccess('viewPipeline')) {
//$view = '{view}';
//}
//if (Yii::app()->user->checkAccess('updatePipeline')) {
$update = '{update}';
//}
//if (Yii::app()->user->checkAccess('deletePipeline')) {
$delete = '{delete}';
//}
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
        'gridId' => 'pipeline-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            array(
                'name' => 'label',
                'type' => 'raw',
                'header' => Yii::t('main-ui', 'Name'),
            ),
            array(
                'name' => 'send_email',
                'header' => Yii::t('main-ui', 'Отправить Email?'),
            ),
            array(
                'name' => 'send_sms',
                'header' => Yii::t('main-ui', 'Отправить SMS?'),
            ),
            array(
                'name' => 'create_task',
                'header' => Yii::t('main-ui', 'Создать задачу?'),
            ),
            array(
                'name' => 'close_deal',
                'header' => Yii::t('main-ui', 'Завершить сделку успешно'),
            ),
            array(
                'name' => 'cancel_deal',
                'header' => Yii::t('main-ui', 'Завершить сделку неуспешно'),
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            )
        )
    ),
));
 ?>