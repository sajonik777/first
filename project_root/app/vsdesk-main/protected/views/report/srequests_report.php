<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Summary by requests'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
<?php
$this->widget(
    'bootstrap.widgets.TbHighCharts',
    array(
        'options' => array(
            'credits' => array('enabled' => false),
            'chart' => array(
                'type' => 'bar',
                'backgroundColor' => '#efefef',
            ),
            'title' => array(
                'text' => Yii::t('main-ui', 'Summary by requests'),
                'x' => -20 //center
            ),
            /*'subtitle' => array(
                'text' => Yii::t('main-ui', 'per service'),
                'x' - 20
            ),*/
            'xAxis' => array(
                //'categories' => ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                'categories' => $categories,
                'title' => [
                    'text' => null,
                ],
            ),
            'yAxis' => array(
                'min' => 0,
                'title' => [
                    'text' => null,
                ],
                'labels' => [
                    'overflow' => 'justify'
                ]
            ),
            'tooltip' => array(
                'valueSuffix' => ' шт.'
            ),
            'plotOptions' => [
                'bar' => [
                    'dataLabels' => [
                        'enabled' => true
                    ]
                ]
            ],
            'legend' => array(
                'enabled' => 'false',
            ),
            'series' => [
                [
                    'name' => 'Заявки',
                    'data' => $model
                ],
            ]
        ),
        'htmlOptions' => array(
            'style' => 'min-width: 310px; height: 400px; margin: 0 auto'
        )
    )
);

/* $this->widget(
    'bootstrap.widgets.TbHighCharts',
    array(
        'options' => array(
            'credits' => array('enabled' => false),
            'chart' => array(
                'type' => 'column',
                'backgroundColor' => '#efefef',
            ),
            'title' => array(
                'text' => Yii::t('main-ui', 'Summary by requests'),
                'x' => -20 //center
            ),*/
/*'subtitle' => array(
    'text' => Yii::t('main-ui', 'per service'),
    'x' - 20
),*/
/*'xAxis' => array(
    'categories' => $categories,
    'labels' => array(
        'rotation' => 0,
        'align' => 'center',
        'style' => array('fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif'),
    ),
),
'yAxis' => array(
    'min' => 0,
    'allowDecimals' => false,
    'gridLineDashStyle' => 'ShortDash',
    'title' => array(
        'text' => Yii::t('main-ui', 'Requests'),
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
    'enabled' => 'false',
),
'series' => array(
    array(
        'name' => Yii::t('main-ui', 'Company'),
        'data' => $model,
        'color' => '#58595b',
        'dataLabels' => array(
            'enabled' => 'true',
            'color' => '#FFFFFF',
            'align' => 'center',
            'x' => 4,
            'y' => 10,
            'style' => array('fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif'),
        )
    ),

)
),
'htmlOptions' => array(
'style' => 'min-width: 310px; height: 400px; margin: 0 auto'
)
)
); */
?>
    </div>
</div>