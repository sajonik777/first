<div class="view">
    <?php echo $model->slabel; ?>
    <br/>

    <div class="row-fluid">
        <div class="span3">
            <i class="fa-regular fa-calendar-days"></i>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('date')); ?>:</b>
                <?php echo CHtml::encode($model->date); ?>
            <br/>
            <?php if ($model->enddate): ?>
                <i class="fa-regular fa-calendar-days"></i>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('enddate')); ?>
                        :</b>
                    <?php echo CHtml::encode($model->enddate); ?>
                <br/>
            <?php endif; ?>
            <span class="fa-solid fa-user"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('creator')); ?>:</b>
                <?php echo CHtml::encode($model->creator); ?>
            <br/>
    <span class="fa-solid fa-user"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('manager')); ?>:</b>
        <?php echo CHtml::encode($model->manager); ?>
            <br/>
            <?php if ($model->users): ?>
                <span class="fa-solid fa-user"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('users')); ?>:</b>
                    <?php echo CHtml::encode($model->users); ?>
                <br/>
            <?php endif; ?>
        </div>
        <div class="span3">
    <span class="fa-solid fa-heart-pulse"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('priority')); ?>
            :</b>
        <?php echo CHtml::encode($model->priority); ?>
            <br/>
    <span class="fa-solid fa-inbox"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('category')); ?>:</b>
        <?php echo CHtml::encode($model->category); ?>
            <br/>
    <span class="fa-solid fa-layer-group"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('service')); ?>:</b>
        <?php echo CHtml::encode($model->service); ?>
            <br/>
     <span class="fa-solid fa-crosshairs"></span>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('influence')); ?>:</b>
         <?php echo CHtml::encode($model->influence); ?>
            <br/>
            <i class="fa-solid fa-clock"></i>&nbsp;<b><?php echo CHtml::encode($model->getAttributeLabel('downtime')); ?>:</b>
                <?php echo CHtml::encode($model->downtime); ?>
            <br/>
            <?php if ($model->knowledge) {
                echo '<span class="fa-solid fa-book-open">&nbsp;</span>';
                echo CHtml::link('У этой проблемы есть запись в Базе знаний', array('knowledge/module/view/id/' . $model->knowledge), array('target' => '_blank'));
                echo '<br/>';
            }; ?>
        </div>
        <div class="row-fluid">
            <div class="span9">
                <h4><?php echo CHtml::encode($model->getAttributeLabel('description')); ?>:</h4>
                <?php echo $model->description; ?>
                <br/>
                <?php if ($model->workaround) {
                    echo '<h4>' . CHtml::encode($model->getAttributeLabel('workaround')) . ':</h4>';
                    echo $model->workaround;
                    echo '<br/>';
                }; ?>
                <?php if ($model->decision) {
                    echo '<h4>' . CHtml::encode($model->getAttributeLabel('decision')) . ':</h4>';
                    echo $model->decision;
                }; ?>
            </div>
        </div>
    </div>
</div>