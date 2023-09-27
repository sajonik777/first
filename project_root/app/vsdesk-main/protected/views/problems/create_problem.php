<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Problems') => array('index'),
    Yii::t('main-ui', 'Create problem'),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create problem'); ?></h3>
    </div>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>
<?php echo $this->renderPartial('_hform', array('model' => $model)); ?>