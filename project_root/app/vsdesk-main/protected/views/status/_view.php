<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('enabled')); ?>:</b>
    <?php echo CHtml::encode($data->enabled); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('label')); ?>:</b>
    <?php echo CHtml::encode($data->label); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('tag')); ?>:</b>
    <?php echo CHtml::encode($data->tag); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('close')); ?>:</b>
    <?php echo CHtml::encode($data->close); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('notify_user')); ?>:</b>
    <?php echo CHtml::encode($data->notify_user); ?>
    <br/>

    <?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('notify_manager')); ?>:</b>
	<?php echo CHtml::encode($data->notify_manager); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notify_group')); ?>:</b>
	<?php echo CHtml::encode($data->notify_group); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sms')); ?>:</b>
	<?php echo CHtml::encode($data->sms); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message')); ?>:</b>
	<?php echo CHtml::encode($data->message); ?>
	<br />

	*/ ?>

</div>