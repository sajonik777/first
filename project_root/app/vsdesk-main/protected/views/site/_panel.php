<?php

$this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => false,
)); ?>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'list',
    'items' => $this->menu,
)); ?>