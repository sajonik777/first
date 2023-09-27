<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'News') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listNews') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'News'))): array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>