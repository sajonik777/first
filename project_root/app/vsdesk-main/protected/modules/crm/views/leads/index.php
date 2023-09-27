<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Сделки') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

$this->menu = array(
    array('icon' => 'fa-solid fa-chart-bar fa-xl', 'url' => array('/crm'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Leads'))),
    array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Create'))),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-list-ul fa-xl"> </i><?php echo Yii::t('main-ui', 'Сделки'); ?></h3>

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
            'id' => 'leads-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'summaryText' => '
        <div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['LeadsPageCount'] ? Yii::app()->session['LeadsPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div>' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/crm/leads/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array_merge($fixed_columns, $dialog->columns()),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'template' => $dialog->link($text = '<i class="icon-cog"> ' . Yii::t('main-ui', 'Columns settings') . '</i>') .
                "{summary}\n{items}\n{pager}",
        )); ?>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
       $('.betweenDatepicker').daterangepicker({
       'format':'DD.MM.YYYY',
       'language':'ru',
       'locale':{
            'fromLabel':'От',
            'toLabel':'До',
            'weekLabel':'Н',
            'customRangeLabel':'Задать даты',
            'firstDay':1,
            'daysOfWeek':['В','П','В','С','Ч','П','С'],
            'monthNames':['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            'applyLabel':'Применить',
            'cancelLabel':'Отмена',
       },
       'ranges':{
            'Сегодня':[moment(), moment()],
            'Вчера':[moment().subtract('days', 1), moment().subtract('days', 1)],
                            'Последние 7 дней':[moment().subtract('days', 6), moment()],
                            'Последние 30 дней':[moment().subtract('days', 29), moment()],
                            'В этом месяце':[moment().startOf('month'), moment().endOf('month')],
                            'В прошлом месяце':[moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
       },
       }, function(){
            $(this.element).change();
        });

        if($('#Leads_status')) $('#Leads_status').select2({'width':'resolve'});
   }
   ");
?>
