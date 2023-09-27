<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Service problems report by month'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => 'problems', 'itemOptions'=>array('title' => Yii::t('main-ui', 'Service problems report'))),
    array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('exportsproblem', 'month' => $month, 'year' => $year), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Export to Excel'))),
);
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Service problems report by month'); ?></h3>
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
        <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'id' => 'psreport-grid',
            'type' => 'striped bordered condensed',
            'fixedHeader' => true,
            'dataProvider' => $model->search(),
            'columns' => array(
                array(
                    'name' => 'servicename',
                    'class' => 'bootstrap.widgets.TbRelationalColumn',
                    'url' => $this->createUrl('pservicerelational', array('year' => $year, 'month' => $month)),
                ),
                'stnew',
                'stworkaround',
                'stsolved',
                array(
                    'name' => 'downtime',
                    'headerHtmlOptions' => array('width' => 90),
                ),
                array(
                    'name' => 'availability',
                    'headerHtmlOptions' => array('width' => 90),
                ),
                array(
                    'name' => 'pavailability',
                    'headerHtmlOptions' => array('width' => 90),
                )
            ),
        )); ?>
    </div>
</div>
