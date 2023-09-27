<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Service request report by month'),
);
$this->menu = array(
    array('label' => Yii::t('main-ui', 'Export to Excel'), 'icon' => 'fa-solid fa-upload', 'url' => array('exportrequestproblem', 'month' => $month, 'year' => $year)),
    array('label' => Yii::t('main-ui', 'Service request report'), 'icon' => 'list', 'url' => array('requests')),
);
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Service request by month'); ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>
<?php $this->widget('bootstrap.widgets.TbGroupGridView', array(
    'id' => 'rsreport-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => new CArrayDataProvider($model),
    'mergeColumns' => array('parent_service', 'parent_availability', 'parent_availability', 'parent_pavailability'),
    'columns' => array(
        //'parent_service_id',
        //'parent_service',
        array(
            'header' => Yii::t('main-ui', 'Parent service'),
            'name' => 'parent_service',
        ),
        array(
            'header' => Yii::t('main-ui', 'Parent service'),
            'name' => 'parent_availability',
        ),
        array(
            'header' => Yii::t('main-ui', 'Availability % (SLA)'),
            'name' => 'parent_pavailability',
        ),
        //'pservice_rl.name',
        array(
            'header' => Yii::t('main-ui', 'Service'),
            'name' => 'servicename',
            //'class' => 'bootstrap.widgets.TbRelationalColumn',
            //'url' => $this->createUrl('rservicerelational', array('year' => $year, 'month' => $month)),
        ),
        array(
            'header' => Yii::t('main-ui', 'Requests'),
            'name' => 'stnew',
        ),
        //'stworkaround',
        //'stsolved',
        /*array(
            'header' => Yii::t('main-ui', 'Downtime (hh:mm)'),
            'name' => 'downtime',
            'headerHtmlOptions' => array('width' => 90),
        ),*/
        array(
            'header' => Yii::t('main-ui', 'Availability %'),
            'name' => 'availability',
            'headerHtmlOptions' => array('width' => 90),
        ),
        array(
            'header' => Yii::t('main-ui', 'Availability % (SLA)'),
            'name' => 'pavailability',
            'headerHtmlOptions' => array('width' => 90),
        )
    ),
)); ?>
