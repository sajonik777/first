<?php

$view = null;
$delete = null;
if (Yii::app()->user->checkAccess('viewCalls')) {
  $view = '{view}';
}
if (Yii::app()->user->checkAccess('deleteCalls')) {
  $delete = '{delete}';
}
$template = $view . '  ' . $delete;
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
    'gridId' => 'calls-grid', //id of related grid
    'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
    'userSpecific' => true,
    'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
    'buttonCancel' => false,
    'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
    'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
    'model' => $model->search(), //model is used to get attribute labels
    'columns' => array(
      array(
        'name' => 'date',
        'header' => Yii::t('main-ui', 'Date'),
      ),
      array(
        'name' => 'adate',
        'header' => Yii::t('main-ui', 'Трубка поднята'),
      ),
      array(
        'name' => 'edate',
        'header' => Yii::t('main-ui', 'Окончание звонка'),
      ),
      array(
        'name' => 'dialer_name',
        'header' => Yii::t('main-ui', 'Кто звонил'),
      ),
      array(
        'name' => 'dr_number',
        'header' => Yii::t('main-ui', 'Номер звонящего'),
      ),
      array(
        'name' => 'dr_company',
        'header' => Yii::t('main-ui', 'Компания звонящего'),
      ),
      array(
        'name' => 'dialed_name',
        'header' => Yii::t('main-ui', 'Кому звонили'),
      ),
      array(
        'name' => 'dd_number',
        'header' => Yii::t('main-ui', 'Номер получателя'),
      ),
      array(
        'name' => 'status',
        'header' => Yii::t('main-ui', 'Статус'),
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
  array(
    'name' => 'id',
    'header' => Yii::t('main-ui', '#'),
    'headerHtmlOptions' => array('width' => 60),
        //'filter' => '',
  )
  )); ?>
