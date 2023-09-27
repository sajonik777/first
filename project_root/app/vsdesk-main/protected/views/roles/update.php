<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Roles management') => array('index'),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Roles'))),
);
?>

    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>

<?php echo $this->renderPartial('_upform', array('model' => $model, 'filtersForm' => $filtersForm, 'filters' => $filters, 'dataProvider' => $dataProvider)); ?>