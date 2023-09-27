<?php

/* @var $this SiteController */
if (Yii::app()->user->checkAccess('systemUser')) {
    $text = Yii::t('main-ui', 'for the current user');
} else if (Yii::app()->user->checkAccess('systemManager')) {
    $text = Yii::t('main-ui', 'assigned to manager');
} else {
    $text = Yii::t('main-ui', 'per manager');
}
?>
<?php
if (Yii::app()->user->checkAccess('showTicketCalendar')) {
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
        height: 'auto',
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
            location.href = '/request/' + calEvent.id;
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
}
?>
<div class="row-fluid">
    <?php
    if (Yii::app()->user->checkAccess('showSearchKB')): ?>
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Поиск по базе знаний</h3>
			</div>
			<div class="box-body">
                <?php
                $form = $this->beginWidget(
                    'bootstrap.widgets.TbActiveForm',
                    array(
                        'id' => 'search_form',
                        'type' => 'inline',
                        'action' => '/site/freesearch',
                    )
                );
                echo CHtml::textField('search_field', NULL, array('id' => 'idTextField',
                    'class' => 'span12',
                    'placeholder' => Yii::t('main-ui', 'Enter the text to search for Knowledge Base and press ENTER')));

                $this->endWidget();
                unset($form); ?>
			</div>
		</div>
    <?php
    endif; ?>
</div>
<?php
if (Yii::app()->user->checkAccess('showTicketCalendar')): ?>
	<div class="row-fluid">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?php
                    echo Yii::t('main-ui', 'Tickets by deadline') ?></h3>
			</div>
			<div class="box-body no-padding">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
<?php
endif; ?>
<div class="row-fluid">
    <?php
    if (Yii::app()->user->checkAccess('showProblemGraph')): ?>
    <?php
    if (Yii::app()->user->checkAccess('showProblemGraph') and Yii::app()->user->checkAccess('showTicketGraph')): ?>
	<div class="span6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?php
                    echo Yii::t('main-ui', 'Dynamics of registration of applications by week'); ?></h3>
			</div>
            <?php
            else: ?>
			<div class="span12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"><?php
                            echo Yii::t('main-ui', 'Dynamics of registration of applications by week'); ?></h3>
					</div>
                    <?php
                    endif; ?>

                    <?php

                    $graph_result = array();
                    $graph_data = Request::model()->countPerDayInPeriod(1681074000, 1683666000, 7);
                    foreach ($graph_data as $gd) {
                        array_push($graph_result, array('name' => 'С '.$gd['start_date'].'<br/>По '.$gd['end_date'],'y' => intval($gd['requests_count'])));
                    }
                    $this->widget(
                        'bootstrap.widgets.TbHighCharts',
                        array(
                            'options' => array(
                                'credits' => array('enabled' => false),

                                'chart' => array(
                                    'type' => 'column',
                                    'backgroundColor' => '#fff',
                                ),
                                'title' => array(
                                    'text' => NULL,
                                    'x' => -20 //center
                                ),
                                'subtitle' => array(
                                    'text' => NULL,
                                    'x' => -20
                                ),
                                'xAxis' => array(
                                    'categories' => array(),
                                    'labels' => array(
                                        'rotation' => 0,
                                        'align' => 'center',
                                        'style' => array('fontSize' => '10px', 'fontFamily' => 'Helvetica, sans-serif'),
                                    ),
                                ),
                                'yAxis' => array(
                                    'min' => 0,
                                    'allowDecimals' => false,
                                    'gridLineDashStyle' => 'ShortDash',
                                    'title' => array(
                                        'text' => 'Заявки',
                                    ),
                                    'plotLines' => array(
                                        array(
                                            'value' => 0,
                                            'width' => 1,
                                            'color' => '#808080'
                                        )
                                    ),
                                ),
                                'tooltip' => array(
                                    'valueSuffix' => ' шт.'
                                ),
                                'legend' => array(
                                    'enabled' => false,
                                ),// TODO: Set dynamic series
                                'series' => array(
                                    array(
                                        'name' => 'Заявки',
                                        'data' => $graph_result
                                    )
                                )
                            ),
                            'htmlOptions' => array(
                                'style' => 'min-width: 310px; height: 400px; margin: 0 auto'
                            )
                        )
                    ); ?>
				</div>
			</div>
            <?php
            endif; ?>


            <?php
            if (Yii::app()->user->checkAccess('showTicketGraph')): ?>
            <?php
            if (Yii::app()->user->checkAccess('showProblemGraph') and Yii::app()->user->checkAccess('showTicketGraph')): ?>
			<div class="span6">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"><?php
                            echo Yii::t('main-ui', 'Number of tickets statuses'); ?></h3>
					</div>
                    <?php
                    else: ?>
					<div class="span12">
						<div class="box">
							<div class="box-header with-border">
								<h3 class="box-title"><?php
                                    echo Yii::t('main-ui', 'Number of tickets statuses'); ?></h3>
							</div>
                            <?php
                            endif; ?>
                            <?php
                            $this->renderPartial('_graph', array(
                                    'graph2' => $graph2,
                                    'data5' => $data5,
                                    'name' => $name,
                                )
                            ); ?>
						</div>
					</div>
                    <?php
                    endif; ?>
				</div>
				<div class="row-fluid">
                    <?php
                    if (Yii::app()->user->checkAccess('showlastNews')): ?>
                    <?php
                    if (Yii::app()->user->checkAccess('showlastNews') and Yii::app()->user->checkAccess('showlastKB')): ?>
					<div class="span6">
						<div class="box box-default">
							<div class="box-header with-border">
								<h3 class="box-title"><?php
                                    echo Yii::t('main-ui', 'Latest news and alerts'); ?></h3>
							</div>
                            <?php
                            else: ?>
							<div class="span12">
								<div class="box box-default">
									<div class="box-header with-border">
										<h3 class="box-title"><?php
                                            echo Yii::t('main-ui', 'Latest news and alerts'); ?></h3>
									</div>
                                    <?php
                                    endif; ?>
									<div class="box-body">
                                        <?php
                                        $this->widget('bootstrap.widgets.TbGridView', array(
                                            'type' => 'striped bordered condensed',
                                            'id' => 'news-grid',
                                            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/news/module/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
                                            'dataProvider' => $news->searchmain(),
                                            'htmlOptions' => array('style' => 'cursor: pointer'),
                                            'summaryText' => '',
                                            'columns' => array(
                                                array(
                                                    'name' => 'date',
                                                    'headerHtmlOptions' => array('width' => 100),
                                                    'header' => Yii::t('main-ui', 'Created'),
                                                ),
                                                array(
                                                    'name' => 'author',
                                                    'headerHtmlOptions' => array('width' => 50),
                                                    'header' => Yii::t('main-ui', 'Author'),
                                                ),
                                                array(
                                                    'name' => 'name',
                                                    //'headerHtmlOptions'=> array('width'=>120),
                                                    'header' => Yii::t('main-ui', 'Name'),
                                                ),

                                                array(
                                                    'class' => 'bootstrap.widgets.TbButtonColumn',
                                                    //'headerHtmlOptions'=> array('width'=>50),
                                                    'template' => '{view}',
                                                    'buttons' => array
                                                    (
                                                        'view' => array
                                                        (
                                                            'label' => Yii::t('main-ui', 'View'),
                                                            'url' => 'Yii::app()->createUrl("news/module/view", array("id"=>$data->id))',
                                                        ),
                                                    ),
                                                )
                                            ))); ?>
										<span class="fa-solid fa-angles-right">   </span><a href="/news/"><?php
                                            echo Yii::t('main-ui', 'View all'); ?></a>
									</div>
								</div>
							</div>
                            <?php
                            endif; ?>

                            <?php
                            if (Yii::app()->user->checkAccess('showlastKB')): ?>
                            <?php
                            if (Yii::app()->user->checkAccess('showlastNews') and Yii::app()->user->checkAccess('showlastKB')): ?>
							<div class="span6">
								<div class="box box-default">
									<div class="box-header with-border">
										<h3 class="box-title"><?php
                                            echo Yii::t('main-ui', 'Latest knowledgebase records'); ?></h3>
									</div>
                                    <?php
                                    else: ?>
									<div class="span12">
										<div class="box box-default">
											<div class="box-header with-border">
												<h3 class="box-title"><?php
                                                    echo Yii::t('main-ui', 'Latest knowledgebase records'); ?></h3>
											</div>
                                            <?php
                                            endif; ?>
											<div class="box-body">
                                                <?php
                                                $config = array('keyField' => 'id', 'pagination' => false);
                                                $rawData = $faq;
                                                $dataProvider = new CArrayDataProvider($rawData, $config);
                                                $this->widget('bootstrap.widgets.TbGridView', array(
                                                    'type' => 'striped bordered condensed',
                                                    'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/knowledge/module/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
                                                    'id' => 'faq-grid',
                                                    'dataProvider' => $dataProvider,
                                                    'htmlOptions' => array('style' => 'cursor: pointer'),
                                                    'summaryText' => '',
                                                    'columns' => array(
                                                        array(
                                                            'name' => 'image',
                                                            'headerHtmlOptions' => array('width' => 10),
                                                            'type' => 'html',
                                                            'header' => CHtml::tag('i', array('class' => "icon-paper-clip"), null),
                                                            'filter' => '',
                                                            'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"icon-paper-clip"), null) : ""',
                                                        ),
                                                        array(
                                                            'name' => 'created',
                                                            'headerHtmlOptions' => array('width' => 100),
                                                            'header' => Yii::t('main-ui', 'Created'),
                                                        ),
                                                        array(
                                                            'name' => 'author',
                                                            'headerHtmlOptions' => array('width' => 50),
                                                            'header' => Yii::t('main-ui', 'Author'),
                                                        ),
                                                        array(
                                                            'name' => 'name',
                                                            //'headerHtmlOptions'=> array('width'=>120),
                                                            'header' => Yii::t('main-ui', 'Name'),
                                                        ),

                                                        array(
                                                            'class' => 'bootstrap.widgets.TbButtonColumn',
                                                            //'headerHtmlOptions'=> array('width'=>50),
                                                            'template' => '{view}',
                                                            'buttons' => array
                                                            (
                                                                'view' => array
                                                                (
                                                                    'label' => Yii::t('main-ui', 'View'),
                                                                    'url' => 'Yii::app()->createUrl("knowledge/module/view", array("id"=>$data->id))',
                                                                ),
                                                            ),
                                                        )
                                                    ))); ?>
												<span class="fa-solid fa-angles-right"></span> <a href="/knowledge/"><?php
                                                    echo Yii::t('main-ui', 'View all'); ?></a>
											</div>
										</div>
									</div>
                                    <?php
                                    endif; ?>
								</div>
                                <?php
                                if (Yii::app()->user->checkAccess('listRequest')): ?>
									<div class="row-fluid">
										<div class="span12">
											<div class="box box-default">
												<div class="box-header with-border">
													<h3 class="box-title"><?php
                                                        echo Yii::t('main-ui', 'Last ') . Yii::app()->params['grid_items'] . Yii::t('main-ui', ' tickets'); ?></h3>
													<ul class="nav nav-pills" style="margin-bottom: -10px">
														<li><a href="javascript:void(0);" id="request-grid-ecolumns-dlg-link"><i class="fa-solid fa-gear fa-xl"
																																 title="<?php
                                                                                                                                 echo Yii::t('main-ui', 'Columns settings'); ?>"></i> </a>
														</li>
														<li><a class="carousel-button-left" href="javascript:void(0);"><i class="fa-solid fa-chevron-left fa-xl"
																														  title="<?php
                                                                                                                          echo Yii::t('main-ui', 'Slide left'); ?>"></i>
															</a></li>
														<li><a class="carousel-button-right" href="javascript:void(0);"><i class="fa-solid fa-chevron-right fa-xl"
																														   title="<?php
                                                                                                                           echo Yii::t('main-ui', 'Slide right'); ?>"></i>
															</a></li>
													</ul>
												</div>
												<div class="box-body table-responsive">
                                                    <?php
                                                    require_once '_grid.php';
                                                    $this->widget('bootstrap.widgets.TbGridView', array(
                                                        'type' => 'striped bordered condensed',
                                                        'id' => 'request-grid',
                                                        'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/request') . '/"+$.fn.yiiGridView.getSelection(id);}',
                                                        'dataProvider' => $model->searchmain(),
                                                        //'dataProvider' => $model->search(),
                                                        'htmlOptions' => array('style' => 'cursor: pointer'),
                                                        'summaryText' => '',
                                                        'afterAjaxUpdate' => "function() {
                if($('.rating-block input').length != 0) $('.rating-block input').rating({'readOnly':true});
            }",
                                                        'columns' => array_merge($fixed_columns, $dialog->columns()),
                                                        'template' => "{summary}\n{items}\n{pager}",
                                                    )); ?>
													<span class="fa-solid fa-angles-right">   </span><a href="/request/"> <?php
                                                        echo Yii::t('main-ui', 'View all'); ?></a>
												</div>
											</div>
										</div>
									</div>
                                    <?php
                                    if (Yii::app()->params->update_grid == 1) {
                                        $timeout = (Yii::app()->params->update_grid_timeout) * 1000;
                                        Yii::app()->clientScript->registerScript('autoupdate-activations-application-grid',
                                            "setInterval(function(){;$.fn.yiiGridView.update('request-grid');return false;}," . $timeout . ");");
                                    }
                                    ?>
                                    <?php
                                    if (Yii::app()->params->update_grid == 1 and Yii::app()->user->checkAccess('showTicketCalendar')) {
                                        $timeout = (Yii::app()->params->update_grid_timeout) * 1000;
                                        Yii::app()->clientScript->registerScript('autoupdate-calendar',
                                            "setInterval(function(){
            var events = {
                 url: '/request/getevents',
                 type: 'GET',
             }
        $('#calendar').fullCalendar( 'removeEvents');
        $('#calendar').fullCalendar( 'removeEventSource', events );
        $('#calendar').fullCalendar( 'addEventSource', events );
         return false;}," . $timeout . ");");
                                    }
                                    ?>
                                <?php
                                endif; ?>
                                <?php
                                if (Yii::app()->user->checkAccess('showSearchKB')): ?>
									<script type="text/javascript">
										$(document).ready(function () {
											$('#faq_search_input').keypress(function (e) {
												if (e.keyCode == 13)
													if ($('#search_field').is(':focus')) {
														document.getElementById('search_form').submit();
														return false;
													}
											})
										});
									</script>
                                <?php
                                endif; ?>
								<script>
									$(function () {
										$('.carousel-button-left').click(function () {
											var leftPos = $('.box-body').scrollLeft();
											var opt     = $(document).width();
											$('.box-body').animate({scrollLeft: leftPos - opt}, 500);
										});
										$('.carousel-button-right').click(function () {
											var opt     = $(document).width();
											var leftPos = $('.box-body').scrollLeft();
											$('.box-body').animate({scrollLeft: leftPos + opt}, 500);
										});
									});
								</script>