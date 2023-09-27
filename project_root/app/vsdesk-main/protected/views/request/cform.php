<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Tickets') => array('index'),
    Yii::t('main-ui', 'Add comment'),
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'List tickets'), 'icon' => 'list', 'url' => array('index')),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Add comment'); ?></h3>
    </div>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>
<?php
    $this->renderPartial('_cform', array('model' => $model));
?>