<div class="no_print">
    <?php
    $this->breadcrumbs = array(
        Yii::t('main-ui', 'Reports'),
        Yii::t('main-ui', 'Service report'),
    );
    $this->menu = array(
        array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('servicenew'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Service report'))),
        array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('exportservice', 'sdate' => $sdate, 'edate' => $edate, 'company' => isset($_POST['Report']['company']) ? $_POST['Report']['company'] : null), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Export to Excel'))),
    );
    ?>
</div>
<div class="page-header">
    <h3><?= $_POST['Report']['company'] ? $_POST['Report']['company'].': ' : 'Все компании: '; ?>
        <?php echo Yii::t('main-ui', 'Service report'); ?> за период с <?php echo date('d.m.Y', $sdate); ?>
        по <?php echo date('d.m.Y', $edate); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <div class="no_print">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => $this->menu,
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>
        </div>
        <?php
        $config = array('pagination' => false);
        $rawData = $model;
        $dataProvider = new CArrayDataProvider($rawData, $config);
        ?>

        <?php if (isset($model)): ?>
            <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'id' => 'zreport-grid',
                'type' => 'striped bordered condensed',
                'template' => "{items}\n{extendedSummary}",
                'fixedHeader' => true,
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            )); ?>
        <?php endif; ?>
    </div>
</div>
