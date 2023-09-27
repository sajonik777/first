<?php

/* @var $this ServiceCategoriesController */
/* @var $model ServiceCategories */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Service categories catalog') => ['index'],
    Yii::t('main-ui', 'Create service category'),
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
    <h3><?php echo Yii::t('main-ui', 'Create service category'); ?></h3>
</div>

<?php $this->renderPartial('_form', ['model' => $model]); ?>
