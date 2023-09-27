<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Этапы сделки') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

$this->menu = array(
    array('icon' => 'fa-solid fa-chart-bar fa-xl', 'url' => array('/crm'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Leads'))),
    array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Create'))),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-list-ul fa-xl"> </i><?php echo Yii::t('main-ui', 'Этапы сделки'); ?></h3>

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
        <?php require_once '_grid.php'; ?><?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'id' => 'pipeline-grid',
            'dataProvider' => $model->search(),
            //'filter'=>$model,
            'summaryText' => '
        <div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['PipelinePageCount'] ? Yii::app()->session['PipelinePageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div>' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/crm/pipeline/update/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => $dialog->columns(),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'template' => $dialog->link($text = '<i class="icon-cog"> ' . Yii::t('main-ui', 'Columns settings') . '</i>') .
                "{summary}\n{items}\n{pager}",
        )); ?>
    </div>
</div>
