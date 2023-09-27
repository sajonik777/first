<?php

/** @var $this RequestprocessingrulesController */
/** @var $model RequestProcessingRules */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Request processing rules') => ['index'],
    Yii::t('main-ui', 'Create'),
];

$this->menu = [
    Yii::app()->user->checkAccess('listRequestProcessingRules') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Request processing rules')]
    ] : [null],
];
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Create request processing rules'); ?></h3>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
