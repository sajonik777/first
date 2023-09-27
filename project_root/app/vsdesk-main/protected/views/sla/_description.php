<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'type' => 'striped bordered condensed',
    'attributes' => array(
        //'id',
        'name',
        'rhours',
        'nrhours',
        'shours',
        'nshours',
        'wstime',
        'wetime',
        'taxes',
        array(
            'label' => Yii::t('main-ui', 'Seven days a week'),
            'type' => 'raw',
            'value' => $round_days,
        ),

    ),
)); ?>