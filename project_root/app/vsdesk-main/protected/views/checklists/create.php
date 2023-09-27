<?php
/* @var $this ChecklistsController */

/* @var $model Checklists */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Checklists') => ['index'],
    Yii::t('main-ui', 'Create'),
];

$this->menu = [
    Yii::app()->user->checkAccess('listChecklists') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Checklists')]
    ] : [null],
];
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create Checklist'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
