<div class="form-group has-feedback" style="box-sizing: border-box">
    <div class="form">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        )); ?>
        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>

        </div>
        <?php
        $configDirPath = dirname(__FILE__) . '/../../config/';
        $mask = $configDirPath . 'ad*.inc';
        $adIncFiles = array();
        $dataList = array();
        foreach (glob($mask) as $filename) {
            $fArr = explode('/', $filename);
            $content = file_get_contents($filename);
            $confArr = unserialize(base64_decode($content));
            $confArr['fileName'] = $filename;
            $confArr['id'] = end($fArr);
            $adIncFiles[] = $confArr;
            if($confArr['ad_enabled'] == 1)
                $dataList[end($fArr)] = $confArr['accountSuffix'];
        }
        if (count($dataList) > 1) {
            echo '<div class="form-group has-feedback">';
            echo $form->dropDownList($model, 'domain', $dataList, ['style' => 'width:100%;']);
            echo $form->error($model, 'domain');
            echo '</div>';
        }
        ?>


        <div class="form-group has-feedback">
            <?php echo $form->textField($model, 'username', array('placeholder' => Yii::t('main-ui', 'Here your login'), 'class'=>'form-control')); ?>
            <span class="fa-solid fa-user form-control-feedback"></span>
            <?php echo $form->error($model, 'username'); ?>
        </div>

        <div class="form-group has-feedback">
            <?php echo $form->passwordField($model, 'password', array('placeholder' => Yii::t('main-ui', 'Here your password'), 'class'=>'form-control')); ?>
            <span class="fa-solid fa-lock form-control-feedback"></span>
            <?php echo $form->error($model, 'password'); ?>

        </div>
        <div class="inline-block">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>&nbsp;
            <?php echo $form->label($model, 'rememberMe', array('style'=>'display: inline-block')); ?>
            <?php if(Yii::app()->params['allow_register'] == 1):?><a style="float: right" href="<?php echo Yii::app()->createUrl('site/recovery') ?>"><?php echo Yii::t('main-ui', 'Forgot password?'); ?></a><?php endif; ?>
        </div>
        <div class="social-auth-links">
            <button class="btn btn-primary btn-block" type="submit">
				<i class="fa-solid fa-arrow-right-to-bracket"></i>
				<?php echo Yii::t('main-ui', 'Login'); ?>
			</button>
            <?php if(Yii::app()->params['allow_register'] == 1):?>
				<button onclick="window.location.href='register'" class="btn btn-primary btn-block" type="button">
					<i class="fa-solid fa-address-card"></i>
					<?php echo Yii::t('main-ui', 'Register'); ?>
				</button>
			<?php endif;?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->

</div>



