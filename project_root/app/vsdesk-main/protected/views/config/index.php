<div class="span12">
    <?php
    $this->breadcrumbs = array(
        Yii::t('main-ui', 'Preferences') => array('index'),
        Yii::t('main-ui', 'Edit'),

    );
    ?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Preferences'); ?></h3>
    </div>
    <?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block' => true,
        'fade' => true,
        'closeText' => '×',
    )); ?>

    <?php $this->widget(
        'bootstrap.widgets.TbTabs',
        array(
            'type' => 'tabs',// 'tabs' or 'pills'
            'placement' => 'left',
            'tabs' => array(
                array(
                    'label' => Yii::t('main-ui', 'Main settings'),
                    'content' => $this->renderPartial('_main', array('model' => $model), true, true),
                    'active' => true
                ),
                array('label' => Yii::t('main-ui', 'Get requests from mail'), 'content' => $this->renderPartial('_getmail', array('model2' => $model2), true, false)),
                array('label' => Yii::t('main-ui', 'AD settings'), 'content' => $this->renderPartial('_ad', array('model3' => $model3), true, false)),
                array('label' => Yii::t('main-ui', 'SMS'), 'content' => $this->renderPartial('_sms', array('model4' => $model4), true, false)),
                array('label' => Yii::t('main-ui', 'Ticket settings'), 'content' => $this->renderPartial('_request', array('model5' => $model5), true, false)),
                array('label' => Yii::t('main-ui', 'Appearance'), 'content' => $this->renderPartial('_appear', array('model6' => $model6), true, false)),
                array('label' => Yii::t('main-ui', 'Attachments'), 'content' => $this->renderPartial('_attach', array('model8' => $model8), true, false)),
                array('label' => Yii::t('main-ui', 'License'), 'content' => $this->renderPartial('_lic', array('model7' => $model7), true, true)),
            ),
        )
    ); ?>

</div>