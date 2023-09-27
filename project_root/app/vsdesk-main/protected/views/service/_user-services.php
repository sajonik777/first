<?php
$support_services = $model->get_user_services();
// var_dump($support_services);
?>
        
        <table class="table striped">
            <?php foreach($support_services as $sk => $sv):?>
                <tr>
                <td><a href="<?php echo Yii::app()->getBaseUrl(); ?>/service/<?php echo $sk; ?>"><?php echo $sv; ?></a></td>
                </tr>
            <?php endforeach;?>
        </table>
