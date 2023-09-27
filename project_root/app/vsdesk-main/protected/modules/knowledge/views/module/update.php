<?php

$this->breadcrumbs = array(
	Yii::t('main-ui', 'Knowledgebase')=>array('index'),
	$model->name                      =>array('view','id'=>$model->id),
	Yii::t('main-ui', 'Edit'),
    );

    ?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Edit record') .' "'. $model->name; ?>"</h3>
        <?php if ($model->image): ?>
            <?php
            $i = 0;
            foreach ($files as $file){
                $i = $i+1;
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $os_type = DetectOS::getOS();
                $filename = ($os_type == 2) ? iconv('UTF-8', 'WINDOWS-1251', $file) : $file;
                $fname = Yii::getPathOfAlias('webroot').'/media/kb/'.$model->id.'/'.$filename;
                $mime=finfo_file($finfo, $fname);
                $image = explode("/",$mime);
                if($image[0] =='image'){
                    echo '<span id="'.$i.'">'. CHtml::ajaxLink ('<span class="icon-trash"> </span>', CController::createUrl('/knowledge/module/deletefile', array('id'=>$model->id, 'file'=>$file)), array('update' => '#'.$i,'beforeSend' => 'function() {
                        $("#'.$i.'").addClass("loading");
                    }',
                    'complete' => 'function() {
                      $("#'.$i.'").removeClass("loading");
                  }')).' <a class="thumb" target="_blank" href="/media/kb/'.$model->id.'/'.$file.'">'.$file.'<span><img src="/media/kb/'.$model->id.'/'.$file.'"/></span></a>'.' </span>';
                }else{
                    echo '<span id="'.$i.'">'. CHtml::ajaxLink ('<span class="icon-trash"> </span>', CController::createUrl('/knowledge/module/deletefile', array('id'=>$model->id, 'file'=>$file)), array('update' => '#'.$i,'beforeSend' => 'function() {
                        $("#'.$i.'").addClass("loading");
                    }',
                    'complete' => 'function() {
                      $("#'.$i.'").removeClass("loading");
                  }')).' <a target="_blank" href="/media/kb/'.$model->id.'/'.$file.'">'.$file.'</a>'.' </span>';
                }
                finfo_close($finfo);

            }
            ?>
        <?php endif; ?>
    </div>
    <?php $this->widget('bootstrap.widgets.TbMenu', array(
      'type' =>'pills',
      'items'=> $this->menu,
      )); ?>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>