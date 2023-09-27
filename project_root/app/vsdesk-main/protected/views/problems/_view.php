<div class="view">
    <?php echo $data->slabel; ?>
    <i class="fa-regular fa-calendar-days">&nbsp;<b>Изменено:</b>
        <?php echo CHtml::encode($data->date); ?></i>
    &nbsp;
    <?php if ($data->enddate): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('enddate')); ?>:</b>
        <?php echo CHtml::encode($data->enddate); ?>
    <?php endif; ?>
    <br/>
    <span class="fa-solid fa-user"></span>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('creator')); ?>:</b>
        <?php echo CHtml::encode($data->creator); ?>
    &nbsp;
    <span class="fa-solid fa-user"></span>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
        <?php echo CHtml::encode($data->username); ?>
    &nbsp;
    <?php if ($data->priority): ?>
        <span class="fa-solid fa-heart-pulse"></span>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('priority')); ?>
                :</b>
            <?php echo CHtml::encode($data->priority); ?>
        &nbsp;
    <?php endif; ?>
    <?php if ($data->influence): ?>
        <span class="fa-solid fa-crosshairs"></span>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('influence')); ?>:</b>
            <?php echo CHtml::encode($data->influence); ?>
        &nbsp;
    <?php endif; ?>
    <?php if ($data->category): ?>
        <span class="fa-solid fa-inbox"></span>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
            <?php echo CHtml::encode($data->category); ?>
        <br/>
    <?php endif; ?>
    <?php if ($data->service): ?>
        <span class="fa-solid fa-layer-group"></span>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('service')); ?>:</b>
            <?php echo CHtml::encode($data->service); ?>
        &nbsp;
    <?php endif; ?>
    <?php if ($data->downtime): ?>
        <i class="fa-solid fa-clock"></i>&nbsp;<b><?php echo CHtml::encode($data->getAttributeLabel('downtime')); ?>:</b>
            <?php echo CHtml::encode($data->downtime); ?>
        <br/>
    <?php endif; ?>
    <?php if ($data->description): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
        <?php echo $data->description; ?>
        <br/>
    <?php endif; ?>
    <?php if ($data->workaround): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('workaround')); ?>:</b>
        <?php echo $data->workaround; ?>
        <br/>
    <?php endif; ?>
    <?php if ($data->decision): ?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('decision')); ?>:</b>
        <?php echo $data->decision; ?>
        <br/>
    <?php endif; ?>
</div>
<br/>