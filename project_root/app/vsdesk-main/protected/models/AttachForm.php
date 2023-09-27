<?php

class AttachForm extends CFormModel
{
    public $extensions;
    public $duplicate_message;
    public $denied_message;
    public $max_file_size;
    public $max_file_msg;

    public
    function rules()
    {
        return array(
            array('extensions, duplicate_message, denied_message, max_file_size, max_file_msg', 'required'),
            array('extensions, duplicate_message, denied_message, max_file_size, max_file_msg', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'extensions' => Yii::t('main-ui', 'File extensions'),
            'duplicate_message' => Yii::t('main-ui', 'Duplicate message'),
            'denied_message' => Yii::t('main-ui', 'Denied message'),
            'max_file_size' => Yii::t('main-ui', 'Maximum file size (Kb)'),
            'max_file_msg' => Yii::t('main-ui', 'Maximum file size message'),
        );
    }
}

?>