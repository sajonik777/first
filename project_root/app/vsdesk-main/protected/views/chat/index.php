<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Chat') => array('index')
);
Yii::app()->clientScript->registerScriptFile('/js/socket.js', CClientScript::POS_END);
?>

<div class="page-header">
    <div style="display: inline-block;"><h3><?= Yii::t('main-ui', 'Chat') ?></h3></div>
</div>
<div class="box">
    <div class="box-body">
        <?php
        Yii::app()->clientScript->registerScript('loading', '
                function loopGetChats2()
                {
                    var csrf = "' . Yii::app()->request->csrfToken . '";
                    $.ajax({
                        type: \'POST\',
                        url: \'/chat/chats\',
                        data: {\'YII_CSRF_TOKEN\': csrf},
                        dataType: "text",
                        cache: false,
                        success: function (result) {
                            $(\'#sock-messages\').html(result);
                        }
                    });
                     setTimeout(loopGetChats2, 2000);
                }
                setTimeout(loopGetChats2, 2000);
                ', CClientScript::POS_READY);
        ?>
        <!-- <label>Server address:</label>
        <input id="sock-addr" type="text" value="ws://echo.websocket.org">
        <br>
        <input id="sock-recon-butt" type="button" value="reconnect">
        <input id="sock-disc-butt" type="button" value="disconnect">
        <br><br> -->
        <label><?= Yii::t('main-ui', 'Message') ?>:</label>
        <input id="sock-msg" type="text" style="width: 400px;">
        <input id="sock-send-butt" type="button" style="margin-bottom: 10px;" value="<?= Yii::t('main-ui', 'send') ?>">
        <br>
        <div id="sock-info" style="color: #c8c8c8"></div>

        <div>
            <!-- DIRECT CHAT PRIMARY -->
            <div>
                <div class="direct-chat-messages" style="height: 600px;" id="sock-messages">
                    <!--  <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">Alexander Pierce</span>
                            <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                        </div>
                        <img class="direct-chat-img" src="/images/profle.png" alt="Message User Image">
                        <div class="direct-chat-text">
                            Is this template really for free? That's unbelievable!
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
