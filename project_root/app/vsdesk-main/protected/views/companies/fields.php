<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Companies') => array('index'),
    Yii::t('main-ui', 'Company fields')=> array('index'),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listCompany') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Companies'))) : array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Company fields'); ?></h3>
    </div>

<?php echo $this->renderPartial('_upformfield', array('model2' => $model, 'fields' => $fields)); ?>