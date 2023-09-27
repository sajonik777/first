<?php

$this->widget('CallWidget', ['uniqid' => $model->uniqid]);
$this->widget('bootstrap.widgets.TbDetailView', [
    'data' => $model,
    'attributes' => [
        'status',
        'date',
        'adate',
        'edate',
        'dialer_name',
        'dr_number',
        'dr_company',
        'dialed_name',
        'dd_number',
    ],
]);
