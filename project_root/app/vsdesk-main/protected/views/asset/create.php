<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Assets') => array('index'),
    Yii::t('main-ui', 'Create asset'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listAsset') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List assets'))): array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create asset'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>