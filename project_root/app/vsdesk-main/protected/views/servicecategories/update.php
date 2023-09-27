<?php

/* @var $this ServiceCategoriesController */
/* @var $model ServiceCategories */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Service categories catalog') => ['index'],
    $model->name => ['view', 'id' => $model->id],
    Yii::t('main-ui', 'Edit'),
];

$this->menu = [
    Yii::app()->user->checkAccess('listServiceCategory') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'List service categories')]
    ] : [null],
];
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Edit') . ' ' . $model->name; ?></h3>
</div>

<?php $this->renderPartial('_form', ['model' => $model]); ?>
