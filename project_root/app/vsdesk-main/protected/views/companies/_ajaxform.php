<?php
$vals = Yii::app()->session['cfields'];
if (isset($fields) AND $fields) {
    foreach ($fields as $field) {
        $value = NULL; 
        if(isset($vals)){
            foreach($vals as $key => $val){
                if($field->id == $key){
                    $value = $val;
                }
            }
        }
        
        $required = $field->req ? ' <span class="required"> *</span>' : '';
        if ($field->type == 'textFieldRow') {
            echo '<div class="row-fluid"><label for="Companies_' . $field->id . '">' . $field->name . $required .'</label>
            <input class="span12" maxlength="500" name="Companies[' . $field->id . ']" id="Companies_' . $field->id . '" type="text" value="'.$value.'"/></div>';
        }
        if ($field->type == 'date') {
            $model2 = new CompanyFields;
            echo '<div class="row-fluid">';
            echo '<label for="Companies_' . $field->id . '">' . $field->name . $required. '</label>';
            echo '<div class="dtpicker2">';
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => "Companies[" . $field->id . "]",
                'value' => $value,
                'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                'defaultOptions' => array(
                    'dateFormat' => 'dd.mm.yy',
                    'showButtonPanel' => true,
                    'changeYear' => true,
                )
            ));
            echo '</div>';
            echo '</div>';
            echo '<script type="text/javascript" src="/js/jquery.ui.datepicker-ru.js"></script>';
            echo '<script>
                    jQuery.datepicker.setDefaults({\'dateFormat\':\'dd.mm.yy\',\'showButtonPanel\':true,\'changeYear\':true});
                    jQuery(\'#Companies_' . $field->id . '\').datepicker([]);
                </script>';
        }
        if ($field->type == 'toggle') {
            echo '<div class="row-fluid">';
            echo '<label for="Companies_' . $field->id . '">' . $field->name . $required .'</label>';
            $this->widget(
                'bootstrap.widgets.TbToggleButton',
                array('name' => "Companies[" . $field->id . "]", 'value' => $value?$value:0)
            );
            echo '</div>';
            echo "
            <script>
            $('#wrapper-Companies_" . $field->id . "').toggleButtons({'onChange':$.noop,'width':100,'height':25,'animated':true,'label':{'enabled':'Да','disabled':'Нет'},'style':{'enabled':'primary'}});
            </script>
            ";
        }
        if ($field->type == 'ruler') {
            echo '<hr>';

        }
        if ($field->type == 'select') {
            $model2 = new CompanyFieldset;
            $list = explode(",", $field->value);
            $arr = array();
            $selectedValues = '';
            if(isset($value)){
                $selectedValues = array($value => Array('selected' => 'selected'));
            }
            $arr = array();
            foreach ($list as $value1) {
                $arr[$value1] = $value1;
            }
            echo '<div class="row-fluid">';
            echo '<label for="Companies_' . $field->name . '">' . $field->name . $required .'</label>';
            echo CHtml::activeDropDownList($model2, 'value',
                $arr,
                array(
                    'name' => "Companies[" . $field->id . "]",
                    'class' => 'span12',
                    'options' => $selectedValues,
                ));
            echo '</div>';
        }
    }
}