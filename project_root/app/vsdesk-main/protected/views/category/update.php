<?php

$this->breadcrumbs = array(
    'Категории заявок' => array('index'),
    $model->name => array('index'),
    Yii::t('main-ui', 'Edit category'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listCategory') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List records'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Edit category') . ' "' . $model->name; ?>"</h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>