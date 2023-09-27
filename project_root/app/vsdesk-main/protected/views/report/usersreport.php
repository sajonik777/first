<?php

$sdate = $_POST['Report']['sdate'];
$edate = $_POST['Report']['edate'];
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('users'),
    Yii::t('main-ui', 'Users report'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('users'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Users report'))),
    array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('exportusers', 'sdate' => $sdate, 'edate' => $edate, 'company' => isset($_POST['Report']['company']) ? $_POST['Report']['company'] : null), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Export to Excel'))),
);
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Users report'); ?>:
    <?= $_POST['Report']['company'] ? $_POST['Report']['company'] : 'Все компании' ; ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <h4><?php echo Yii::t('main-ui', 'Report created between '); ?><?php echo $_POST['Report']['sdate']; ?> <?php echo Yii::t('main-ui', 'and '); ?> <?php echo $_POST['Report']['edate']; ?></h4>
        <?php
        $config = array('pagination' => false);
        $rawData = $model;
        $dataProvider = new CArrayDataProvider($rawData, $config);
        ?>
        <?php if (isset($model)): ?>
            <div id="grid">
                <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                    'id' => 'zreport-grid',
                    'type' => 'striped bordered condensed',
                    'template' => "{items}\n{extendedSummary}",
                    'fixedHeader' => true,
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                )); ?>
            </div>
        <?php endif; ?>
    </div>
</div>