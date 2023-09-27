<?php

if ($item): ?>
    <h4><?php echo Yii::t('main-ui', 'Enter form data'); ?></h4>
<?php endif; ?>


<?php $i = 0; ?>
<?php
foreach ($item as $data_value): ?>
    <?php $i = $i + 1; ?>
    <?php echo '<label>' . $data_value->name . '</label>'; ?>
    <?php
    if (isset($pst)){
      foreach($pst as $key => $value){
        if($key == $i . 'name'){
          echo '<input type="text" name="Asset[' . $i . 'name]" id="Asset[' . $i . 'name]" size="10" class="span12" maxlength="200" value="'.$value.'">';
        }
      }
    } else {
      echo '<input type="text" name="Asset[' . $i . 'name]" id="Asset[' . $i . 'name]" size="10" class="span12" maxlength="200" value="'.$value.'">';
    }

     ?>
    <?php  ?>
<?php endforeach; ?>
