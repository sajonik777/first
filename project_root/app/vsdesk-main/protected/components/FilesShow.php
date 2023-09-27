<?php

class FilesShow
{
    public function __construct()
    {
        if (ini_get('date.timezone') == '') {
            date_default_timezone_set(Yii::app()->params['timezone']);
        }
    }

    static function show($files, $url, $path, $id, $model)
    {
        $i = 0;
        echo '<div class="box-footer"><ul class="mailbox-attachments clearfix">';
        foreach ($files as $file) {
            $i = $i + 1;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fname = Yii::getPathOfAlias('webroot') . $path . $id . '/' . $file;
            if (is_dir($fname) or !file_exists($fname)) {
                continue;
            }
            $mime = finfo_file($finfo, $fname);
            $image = explode("/", $mime);
            $icon = null;
            if (isset($image) and ($image[1] == 'msword' OR $image[1] == 'rtf')) {
                $icon = 'file-word-o';
            } elseif (isset($image) and ($image[1] == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet' OR $image[1] == 'vnd.ms-office' OR $image[1] == 'vnd.ms-excel')) {
                $icon = 'file-excel-o';
            } elseif (isset($image) and $image[1] == 'pdf') {
                $icon = 'file-pdf-o';
            } elseif (isset($image) and ($image[1] == 'zip' OR $image[1] == 'x-rar-compressed' OR $image[1] == 'x-bzip2' OR $image[1] == 'x-gzip' OR $image[1] == 'x-bzip')) {
                $icon = 'file-archive-o';
            } else {
                $icon = 'file';
            }

            $file_name = false;
            if (empty($id)) {
                $fileObj = Files::model()->findByAttributes(['file_name' => $file]);
                $file_name = $fileObj->name;
            }

            if ($image[0] == 'image') {
                echo '
                            <li id="' . $fileObj->id . '">
                            <span class="mailbox-attachment-icon"><a href="' . $path . $id . '/' . $file . '" target="_blank""><i class="fa icon-image icon-60"></i></a></span>
                            <div class="mailbox-attachment-info">
                                <a href="' . $path . $id . '/' . $file . '" target="_blank" class="mailbox-attachment-name thumb"><i class="fa icon-camera"></i> ' . ($file_name ? $file_name : $file) . '<span><img src="' . $path . $id . '/' . $file . '"/></span></a>
                        <span class="mailbox-attachment-size">' . self::get_filesize($fname);
                if (Yii::app()->user->checkAccess('update' . $model) AND !Yii::app()->user->checkAccess('systemUser')) {
                    if (!empty($id)) {
                        echo CHtml::ajaxLink('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-trash"></i> </span>',
                            Yii::app()->createUrl($url . '/deletefile', array('id' => $id, 'file' => $file)), array(
                                'update' => '#' . $fileObj->id,
                                'beforeSend' => 'function() {
                                            $("#' . $fileObj->id . '").addClass("loading");
                                        }',
                                'complete' => 'function() {
                                          $("#' . $fileObj->id . '").removeClass("loading");
                                          $("#' . $fileObj->id . '").hide();
                                      }'
                            ));
                        echo CHtml::Link('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-download"></i> </span>',
                            Yii::app()->createUrl('files/download', array('file' => $file)));
                    } else {
                        echo CHtml::ajaxLink('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-trash"></i> </span>',
                            Yii::app()->createUrl('files/delete', array('file' => $file)), array(
                                'update' => '#' . $fileObj->id,
                                'beforeSend' => 'function() {
                                            $("#' . $fileObj->id . '").addClass("loading");
                                        }',
                                'complete' => 'function() {
                                          $("#' . $fileObj->id . '").removeClass("loading");
                                          $("#' . $fileObj->id . '").hide();
                                      }',
                            ));
                        echo CHtml::Link('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-download"></i> </span>',
                            Yii::app()->createUrl('files/download', array('file' => $file)));
                    }
                } else {
                    echo CHtml::Link('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-download"></i> </span>',
                        Yii::app()->createUrl('files/download', array('file' => $file)));
                }
                echo '</span></div></li>';
            } else {
                echo '
                            <li id="' . $fileObj->id . '">
                            <span class="mailbox-attachment-icon"><a href="' . $path . $id . '/' . $file . '" target="_blank" class="thumb""><i class="fa icon-' . $icon . ' icon-60"></i></a></span>
                            <div class="mailbox-attachment-info">
                                <a href="' . $path . $id . '/' . $file . '" target="_blank" class="mailbox-attachment-name"><i class="fa-solid fa-paperclip"></i> ' . ($file_name ? $file_name : $file) . '</a>
                        <span class="mailbox-attachment-size">' . self::get_filesize($fname);
                if (Yii::app()->user->checkAccess('update' . $model) AND !Yii::app()->user->checkAccess('systemUser')) {
                    if (!empty($id)) {
                        echo CHtml::ajaxLink('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-trash"></i> </span>',
                            Yii::app()->createUrl($url . '/deletefile', array('id' => $id, 'file' => $file)), array(
                                'update' => '#' . $fileObj->id,
                                'beforeSend' => 'function() {
                                            $("#' . $fileObj->id . '").addClass("loading");
                                        }',
                                'complete' => 'function() {
                                            $("#' . $fileObj->id . '").removeClass("loading");
                                            $("#' . $fileObj->id . '").hide();
                                      }'
                            ));
                        echo CHtml::Link('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-download"></i> </span>',
                            Yii::app()->createUrl('files/download', array('file' => $file)));
                    } else {
                        echo CHtml::ajaxLink('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-trash"></i> </span>',
                            Yii::app()->createUrl('files/delete', array('file' => $file)), array(
                                'update' => '#' . $fileObj->id,
                                'beforeSend' => 'function() {
                                            $("#' . $fileObj->id . '").addClass("loading");
                                        }',
                                'complete' => 'function() {
                                            $("#' . $fileObj->id . '").removeClass("loading");
                                            $("#' . $fileObj->id . '").hide();
                                      }'
                            ));
                        echo CHtml::Link('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-download"></i> </span>',
                            Yii::app()->createUrl('files/download', array('file' => $file)));
                    }
                } else {
                    echo CHtml::Link('<span class="btn btn-default btn-small pull-right"> <i class="fa-solid fa-download"></i> </span>',
                        Yii::app()->createUrl('files/download', array('file' => $file)));
                }
                echo '</span></div></li>';
            }
            finfo_close($finfo);
        }
        echo '</ul>
    </div>';
    }

    static function get_filesize($file)
    {
        // идем файл
        if (!file_exists($file)) {
            return "Файл  не найден";
        }
        // теперь определяем размер файла в несколько шагов
        $filesize = filesize($file);
        // Если размер больше 1 Кб
        if ($filesize > 1024) {
            $filesize = ($filesize / 1024);
            // Если размер файла больше Килобайта
            // то лучше отобразить его в Мегабайтах. Пересчитываем в Мб
            if ($filesize > 1024) {
                $filesize = ($filesize / 1024);
                // А уж если файл больше 1 Мегабайта, то проверяем
                // Не больше ли он 1 Гигабайта
                if ($filesize > 1024) {
                    $filesize = ($filesize / 1024);
                    $filesize = round($filesize, 1);
                    return $filesize . " GB";
                } else {
                    $filesize = round($filesize, 1);
                    return $filesize . " MB";
                }
            } else {
                $filesize = round($filesize, 1);
                return $filesize . " Kb";
            }
        } else {
            $filesize = round($filesize, 1);
            return $filesize . " b";
        }
    }

}