<hr/>
<div class="row-fluid">
    <?php
    /* @var $this DefaultController */
    $form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm',
        array(
            'id' => 'verticalForm',
            'htmlOptions' => array('class' => 'well'), // for inset effect
        )
    );
    $filename = ROOT_PATH . '/protected/data/installer.lock';
    if (!file_exists($filename)) {
        $this->widget(
            'bootstrap.widgets.TbWizard',
            array(
                'type' => 'tabs', // 'tabs' or 'pills'
                'pagerContent' => '<hr><div style="float:right">
                                    <input type="button" class="btn button-next" name="next" value="' . Yii::t('install', 'Next') . '" />
                                   </div>
                                    <div style="float:left">
                                        <input type="button" class="btn button-previous" name="previous" value="' . Yii::t('install', 'Previous') . '" />
                                    </div><br /><br />',
                'options' => array(
                    'nextSelector' => '.button-next',
                    'previousSelector' => '.button-previous',
                    'firstSelector' => '.button-first',
                    'lastSelector' => '.button-last',
                    'finishSelector' => '.button-finish',
                    'onTabShow' => 'js:function(tab, navigation, index) {
                    var $total = navigation.find("li").length;
                    var $current = index+1;
                    var $percent = ($current/$total) * 100;
                    $("#wizard-bar > .bar").css({width:$percent+"%"});
                }',
                    'onTabClick' => 'js:function(tab, navigation, index) {alert("Переход по вкладкам запрещен");return false;}',
                    'onNext' => 'js:function(tab, navigation, index) { 
                
                    if(index==1) {
                        if($("span").hasClass("red")){
                            alert("Не все условия выполнены!");
                            return false;
                        }
                    }
                    
                    if(index==2) {
                        var flag = false;
                        if(!$(\'#dbhost\').val()) { flag = true; }
                        if(!$(\'#dbname\').val()) { flag = true; }
                        if(!$(\'#dbuser\').val()) { flag = true; }
                        if(flag == true) {
                            alert(\'Все поля должны быть заполненны!\');
                            return false;
                        }
                    }
                    
                    if(index==3) {
                        var flag = false;
                        if(!$(\'#smhost\').val()) { flag = true; }
                        if(!$(\'#smport\').val()) { flag = true; }
                        if(!$(\'#adminEmail\').val()) { flag = true; }
                        if(!$(\'#smfrom\').val()) { flag = true; }
                        if(!$(\'#smfromname\').val()) { flag = true; }
                        if(flag == true) {
                            alert(\'Все поля должны быть заполненны!\');
                            return false;
                        }
                    }
                    
                    if(index==3) {
                         jQuery.ajax({
                            url: "install/default/write",
                            type: "POST",
                            dataType: "html",
                            data: jQuery("#verticalForm").serialize(), 
                            success: function(response) {
                                document.getElementById("retWrite").innerHTML = response;
                            },
                            error: function(response) {
                                document.getElementById("retWrite").innerHTML = "Ошибка при отправке формы";
                            }
                        });
                    }
                    
                    }',
                ),
                'tabs' => array(
                    array(
                        'label' => Yii::t('install', 'Check requirements'),
                        'content' => $this->renderPartial('_check', array('rewrite' => $rewrite), true),
                        'active' => true
                    ),
                    array('label' => Yii::t('install', 'Database settings'), 'content' => $this->renderPartial('_db', NULL, true)),
                    array('label' => Yii::t('install', 'Main settings'), 'content' => $this->renderPartial('_config', NULL, true)),
                    array('label' => Yii::t('install', 'Finish setup'), 'content' => $this->renderPartial('_write', NULL, true)),
                ),
            )
        );
        echo '<hr><div id="wizard-bar" class="progress progress-striped active"><div class="bar"></div></div>';
    } else {
        echo '<h3>'. Yii::t('install', 'Univef is already installed'). '</h3>';
    }
    $this->endWidget();
    ?>
</div>