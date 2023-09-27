<?php

class UploadForm extends CFormModel
{
    public $files;

    // другие свойства

    public function rules()
    {
        return array(
            //устанавливаем правила для файла, позволяющие загружать
            // только картинки!
            array('files', 'file', 'types' => 'csv'),
        );
    }
}

?>