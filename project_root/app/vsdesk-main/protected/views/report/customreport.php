<?php

$total = '';
$this->breadcrumbs = [
    Yii::t('main-ui', 'Reports') => ['index'],
    Yii::t('main-ui', 'Custom report'),
];
?>
    <div class="page-header">
        <h3><i class="fa-solid fa-ticket fa-xl"> </i><?php echo Yii::t('main-ui', 'Custom report'); ?></h3>
    </div>
    <div class="box">
      <div class="box-header">
        <?php
        $this->menu = [
            ['icon' => 'fa-solid fa-upload fa-xl', 'url' => 'exportcustom', 'itemOptions'=> ['title' => Yii::t('main-ui', 'Export to Excel')]],
            ['icon' => 'fa-solid fa-gear fa-xl', 'url' => ['javascript:void(0)'], 'itemOptions'=> ['title' => Yii::t('main-ui', 'Columns settings'), 'id'=>"request-grid-full-report-ecolumns-dlg-link"]],
            ['icon' => 'fa-solid fa-chevron-left fa-xl', 'url' => 'javascript:void(0)', 'itemOptions'=> ['title' => Yii::t('main-ui', 'Slide left'), 'class'=> 'carousel-button-left']],
            ['icon' => 'fa-solid fa-chevron-right fa-xl', 'url' => 'javascript:void(0)', 'itemOptions'=> ['title' => Yii::t('main-ui', 'Slide right'), 'class'=> 'carousel-button-right']],
        ];
        ?>
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
      </div>
        <div class="box-body table-responsive">
            <?php $this->widget('bootstrap.widgets.TbAlert', [
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            ]); ?>

            <?php require_once '_gridcustomreport.php'; ?>
            <?php $this->widget('FilterGridResizable', [
                'type' => 'striped bordered condensed',
                'id' => 'request-grid-full-report',
                'redirectRoute' => CHtml::normalizeUrl(''),
                'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['customPageCount'] ? Yii::app()->session['customPageCount'] : 30, Yii::app()->params['selectPageCount'], ['onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;"]) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
                'dataProvider' => $model->searchcustom(),
                'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/request') . '/"+$.fn.yiiGridView.getSelection(id);}',
                'filter' => $model,
                //'fixedHeader' => true,
                'afterAjaxUpdate' => 'reinstallDatePicker',
                'htmlOptions' => ['style' => 'cursor: pointer'],
                'columns' => array_merge($fixed_columns, $dialog->columns()),
                'pager' => [
                    'class' => 'CustomPager',
                    'displayFirstAndLast' => true,
                ],
                'template' => "{summary}\n{items}",
            ]);
            Yii::app()->session['customReportColumns'] = array_merge($fixed_columns, $dialog->columns());
            ?>
        </div>
    </div>

<?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
       /*$('#newDatepicker').daterangepicker({*/
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

       /*$('#fnewDatepicker').datepicker();*/
       /*$('#fsnewDatepicker').datepicker();*/
       /*$('#fenewDatepicker').datepicker();*/
       /*$('#snewDatepicker').datepicker();*/

       if($('#fullname2')) $('#fullname2').select2();
       if($('#mfullname2')) $('#mfullname2').select2();
       if($('#slabel2')) $('#slabel2').select2();
       if($('#delay')) $('#delay').select2();
       if($('#Priority2')) $('#Priority2').select2();
       if($('#ZayavCategory_id2')) $('#ZayavCategory_id2').select2();
       if($('#KE_type2')) $('#KE_type2').select2();
       if($('#service2')) $('#service2').select2();
       if($('#creator2')) $('#creator2').select2();
       if($('#depart2')) $('#depart2').select2();
       if($('#cunits2')) $('#cunits2').select2();
       if($('#groups_id2')) $('#groups_id2').select2();
       if($('#company2')) $('#company2').select2();
       if($('.rating-block input').length != 0) $('.rating-block input').rating({'readOnly':true});
   }
   ");
?>
<script>
    $(function(){
        $('.carousel-button-left').click(function() {
          var leftPos = $('.box-body').scrollLeft();
          var opt = $(document).width();
          $(".box-body").animate({scrollLeft: leftPos - opt}, 500);
        });
        $('.carousel-button-right').click(function() {
          var opt = $(document).width();
          var leftPos = $('.box-body').scrollLeft();
          $(".box-body").animate({scrollLeft: leftPos + opt}, 500);
        });
    });
</script>
