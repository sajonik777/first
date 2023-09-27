<?php

$this->breadcrumbs = [
    Yii::t('main-ui', 'Calls') => ['index'],
    $model->id,
];

$this->menu = [
    Yii::app()->user->checkAccess('listCalls') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'List Calls')]
    ] : [null],
];
?>
<div class="page-header">
    <h3><?php echo $model->uniqid; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php
        $this->widget('CallWidget', ['uniqid' => $model->uniqid]);
        ?>
        <?php $this->widget('bootstrap.widgets.TbDetailView', [
            'data' => $model,
            'attributes' => [
                'status',
                'date',
                'adate',
                'edate',
                [
                    'label' => Yii::t('main-ui', 'Кто звонил'),
                    'type' => 'raw',
                    'value' => '<a href="/cusers/'.$user->id.'">'.$model->dialer_name.'</a>',
                ],
                'dr_number',
                'dr_company',
                'dialed_name',
                'dd_number',
            ],
        ]); ?>
    </div>
    <?php if (isset($model->dialer_name)): ?>
        <?php if (!isset($model->rid)): ?>
            <div class="box-footer">
                <a class="btn btn-info"
                   href="/request/createfromcall?user=<?php echo $model->dialer; ?>&call=<?php echo $model->id; ?>"><?php echo Yii::t('main-ui',
                        'Create ticket'); ?></a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="box-footer">
            <a class="btn btn-warning" onclick="jQuery('#myModal').modal({'show':true});"
               href="javascript:void(0);"><?php echo Yii::t('main-ui', 'Create user'); ?></a>
        </div>
    <?php endif; ?>
</div>
<?php
$user = new FRegisterForm();
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'adduser-form',
    'enableAjaxValidation' => true,
    'clientOptions' => [
        'validateOnSubmit' => true,
    ],
    'action' => Yii::app()->createUrl('/cusers/fastadd', ['call' => $model->id, 'ticket' => null]),
]); ?>
<?php $this->beginWidget('bootstrap.widgets.TbModal', ['id' => 'myModal']); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Create user'); ?></h4>
</div>
<div class="modal-body">
    <?php
    echo $form->errorSummary($user); ?>
    <div class="row-fluid">
        <div class="span12">
            <?php
            echo $form->labelEx($user, 'company');
            $this->widget(
                'bootstrap.widgets.TbTypeahead',
                array(
                    'model' => $user,
                    'attribute' => 'company',
                    'options' => array(
                        'source' => Companies::model()->eall(),
                        'items' => 4,
                        'matcher' => <<<ENDL
js:function(item) {
return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
                    ),
                    'htmlOptions' => array(
                        'class' => 'span12'
                    ),
                )
            );
            echo $form->textFieldRow($user, 'fullname', array('class' => 'span12', 'onkeyup' => 'translit()')); ?>
        </div>
        <div class="row-fluid">
            <div class="span6"><?php echo $form->textFieldRow($user, 'Username', array('class' => 'span12')); ?></div>
            <div class="span6"><?php echo $form->passwordFieldRow($user, 'Password',
                    array('class' => 'span12')); ?></div>
        </div>
        <div class="row-fluid">
            <div class="span6"><?php echo $form->textFieldRow($user, 'Email', array('class' => 'span12')); ?></div>
            <div class="span6"><?php echo $form->textFieldRow($user, 'Phone',
                    array('class' => 'span12', 'value' => $model->dr_number ? $model->dr_number : null)); ?></div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('main-ui', 'Create'),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function translit() {
// Символ, на который будут заменяться все спецсимволы
        var space = '_';
// Берем значение из нужного поля и переводим в нижний регистр
        var text = $('#FRegisterForm_fullname').val().toLowerCase();

// Массив для транслитерации
        var transl = {
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
            'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
            'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
            'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
            ' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
            '#': space, '$': space, '%': space, '^': space, '&': space, '*': space,
            '(': space, ')': space, '-': space, '\=': space, '+': space, '[': space,
            ']': space, '\\': space, '|': space, '/': space, '.': space, ',': space,
            '{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
            '?': space, '<': space, '>': space, '№': space
        }

        var result = '';
        var curent_sim = '';

        for (i = 0; i < text.length; i++) {
            // Если символ найден в массиве то меняем его
            if (transl[text[i]] != undefined) {
                if (curent_sim != transl[text[i]] || curent_sim != space) {
                    result += transl[text[i]];
                    curent_sim = transl[text[i]];
                }
            }
            // Если нет, то оставляем так как есть
            else {
                result += text[i];
                curent_sim = text[i];
            }
        }

        result = TrimStr(result);

// Выводим результат 
        $('#FRegisterForm_Username').val(result);

    }

    function TrimStr(s) {
        s = s.replace(/^-/, '');
        return s.replace(/-$/, '');
    }

    // Выполняем транслитерацию при вводе текста в поле
    $(function () {
        $('#name').keyup(function () {
            translit();
            return false;
        });
    });
</script>
