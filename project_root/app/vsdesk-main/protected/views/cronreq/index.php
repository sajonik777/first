<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Cron Requests') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listCronRequest') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'New cron request'))) : array(NULL),
    array('icon' => 'fa-solid fa-gear fa-xl', 'url' => array('javascript:void(0)'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Columns settings'), 'id'=>"cron-req-grid-ecolumns-dlg-link")),
);

?>
    <div class="page-header">
        <h3><i class="fa-regular fa-calendar-days fa-xl"></i><?php echo Yii::t('main-ui', 'Cron Requests'); ?></h3>
    </div>
    <div class="row-fluid">
        <div class="box">
            <div class="box-body no-padding">
                <div id="calendar"></div>
            </div>
        </div>
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
        <?php require_once '_grid.php'; ?><?php $this->widget('FilterGridResizable', array(
            'type' => 'striped bordered condensed',
            'id' => 'cron-req-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['CronReqPageCount'] ? Yii::app()->session['CronReqPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/cronreq/update') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array_merge($fixed_columns, $dialog->columns()),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'template' => "{summary}\n{items}\n{pager}",
        )); ?>
    </div>
</div>
    <!-- fullCalendar 2.2.5 -->
<?php
Yii::app()->clientScript->registerCssFile('/css/fullcalendar.css');
Yii::app()->clientScript->registerScriptFile('/js/moment.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('/js/fullcalendar.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('fcalendar', "
$(function () {
var repeatingEvents = $json;

//emulate server
var getEvents = function( start, end ){
    return repeatingEvents;
}
    $('#calendar').fullCalendar({
        height: 600,
        minTime: 0,
        maxTime: 24,
        firstDay: 1,
        header: {
            left: 'prev,next today',
            center: 'title',
        },
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв.','Фев.','Март','Апр.','Май','οюнь','οюль','Авг.','Сент.','Окт.','Ноя.','Дек.'],
        dayNames: ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
        dayNamesShort: ['ВС','ПН','ВТ','СР','ЧТ','ПТ','СБ'],
        buttonText: {
            today: 'Сегодня',
            month: 'Месяц',
            week: 'Неделя',
            day: 'День'
        },
        editable: true,
        eventClick: function(calEvent, jsEvent, view) {
            location.href = '/cronreq/update/' + calEvent.id;
        },
    eventRender: function(event, element, view){
        return (event.ranges.filter(function(range){
            return (event.start.isBefore(range.end) && event.end.isAfter(range.start));
        }).length)>0;
    },
    events: function( start, end, timezone, callback ){
        var events = getEvents(start,end); //this should be a JSON request

        callback(events);
    },
                timeFormat: 'H:mm',
        axisFormat: 'H:mm',
      });
}); ", CClientScript::POS_END);
?>
