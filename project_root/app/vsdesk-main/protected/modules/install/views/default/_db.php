<div class="row-fluid">
    <p><strong><?= Yii::t('install', 'Database settings') ?></strong></p>
</div>
<br/>
<div class="row-fluid">
    <div class="span6">
        <div class="input"><p><?= Yii::t('install', 'Database Host') ?></p>
            <input size="50" class="text required" type="text" name="dbhost" id="dbhost" value="localhost"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'Имя БД') ?></p>
            <input size="50" class="text required" type="text" name="dbname" id="dbname" value="univefservicedesk"/>
        </div>

        <div><p><?= Yii::t('install', 'Create Database if it doesn\'t exists?') ?></p>
            <?php
            $this->widget(
                'bootstrap.widgets.TbToggleButton',
                array(
                    'name' => 'create_database',
                    'value' => true,
                )
            );
            ?>
        </div>
    </div>
    <div class="span6">
        <div class="input"><p><?= Yii::t('install', 'Database User') ?></p>
            <input size="50" class="text" type="text" name="dbuser" id="dbuser" value="univefservicedesk"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'Database Pass') ?></p>
            <input size="50" class="text" type="password" name="dbpass" id="dbpass" value=""/>
        </div>

        <div>
            <p><?= Yii::t('install', 'New install?') ?></p>
            <?php
            $this->widget(
                'bootstrap.widgets.TbToggleButton',
                array(
                    'name' => 'new_install',
                    'value' => true,
                    'enabledLabel'=>Yii::t('install', 'Install'),
                    'disabledLabel'=>Yii::t('install', 'Update'),
                    'width' => 200,

                )
            );
            ?>
         </div>
    </div>
</div>


<br/>
<div class="row-fluid">
    <div class="span6">
        <?php /* $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'label' => Yii::t('install', 'Check DB connection'),
                'type' => 'info',
                'ajaxOptions' => array(
                    'type' => 'POST',
                    'dataType' => 'text',
                    'url' => CController::createUrl('/install/checkdb'),
                    'update' => '#test',

                    'error' => 'function (data) {
                         console.log(data);
                        }',

                ),

            )
        ); */ ?>
        <?php
        echo CHtml::ajaxbutton(
            Yii::t('install', 'Check DB connection'),
            array('checkdb'), // Yii URL
            array(
                'type' => 'POST',
                'data' => 'js:{"dbhost": $(\'#dbhost\').val(), "dbname": $(\'#dbname\').val(), "dbuser": $(\'#dbuser\').val(), "dbpass": $(\'#dbpass\').val(), "YII_CSRF_TOKEN": "' . Yii::app()->request->csrfToken . '" }',
                'update' => '#testDB',
            ), // jQuery selector
            array('class' => 'btn btn-info')
        );
        ?>

    </div>
    <br><br>
    <div id="testDB"></div>
</div>

<!-- end buttons -->
