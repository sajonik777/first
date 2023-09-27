<?php

/* @var $this StatusController */
/* @var $model Status */
/* @var $messages Messages[] */
/* @var $smss Smss[] */
?>

<?php
$this->breadcrumbs = [
    Yii::t('main-ui', 'Statuses') => ['index'],
    $model->name => ['index'],
    Yii::t('main-ui', 'Edit')
];

$this->menu = array(
    Yii::app()->user->checkAccess('listStatus') ? ['icon' => 'fa-solid fa-list-ul fa-xl', 'url' => ['index'], 'itemOptions'=> ['title' => Yii::t('main-ui', 'Status')]] : array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>
<?php echo $this->renderPartial('_form', ['model' => $model, 'messages' => $messages, 'smss' => $smss]); ?>