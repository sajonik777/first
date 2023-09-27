<?php

$session = $model->tw_session_rl;
$until = false;
if (!empty($session)) {
    $valid_until = DateTime::createFromFormat('Y-m-d H:i:s', $session->valid_until);
    $current_dt = new DateTime();
    $until = $current_dt >= $valid_until;
}

if (empty($session) || $until) {
    echo CHtml::ajaxButton(
        Yii::t('main-ui', 'Create TeamViewer Session'),
        CHtml::normalizeUrl(['request/createtwsession']),
        [
            'data' => 'request=' . $model->id,
            'success' => 'function(data){$("#tw_session_rezult").html(data);$("#twsess_id").hide();}'
        ],
        ['class' => 'btn btn-primary', 'id' => 'twsess_id']
    );
}
?>
<div id="tw_session_rezult">
    <?php
    if (!empty($session) && !$until) {
        if (Yii::app()->user->checkAccess('systemManager')) {
            $this->widget('bootstrap.widgets.TbButton', array(
                'type' => 'warning',
                'buttonType' => 'link',
                'htmlOptions' => array('target' => '_blank'),
                'url' => $session->supporter_link,
                'label' => Yii::t('main-ui', 'Start session ') . $session->code
            ));
        }
        if (Yii::app()->user->checkAccess('systemUser')) {
            $this->widget('bootstrap.widgets.TbButton', array(
                'type' => 'warning',
                'buttonType' => 'link',
                'htmlOptions' => array('target' => '_blank'),
                'url' => $session->end_customer_link,
                'label' => Yii::t('main-ui', 'Start session ') . $session->code
            ));
        }
        if (Yii::app()->user->checkAccess('systemAdmin')) {
            $ret = "<div><b>Ссылка на подключение к сессии {$session->code}: <a href='{$session->supporter_link}'>для исполнителя</a> | <a href='{$session->end_customer_link}'>для заказчика</a> ( <a href='{$session->end_customer_link}'>{$session->end_customer_link}</a> )</b></div>";
            echo $ret;
        }
    }
    ?>
</div>
