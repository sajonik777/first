<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Summary by Units'),
);
$this->menu = array(
    array('label' => Yii::t('main-ui', 'Export to Excel'), 'icon' => 'fa-solid fa-upload', 'url' => array('exportsunits')),
);
?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Summary by Units'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <ul id="yw0" class="nav nav-pills">
            <li><a title="<?php echo Yii::t('main-ui', 'Export to Excel'); ?>" href="exportsunits"><i
                        class="fa-solid fa-upload fa-xl"></i>
                </a></li>
        </ul>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbGroupGridView', array(
            'id' => 'zreport-grid',
            'type' => 'striped bordered condensed',
            'mergeColumns' => array('dept'),
            'dataProvider' => $model->search(),
            'columns' => array(
                'dept:text:'.Yii::t('main-ui', 'Location'),
                'type',
                'count',
                // 'summary',
            ),
        )); ?>
    </div>
</div>