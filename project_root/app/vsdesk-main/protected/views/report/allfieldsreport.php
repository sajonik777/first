<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reports') => array('index'),
    Yii::t('main-ui', 'Tickets with fields by service'),
);
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Tickets with fields by service'); ?></h3>
</div>
<div class="box">
  <div class="box-header">
    <?php
    $this->menu = array(
        array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('exportallfields', 'service' => $service_id, 'sdate' => $sdate, 'edate' => $edate), 'itemOptions' => array('title' => Yii::t('main-ui', 'Export to Excel'))),
        array('icon' => 'fa-solid fa-gear fa-xl', 'url' => array('javascript:void(0)'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Columns settings'), 'id'=>"full-fields-report-ecolumns-dlg-link")),
        array('icon' => 'fa-solid fa-chevron-left fa-xl', 'url' => 'javascript:void(0)', 'itemOptions'=>array('title' => Yii::t('main-ui', 'Slide left'), 'class'=> 'carousel-button-left')),
        array('icon' => 'fa-solid fa-chevron-right fa-xl', 'url' => 'javascript:void(0)', 'itemOptions'=>array('title' => Yii::t('main-ui', 'Slide right'), 'class'=> 'carousel-button-right')),
    );
    ?>
    <?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'pills',
        'items' => $this->menu,
    )); ?>
  </div>
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>

        <?php require_once '_gridallfieldsreport.php'; ?>
        <?php $this->widget('FilterGridResizable', array(
            'type' => 'striped bordered condensed',
            'fixedHeader' => true,
            'id' => 'full-fields-report',
            'enableSorting' => false,
            'redirectRoute' => CHtml::normalizeUrl(''),
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['requestPageCount'] ? Yii::app()->session['requestPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'dataProvider' => $gridDataProvider,
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array_merge($fixed_columns, $dialog->columns()),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'template' => "{summary}\n{items}\n{pager}",
        ));
        Yii::app()->session['allFieldsReportColumns'] = array_merge($fixed_columns, $dialog->columns());
        ?>
    </div>
</div>


<?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
	    window.location='allfields';
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
