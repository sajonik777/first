<?php

if (isset($fields) AND $fields) {
    foreach ($fields as $field) {
      $required = $field->req ? ' <span class="required"> *</span>' : '';
        if ($field->type == 'textFieldRow') {
            echo '<div class="row-fluid"><label for="FieldsetsFields_' . $field->id . '">' . $field->name . $required . '</label>
            <input class="span12" maxlength="500" name="Request[' . $field->id . ']" id="Request_' . $field->id . '" type="text" value="' . $field->value . '"></div>';
        }
        if ($field->type == 'date') {
            $model2 = new Requestfields;
            echo '<div class="row-fluid">';
            echo '<label for="Request_' . $field->id . '">' . $field->name . $required . '</label>';
            echo '<div class="dtpicker2">';
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => "Request[" . $field->id . "]",
                'value' => $field->value,
                'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                'defaultOptions' => array(
                    'dateFormat' => 'dd.mm.yy',
                    'showButtonPanel' => true,
                    'changeYear' => true,
                )
            ));
            echo '</div>';
            echo '</div>';
            echo '<script>
                    jQuery.datepicker.setDefaults({\'dateFormat\':\'dd.mm.yy\',\'showButtonPanel\':true,\'changeYear\':true});
                    jQuery(\'#Request_' . $field->id . '\').datepicker([]);
                </script>';
        }
        if ($field->type == 'toggle') {
            echo '<div class="row-fluid">';
            echo '<label for="Request_' . $field->id . '">' . $field->name . $required . '</label>';
            $this->widget(
                'bootstrap.widgets.TbToggleButton',
                array('name' => "Request[" . $field->id . "]", 'value' => $field->value)
            );
            echo '</div>';

        }
        if ($field->type == 'ruler') {
            echo '<hr>';

        }
        if ($field->type == 'select') {
            $field_names = FieldsetsFields::model()->findByAttributes(array('id' => $field->fid));
            $list = explode(",", $field_names->value);
            $arr = array();
            foreach ($list as $value) {
                $arr[$value] = $value;
            }
            echo '<div class="row-fluid">';
            echo '<label for="Request_' . $field->name . '">' . $field->name . $required .'</label>';
            echo CHtml::DropDownList("Request[" . $field->id . "]", $field->value,
                [NULL => Yii::t('main-ui', 'Select item')]+$arr,
                array(
                    'name' => "Request[" . $field->id . "]",
                    'class' => 'span12',
                ));
            echo '</div>';
        }
    }
} ?>
