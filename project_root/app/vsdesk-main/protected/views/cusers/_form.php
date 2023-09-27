<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'cusers-form',
            'enableAjaxValidation' => true,
        )); ?>
        <div>
            <?php echo $form->errorSummary($model); ?>
        </div>
        <div class="row-fluid">
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'fullname', array('maxlength' => 100, 'class' => 'span12', 'onkeyup' => 'translit()')); ?>
                <?php echo $form->textFieldRow($model, 'Username', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->passwordFieldRow($model, 'Password', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->textFieldRow($model, 'Email', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->textFieldRow($model, 'Phone', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->textFieldRow($model, 'intphone', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->textFieldRow($model, 'mobile', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->toggleButtonRow($model, 'active'); ?>
                <?php echo $form->toggleButtonRow($model, 'sendmail'); ?>
                <?php echo $form->toggleButtonRow($model, 'sendsms'); ?>

            </div>
            <div class="span6">
                <?php
                if (Yii::app()->user->checkAccess('systemManager')) {
                    echo $form->dropDownListRow($model, 'role', Roles::uall(), array('class' => 'span12'));
                } else {
                    echo $form->dropDownListRow($model, 'role', Roles::all(), array('class' => 'span12'));
                }


                echo '<div id="comorcon_ret">';
                echo '<div class="span12">';
                echo $form->select2Row($model, 'company', array(
                    'multiple' => false,
                    'data' =>  Companies::all_for_form(),
                    // 'empty'=>"",
                    // 'empty' => [''=>'--- Выберите местоположение ---'],
                    // 'selected'=>'',
                    'placeholder' => '--- Выберите местоположение ---',
                    // "selected"=>'',
                    'allowClear'=>true,
                    'options'=>array(
                        // 'tokenSeparators'=>array(','),
                        
                        'width' => '100%',
                        'asDropDownList' => true,
                        
                    ),
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('Cusers/SelectGroup'),//вызов контроллера c Ajax
                        'update' => '#dep',//id DIV - а в котором надо обновить данные
                    )
                ));
                echo '</div>';
                echo '</div>';
                echo $form->textFieldRow($model, 'city', array('maxlength' => 50, 'class' => 'span12'));

                //echo $form->dropDownListRow($model, 'department', Depart::all(), array('class' => 'span8'));
                echo '<div id="dep">';
                $comp  = Companies::all();
                // reset($comp)
                echo $form->dropDownListRow($model, 'department', Depart::call($model->company ? $model->company : null ), array('class' => 'span12'));
                echo '</div>';
                echo $form->textFieldRow($model, 'room', array('maxlength' => 50, 'class' => 'span12'));
                //echo $form->textFieldRow($model, 'umanager', array('maxlength' => 50, 'class' => 'span12'));
              echo $form->labelEx($model, 'umanager');
              $this->widget(
                      'bootstrap.widgets.TbTypeahead',
                      array(
                              'model' => $model,
                              'attribute' => 'umanager',
                              'options' => array(
                                      'source' => CUsers::model()->eall(),
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
                echo $form->textFieldRow($model, 'position', array('maxlength' => 50, 'class' => 'span12'));
                echo $form->dropDownListRow($model, 'lang', array_merge($lang, array('en' => 'English')), array('class' => 'span12'));

                ?>
            </div>
        </div>
    </div>
            <div class="row-fluid">
                <div class="box-footer">
                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
                    )); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
</div>
<script>

function translit(){
// Символ, на который будут заменяться все спецсимволы
var space = '_'; 
// Берем значение из нужного поля и переводим в нижний регистр
var text = $('#CUsers_fullname').val().toLowerCase();
     
// Массив для транслитерации
var transl = {
'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
'#': space, '$': space, '%': space, '^': space, '&': space, '*': space, 
'(': space, ')': space,'-': space, '\=': space, '+': space, '[': space, 
']': space, '\\': space, '|': space, '/': space,'.': space, ',': space,
'{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
'?': space, '<': space, '>': space, '№':space
}
                
var result = '';
var curent_sim = '';
                
for(i=0; i < text.length; i++) {
    // Если символ найден в массиве то меняем его
    if(transl[text[i]] != undefined) {
         if(curent_sim != transl[text[i]] || curent_sim != space){
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
$('#CUsers_Username').val(result); 
    
}
function TrimStr(s) {
    s = s.replace(/^-/, '');
    return s.replace(/-$/, '');
}
// Выполняем транслитерацию при вводе текста в поле
$(function(){
    $('#name').keyup(function(){
         translit();
         return false;
    });
});
</script>
