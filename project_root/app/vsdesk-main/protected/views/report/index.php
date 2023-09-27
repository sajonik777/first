<?php

$this->breadcrumbs = array(
    'Zreports',
);

$this->menu = array(
    array('label' => 'Create Zreport', 'url' => array('create')),
    array('label' => 'Manage Zreport', 'url' => array('admin')),
);
?>

<h1>Zreports</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
)); ?>
