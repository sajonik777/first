<?php

$srole = Roles::model()->findAll('value=:role_id', array(':role_id' => $user->role));
foreach ($srole as $srl) {
    $role = $srl->name;
}
; ?>
<?php if ($user->sendmail == 1) {
    $sendmail = 'Да';
} else {
    $sendmail = 'Нет';
}
;
if ($user->sendsms == 1) {
    $sendsms = 'Да';
} else {
    $sendsms = 'Нет';
}
; ?>
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
    'data' => $user,
    'type' => 'striped bordered condensed',
    'attributes' => array_filter([
        $user->photo ? [
            'label' => Yii::t('main-ui', 'Photo'),
            'type' => 'raw',
            'value' => "<img src='/media/userphoto/{$user->id}.png'>",
        ] : null,
        [
            'label' => Yii::t('main-ui', 'Fullname'),
            'type' => 'raw',
            'value' => '<a href="/cusers/'.$user->id.'">'.$user->fullname.'</a>',
        ],
        'Username',
        'company',
        $user->Email ? 'Email' :NULL,
        $user->Phone ? [
            'label' => Yii::t('main-ui', 'Phone'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $user->Phone .  ' <a onClick="call(' . $user->Phone . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$user->Phone.'" >'.$user->Phone.'</a>',
        ] : null,
        $user->intphone ? [
            'label' => Yii::t('main-ui', 'Internal phone'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $user->intphone .  ' <a onClick="call(' . $user->intphone . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$user->intphone.'" >'.$user->intphone.'</a>',
        ] : null,
        $user->mobile ? [
            'label' => Yii::t('main-ui', 'Mobile'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $user->mobile .  ' <a onClick="call(' . $user->mobile . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$user->mobile.'" >'.$user->mobile.'</a>',
        ] : null,
        $user->department ? 'department' : NULL,
        $user->position ? 'position' : NULL,
        $user->room ? 'room' : NULL,
        $user->umanager ? 'umanager' : NULL,
        [
            'label' => Yii::t('main-ui', 'Language'),
            'type' => 'raw',
            'value' => Yii::t('main-ui', $user->lang),
        ],
        [
            'label' => Yii::t('main-ui', 'Role'),
            'type' => 'raw',
            'value' => $role,
        ],
        [
            'label' => Yii::t('main-ui', 'Email notification'),
            'type' => 'raw',
            'value' => $sendmail,
        ],
        [
            'label' => Yii::t('main-ui', 'SMS notification'),
            'type' => 'raw',
            'value' => $sendsms,
        ],
    ]),
]); ?>
