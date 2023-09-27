<?php

$labels = [];
foreach ($gridDataProvider->getData() as $item) {
    foreach ($item->fields as $key => $value)
        $labels[] = $key;
}
$labels = array_unique($labels);


$columns = array(
    array(
        'name' => 'slabel',
        'type' => 'raw',
        'header' => Yii::t('main-ui', 'Status'),
    ),
    array(
        'name' => 'Date',
        'headerHtmlOptions' => array('width' => 120),
        'header' => Yii::t('main-ui', 'Created'),
    ),
    array(
        'name' => 'StartTime',
        'header' => Yii::t('main-ui', 'Start Time'),
        'headerHtmlOptions' => array('width' => 70),
    ),
    array(
        'name' => 'fStartTime',
        'header' => Yii::t('main-ui', 'Fact Start time'),
        'headerHtmlOptions' => array('width' => 70),
    ),
    array(
        'name' => 'EndTime',
        'header' => Yii::t('main-ui', 'Deadline'),
        'headerHtmlOptions' => array('width' => 70),
    ),
    array(
        'name' => 'fEndTime',
        'header' => Yii::t('main-ui', 'Fact End Time'),
        'headerHtmlOptions' => array('width' => 70),
    ),
    array(
        'name' => 'lead_time',
        'header' => Yii::t('main-ui', 'Time worked'),
        'headerHtmlOptions' => array('width' => 70),
    ),
    array(
        'name' => 'Name',
        'header' => Yii::t('main-ui', 'Name'),
        'headerHtmlOptions' => array('width' => 250),
    ),
    array(
        'name' => 'phone',
        'header' => Yii::t('main-ui', 'Phone'),
        'headerHtmlOptions' => array('width' => 150),
    ),
    array(
        'name' => 'room',
        'header' => Yii::t('main-ui', 'Room'),
        'headerHtmlOptions' => array('width' => 150),
    ),
    array(
        'name' => 'Address',
        'header' => Yii::t('main-ui', 'Address'),
        'headerHtmlOptions' => array('width' => 250),
    ),
    array(
        'name' => 'company',
        'header' => Yii::t('main-ui', 'Company'),
    ),
    array(
        'name' => 'fullname',
        'headerHtmlOptions' => array('width' => 120),
        'header' => Yii::t('main-ui', 'Customer'),
    ),
    array(
        'name' => 'cunits',
        'header' => Yii::t('main-ui', 'Units'),
    ),
    array(
        'name' => 'service_name',
        'header' => Yii::t('main-ui', 'Service'),
    ),
    array(
        'name' => 'mfullname',
        'header' => Yii::t('main-ui', 'Manager'),
    ),
    array(
        'name' => 'groups_id',
        'value' => '$data->groups_rl ? $data->groups_rl->name : NULL',
        'header' => Yii::t('main-ui', 'Group'),
    ),
    array(
        'name' => 'ZayavCategory_id',
        'header' => Yii::t('main-ui', 'Category'),
    ),
    array(
        'name' => 'Priority',
        'header' => Yii::t('main-ui', 'Priority'),
    ),
    array(
        'name' => 'Content',
        'header' => Yii::t('main-ui', 'Content'),
        'value' => 'strip_tags($data->Content)',
    ),
    array(
        'name' => 'rating',
        'header' => Yii::t('main-ui', 'Rating'),
        'type' => 'raw',
        'value' => '$data->rating',
        'headerHtmlOptions' => array('width' => 100),
        'htmlOptions' => array('class' => 'rating-block'),
        'filter' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
        'sortable' => false,
    )
);

foreach ($labels as $label) {
    $columns[] = ['name' => $label, 'header' => $label, 'value' => '$data->fields["' . $label . '"]'];
}

$dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'full-fields-report', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => new CArrayDataProvider($gridDataProvider), //model is used to get attribute labels
        'columns' => $columns,
    ),
));
$fixed_columns = array_filter(array(
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'headerHtmlOptions' => array('width' => 60),
    ),
));