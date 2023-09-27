<?php
$series = array(
    0 => array(
        'name' => 'Заявки',
        'data' => Status::model()->countRequests(),
        // 'data' => $graph2,
        "dataLabels" => array(
            'enabled' => "true",
            'color' => "#000",
            'align' => "center",
            'x' => 4,
            'y' => 10,
            'style' => array(
                'fontSize' => "10px",
                'fontFamily' => "Helvetica, sans-serif"
            )
        )
    )
);
?>
<?php

echo '<div id="graph">';

$this->widget(
    'bootstrap.widgets.TbHighCharts',
    array(
        'options' => array(
            'credits' => array('enabled' => false),
            'chart' => array(
                'type' => 'pie',
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
                'labels' => array(
                    'rotation' => !Yii::app()->user->checkAccess('systemUser') && !Yii::app()->user->checkAccess('systemManager') ? -45 : 0,
                    'align' => 'right',
                    'style' => array('fontSize' => '10px', 'fontFamily' => 'Helvetica, sans-serif'),
                ),
            ),
            'yAxis' => array(
                'min' => 0,
                'allowDecimals' => false,
                'gridLineDashStyle' => 'ShortDash',
                'title' => array(
                    'text' => Yii::t('main-ui', 'Tickets'),
                ),
            ),
            'tooltip' => array(
                'pointFormat' => '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}  шт.</b> ({point.percentage:.0f}%)<br/>',
            ),
            'plotOptions' => array(
                'pie' => array(
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => array(
                        'enabled' => false
                    ),
                    'showInLegend' => true
                )
            ),
            'series' => $series,
        ),
        'htmlOptions' => array(
            'style' => 'min-width: 310px; height: 400px; margin: 0 auto'
        )
    )
);
echo '</div>';
?>

<script>

</script>
