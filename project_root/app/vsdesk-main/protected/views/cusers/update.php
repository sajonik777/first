<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Users') => array('index'),
    Yii::t('main-ui', 'Edit'),
);
if (!Yii::app()->user->checkaccess('systemUser')) {
    $this->menu = array(
        Yii::app()->user->checkAccess('listUser') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List users'))) : array(NULL),
    );
}
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Edit') . ' ' . $model->Username; ?></h3>
</div>

<?php
if (Yii::app()->user->checkaccess('systemUser')) {
    echo $this->renderPartial('_upformuser', array('model' => $model, 'lang' => $lang));
} else {
    echo $this->renderPartial('_upform', array('model' => $model, 'lang' => $lang));
}
?>	