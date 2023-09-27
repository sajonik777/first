<?php

$created = explode(' ', $data->timestamp);
$user_obj = CUsers::model()->findByAttributes(array('fullname' => $data->author));
if(isset($data->channel) AND !empty($data->channel)){
    if($data->channel == 'manual'){
        $icon = 'fa-solid fa-pen-to-square';
    } else if($data->channel == 'email'){
        $icon = 'fa-solid fa-envelope';
    } else if($data->channel == 'telegram'){
        $icon = 'fa-brands fa-telegram';
    } else if($data->channel == 'viber'){
        $icon = 'fa-brands fa-viber';
    } else if($data->channel == 'whatsapp'){
        $icon = 'fa-brands fa-whatsapp';
    } else if($data->channel == 'skype'){
        $icon = 'fa-brands fa-skype';
    } else if($data->channel == 'facebook'){
        $icon = 'fa-brands fa-facebook';
    } else if($data->channel == 'slack'){
        $icon = 'fa-brands fa-slack';
    } else if($data->channel == 'webchat'){
        $icon = 'fa-solid fa-comments';
    }  else {
        $icon = 'fa-solid fa-comment';
    }
} else {
    $icon = 'fa-solid fa-comment';
}

?>
<li id="date<?php echo $data->id; ?>" class="time-label">
  <span>
    <?php echo $created[0]; ?>
</span>
</li>
<li id="comment<?php echo $data->id; ?>">
    <?php if ($data->read !== true) : ?>
        <i id="read<?php echo $data->id; ?>" class="fa <?php echo $icon;?> bg-red"></i>
    <?php else : ?>
        <i class="fa <?php echo $icon;?> bg-blue"></i>
    <?php endif; ?>

    <div class="timeline-item">
        <span class="time"><i class="fa-solid fa-clock"></i> <?php echo $created[1]; ?></span>
        <h3 class="timeline-header"><strong>
                <?php if ($user_obj->photo == "1"){echo '<img alt="asas" class="img-circle" width="35" src="/media/userphoto/' . $user_obj->id . '.png">';}else{echo '<img alt="asas" class="img-circle" width="35" src="/images/profle.png">';} ?> <?php echo CHtml::encode($data->author); ?>
        </strong> <?php if($data->show == 1){echo Yii::t('main-ui', 'add a hidden reply:');} else {echo Yii::t('main-ui', 'add a reply:');} ?> </h3>
        <div class="mailbox-controls with-border">
            <?php
            if ($data->read !== true) {
                echo CHtml::htmlButton('<i class="fa-solid fa-eye"></i>', array(
                    'class' => 'btn btn-primary btn-small',
                    'id' => 'read_btn' . $data->id,
                    'title' => Yii::t('main-ui', 'Mark as read'),
                    'onclick' => 'js:onRead('.$data->id.');'));
            }
            ?>
            <?php if (Yii::app()->user->checkAccess('canDeleteCommentsRequest') and !Yii::app()->user->checkAccess('systemUser')) : ?>
                <?php
                echo CHtml::htmlButton('<i class="fa-solid fa-trash"></i>', array(
                    'class' => 'btn btn-danger btn-small',
                    'id' => 'delete_btn' . $data->id,
                    'title' => Yii::t('main-ui', 'Delete'),
                    'onclick' => 'js:onDelete('.$data->id.');'));
                    ?>
                <?php endif; ?>
                <?php
                echo CHtml::htmlButton('<i class="fa-solid fa-quote-right"></i>', array(
                    'class' => 'btn btn-warning btn-small',
                    'id' => 'reply_btn' . $data->id,
                    'title' => Yii::t('main-ui', 'Quote'),
                    'onclick' => 'js:onReply('.$data->id.');'));
                    ?>
                </div>

                <div class="timeline-body">
                    <div id="redactor<?= $data->id ?>"><?php echo $data->comment; ?></div>
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
                    <?php
                    if ($data->files2) {
                        FilesShow::show($data->files2, 'request', '/uploads', '', 'Request');
                    }
                    ?>
                    <?php if ($data->files) : ?>
                        <?php
                        $files = explode(',', $data->files);
                        FilesShow::show($files, 'comment', '/media/' . $data->r->id . '/', $data->id, 'Request');
                        ?>
                    <?php endif; ?>
                    <div>
                    </div>
                </div>
            </div>
        </li>