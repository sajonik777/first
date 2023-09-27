<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Asset types') => array('index'),
    Yii::t('main-ui', 'Create new type'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listAssetType') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List asset types'))): array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create new type'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'model_s' => $model_s)); ?>