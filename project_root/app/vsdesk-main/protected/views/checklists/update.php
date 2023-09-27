<?php
/* @var $this ChecklistsController */
/* @var $model Checklists */
/* @var $modelChecklistFields ChecklistFields */
/* @var $fields ChecklistFields[] */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Checklists') => ['index'],
    $model->name => ['index'],
    Yii::t('main-ui', 'Edit'),
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
        <h3><?php echo $model->name; ?></h3>
    </div>

<?php
echo $this->renderPartial('_upform', [
        'model' => $model,
        'modelChecklistFields' => $modelChecklistFields,
        'fields' => $fields,
]);
?>
