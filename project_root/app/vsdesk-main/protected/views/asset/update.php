<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Assets') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit asset'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listAsset') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List assets'))): array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>

<?php echo $this->renderPartial('_form2', array('model' => $model, 'item' => $data, 'model_s' => $model_s)); ?>