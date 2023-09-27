<div class="row-fluid">
    <div class="span6">

        <input type="hidden" name="lang" id="lang" value="<?= $_GET['lang'] ?>"/>
        
        <div class="input"><p><?= Yii::t('install', 'SMTP server host') ?></p>
            <input size="50" class="text" type="text" name="smhost" id="smhost" value="localhost"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'SMTP port') ?></p>
            <input size="15" class="text" type="text" name="smport" id="smport" value="25"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'SMTP username') ?></p>
            <input size="50" class="text" type="text" name="smusername" id="smusername"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'SMTP password') ?></p>
            <input size="50" class="text" type="password" name="smpassword" id="smpassword"/>
        </div>
    </div>

    <div class="span6">

        <div class="input"><p><?= Yii::t('install', 'Admin e-mail') ?></p>
            <input size="50" class="text" type="text" name="adminEmail" id="adminEmail" value="your@email.com"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'SMTP from address') ?></p>
            <input size="50" class="text" type="text" name="smfrom" id="smfrom" value="your@email.com"/>
        </div>

        <div class="input"><p><?= Yii::t('install', 'From: text') ?></p>
            <input size="50" class="text" type="text" name="smfromname" id="smfromname" value="Univef support system"/>
        </div>

        <div class="input"><p>
                <b><?php echo Yii::t('install', 'Install the scheduler task (only for dedicated server)'); ?></b></p>
            <?php
            $this->widget(
                'bootstrap.widgets.TbToggleButton',
                array(
                    'name' => 'install_cron',
                    'value' => true,
                )
            );
            ?>
        </div>

    </div>
</div>