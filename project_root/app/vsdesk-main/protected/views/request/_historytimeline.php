<?php

$created = explode(' ', $data->datetime);
?>
<li id="date<?php echo $data->id; ?>" class="time-label" style="margin-right: 70%">
                  <span>
                    <?php echo $created[0]; ?>
                  </span>
</li>
<li id="comment<?php echo $data->id; ?>">
        <i class="fa fa-regular fa-clock bg-blue"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa-solid fa-clock"></i> <?php echo $created[1]; ?></span>
        <h3 class="timeline-header"><strong>
                <?php echo CHtml::encode($data->cusers_id); ?>
            </strong></h3>
        <div class="timeline-body">
            <div id="redactor<?= $data->id ?>"><?php echo $data->action; ?></div>
            <br>
            <p>
                <button id="btn-save<?= $data->id ?>" class='btn btn-info btn-small' style="display: none;" outline>
                    <?php echo Yii::t('main-ui', 'Save'); ?>
                </button>
                <button id="btn-cancel<?= $data->id ?>" class='btn btn-danger btn-small' style="display: none;" outline>
                    <?php echo Yii::t('main-ui', 'Cancel'); ?>
                </button>
            </p>
        </div>
        <div class="timeline-footer">
            <div>
            </div>
        </div>
    </div>
</li>