<?php

$this->breadcrumbs = array(
    'Cunits',
);

$this->menu = array(
    array('label' => 'Create Cunits', 'url' => array('create')),
    array('label' => 'Manage Cunits', 'url' => array('admin')),
);
?>

<h1>Cunits</h1>

<?php $this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
)); ?>
