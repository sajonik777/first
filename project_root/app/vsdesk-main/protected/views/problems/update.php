<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Problems') => array('index'),
    $model->id => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Update problem'),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Update problem') . ' #' . $model->id; ?></h3>
        <?php if (Yii::app()->user->checkAccess('canAssignProblem')): ?>
            <hr>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label' => Yii::t('main-ui', 'Assign to user'),
                'type' => 'primary',
                'icon' => 'user',
                'htmlOptions' => array(
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ),
            ));
            ?>
        <?php endif; ?>
        <?php if ($model->image): ?>
            <div>
                <br/>
                <?php
                $i = 0;
                foreach ($files as $file) {
                    $i = $i + 1;
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $os_type = DetectOS::getOS();
                    $filename = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
                    $fname = Yii::getPathOfAlias('webroot') . '/media/problems/' . $model->id . '/' . $filename;
                    $mime = finfo_file($finfo, $fname);
                    $image = explode("/", $mime);
                    if ($image[0] == 'image') {
                        echo '<span style="font-size: 12px" id="' . $i . '">' . CHtml::ajaxLink('<span class="icon-trash"> </span>', CController::createUrl('problems/deletefile', array('id' => $model->id, 'file' => $file)), array('update' => '#' . $i, 'beforeSend' => 'function() {
                            $("#' . $i . '").addClass("loading");
                            }',
                                'complete' => 'function() {
                              $("#' . $i . '").removeClass("loading");
                            }')) . ' <a class="thumb" target="_blank" href="/media/problems/' . $model->id . '/' . $file . '">' . $file . '<span><img src="/media/problems/' . $model->id . '/' . $file . '"/></span></a>' . ' </span>';
                    } else {
                        echo '<span style="font-size: 12px" id="' . $i . '">' . CHtml::ajaxLink('<span class="icon-trash"> </span>', CController::createUrl('problems/deletefile', array('id' => $model->id, 'file' => $file)), array('update' => '#' . $i, 'beforeSend' => 'function() {
                            $("#' . $i . '").addClass("loading");
                            }',
                                'complete' => 'function() {
                              $("#' . $i . '").removeClass("loading");
                            }')) . ' <a target="_blank" href="/media/problems/' . $model->id . '/' . $file . '">' . $file . '</a>' . ' </span>';
                    }
                    finfo_close($finfo);
                }
                ?>
            </div>
        <?php endif; ?>
    </div>

<?php echo $this->renderPartial('_upform', array('model' => $model)); ?>