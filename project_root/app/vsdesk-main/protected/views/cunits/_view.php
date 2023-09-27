<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
    <?php echo CHtml::encode($data->type); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
    <?php echo CHtml::encode($data->status); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('slabel')); ?>:</b>
    <?php echo CHtml::encode($data->slabel); ?>
    <br/>

    <!-- <b><?php echo CHtml::encode($data->getAttributeLabel('cost')); ?>:</b>
    <?php echo CHtml::encode($data->cost); ?>
    <br/> -->

    <b><?php echo CHtml::encode($data->getAttributeLabel('user')); ?>:</b>
    <?php echo CHtml::encode($data->user); ?>
    <br/>

    <?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fullname')); ?>:</b>
	<?php echo CHtml::encode($data->fullname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inventory')); ?>:</b>
	<?php echo CHtml::encode($data->inventory); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datein')); ?>:</b>
	<?php echo CHtml::encode($data->datein); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateout')); ?>:</b>
	<?php echo CHtml::encode($data->dateout); ?>
	<br />

	*/ ?>

</div>