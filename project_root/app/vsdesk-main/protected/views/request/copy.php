<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Tickets') => array('index'),
    Yii::t('main-ui', 'Create ticket'),
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'List tickets'), 'icon' => 'list', 'url' => array('index')),
);
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Create ticket'); ?></h3>
</div>

<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>
<?php
if (!Yii::app()->user->checkAccess('systemUser')) {
    $this->renderPartial('_adminform', array('model' => $model, 'fields' => $fields, 'copy' => true));
} else {
    if (Yii::app()->user->checkAccess('liteformRequest')) {
        $this->renderPartial('_liteform', array('model' => $model));
    } else {
        $this->renderPartial('_form', array('model' => $model, 'fields' => $fields, 'copy' => true));

    }

}
?>
