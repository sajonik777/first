<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Service problems report'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('problems2'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Service problems report'))),
    array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('exportsproblem2', 'sdate' => $sdate, 'edate' => $edate, 'company' => isset($_POST['company']) ? $_POST['company'] : null), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Export to Excel'))),
);
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Service problems report'); ?>:
        <?= $_POST['Psreport']['company'] ? $_POST['Psreport']['company'] : 'Все компании' ; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'id' => 'psreport-grid',
            'type' => 'striped bordered condensed',
            'fixedHeader' => true,
            'dataProvider' => $model->search(),
            'columns' => array(
                array(
                    'name' => 'servicename',
                    'class' => 'bootstrap.widgets.TbRelationalColumn',
                    'url' => $this->createUrl('pservicerelational2', array('sdate' => $sdate, 'edate' => $edate)),
                ),
                'stnew',
                'stworkaround',
                'stsolved',
            ),
        )); ?>
    </div>
</div>
