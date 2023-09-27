<?php

$srole = Roles::model()->findAll('value=:role_id', array(':role_id' => $model->role)); ?>
<?php
foreach ($srole as $srl) {
    $role = $srl->name;
}; ?>
<?php if ($model->sendmail == 1) {
    $sendmail = 'Да';
} else {
    $sendmail = 'Нет';
};
if ($model->sendsms == 1) {
    $sendsms = 'Да';
} else {
    $sendsms = 'Нет';
}; ?>
<?php
//Yii::app()->clientScript->registerScript('call', '
//function call(phone){
//    alert(phone);
//}
//');
//?>
<script>
    function call(phone){
        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        $.ajax({
            type: "POST",
            url: "/cusers/call",
            data: {"number": phone, "YII_CSRF_TOKEN": csrf},
            dataType: "text",
            cache: false,
            error: function (e) {
                console.log(e);
            },
            success: function (data) {
                console.log(data);
            }
        });
    }
</script>
<?php
$enabled = Asterisk::isEnabled();
$canCall = Yii::app()->user->checkAccess('amiCalls');
?>
<?php $this->widget('bootstrap.widgets.TbDetailView', [
    'data' => $model,
    'type' => 'striped bordered condensed',
    'attributes' => array_filter([
//        array(
//            'label' => Yii::t('main-ui', 'Active'),
//            'type' => 'raw',
//            'value' => $model->active == "1" ? "Да" : "Нет",
//        ),
        [
            'label' => Yii::t('main-ui', 'Photo'),
            'type' => 'raw',
            'value' => $model->photo ? "<img src='/media/userphoto/{$model->id}.png'>" : "<img src='/images/no_avatar.png'>",
        ],
        'fullname',
//        'Username',
        'company',
        $model->Email ? 'Email' : null,
        $model->Phone ? [
            'label' => Yii::t('main-ui', 'Phone'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $model->Phone .  ' <a onClick="call(' . $model->Phone . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$model->Phone.'" >'.$model->Phone.'</a>',
        ] : null,
        $model->intphone ? [
            'label' => Yii::t('main-ui', 'Internal phone'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $model->intphone .  ' <a onClick="call(' . $model->intphone . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$model->intphone.'" >'.$model->intphone.'</a>',
        ] : null,
//        $model->Phone ? 'Phone' :NULL,
        $model->mobile ? [
            'label' => Yii::t('main-ui', 'Mobile'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $model->mobile .  ' <a onClick="call(' . $model->mobile . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$model->mobile.'" >'.$model->mobile.'</a>',
        ] : null,
//        $model->intphone ? 'intphone' :NULL,
        $model->department ? 'department' : null,
        $model->position ? 'position' : null,
        $model->room ? 'room' : null,
        $model->umanager ? 'umanager' : null,
//        array(
//            'label' => Yii::t('main-ui', 'Language'),
//            'type' => 'raw',
//            'value' => Yii::t('main-ui', $model->lang),
//        ),
        [
            'label' => Yii::t('main-ui', 'Role'),
            'type' => 'raw',
            'value' => $role,
        ],
    ]),
]);

