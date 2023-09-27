<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Chat') => array('index')
);


?>
<div class="page-header">
    <h3><i class="fa-solid fa-comments fa-xl"> </i> <?= Yii::t('main-ui', 'Chat') ?></h3>
</div>
<div class="box box-primary direct-chat-primary">
    <div class="box-body">
        <div class="row-fluid">
            <div class="span12">
                <div class="span3">
                    <script>
                        function changeReader() {
                            location.href = '/chat/privates?user=' + $("#readers-list").val();
                        }
                    </script>
                    <?php
                    $data = CHtml::listData(CUsers::model()->findAllByAttributes(array('active' => 1)), 'fullname', 'fullname');
                    array_unshift($data, Yii::t('main-ui', 'Select user'));
                    $this->widget(
                        'bootstrap.widgets.TbSelect2',
                        array(
                            'asDropDownList' => true,
                            'name' => 'reader',
                            'data' => $data,
                            'options' => array(
                                'width' => '100%',
                            ),
                            'htmlOptions' => array(
                                'onclick' => 'js:changeReader();',
                                'id' => 'readers-list',
                            ),
                        )
                    );
                    ?>
                    <?php
                    if (isset($_GET['user']) and !array_key_exists($_GET['user'], $all)) {
                        $all[$_GET['user']] = 0;
                    }
                    ?>
                    <div  class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo Yii::t('main-ui', 'Main chat'); ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <ul id="all_msg" class="nav nav-pills nav-stacked">
                                <li class="user_li" onclick="location.href='/chat/privates?user=main'">
                                    <img class="img-circle img-sm" src="/images/profle.png">
                                    <a id="main_msg" class="users-list-name"
                                       href="#"><strong>main</strong>
                                        <span id="main_msg_count" class ="label label-success"></span>
                                    </a>
                                </li>
                            </ul>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo Yii::t('main-ui', 'Last chats'); ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <ul id="priv_msg" class="nav nav-pills nav-stacked">
                                <?php foreach ($all as $member => $count) { ?>
                                    <?php if ($member !== 'main'): ?>
                                    <?php $user = CUsers::model()->findByAttributes(array('fullname' => $member)); ?>
                                    <li class="user_li" onclick="location.href='/chat/privates?user=<?= $member ?>'">
                                        <img class="img-circle img-sm" src="/images/profle.png">
                                        <a id="<?php echo $user->Username; ?>_msg" class="users-list-name"
                                           href="#"><?= $member ?>
                                            <span id="<?php echo $user->Username; ?>_msg_count" class ="label label-success"></span>
                                        </a>
                                    </li>
                                    <?php else: ?>
                                        <!--<li class="user_li" style="display: none">
                                            <img class="img-circle img-sm" src="/images/profle.png">
                                            <a class="users-list-name"
                                               href="#"><?/*= $member */?></a>
                                        </li>-->
                                    <?php endif; ?>
                                <?php } ?>
                            </ul>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <div class="span9">
                    <div class="row-fluid">
                        <div class="span11"><input id="sock-msg" type="text" name="message" placeholder="Введите сообщение ..."
                                                   class="form-control" style="padding: 7px 0px 7px 7px;">
                        </div>
                        <div class="span1"><span class="input-group-btn">
                            <button id="sock-send-butt" type="button" class="btn btn-info"><i
                                    class="fa-solid fa-comments"> </i></button>
                          </span>
                        </div>
                    </div>
                    <?= isset($_GET['user']) ? '<input id="sock-usr" type="hidden" value="' . $_GET['user'] . '">' : '' ?>

                    <?php if (isset($_GET['user']) and !empty($_GET['user'])): ?>
                        <!-- DIRECT CHAT PRIMARY -->
                        <div>
                            <div class="box-header with-border"><strong>Чат с <?php if($_GET['user'] !== 'main'){ echo $_GET['user'];}else{ echo 'общим списком';}?>:</strong></div>
                            <div class="direct-chat-messages" style="height: 600px;" id="sock-messages">

                            </div>
                        </div>
                    <?php else: ?>
                        <p>Выберите собеседника</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box-footer">
        <div id="sock-info" style="color: #c8c8c8"></div>
    </div>
</div>
