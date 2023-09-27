<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
    <?php echo CHtml::encode($data->name); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
    <?php echo CHtml::encode($data->description); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('sla')); ?>:</b>
    <?php echo CHtml::encode($data->sla); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('priority')); ?>:</b>
    <?php echo CHtml::encode($data->priority); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('manager')); ?>:</b>
    <?php echo CHtml::encode($data->manager); ?>
    <br/>


</div>