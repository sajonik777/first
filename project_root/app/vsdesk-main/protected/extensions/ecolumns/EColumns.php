<?php

/**
* EColumns class file.
*
* Allows to set column visibility and order in CGridView.
*
* @author Vitaliy Potapov <noginsk@rambler.ru>
*/

yii::import('zii.widgets.jui.CJuiSortable');

class EColumns extends CJuiSortable
{
    /**
    * array of columns with keys generated by column data (name. or class or etc)
    *
    * @var mixed
    */
    public $columns = array();

    public $storage = 'session';
    public $gridId = null;
    public $delimiter = '||';
    public $buttonApply = '<input type="submit" value="Apply" style="float: left">';
    public $buttonCancel = null;
    public $buttonReset = '<input type="button" class="reset" value="Сброс" style="float: right">';
    public $model = null; //model can be used to get attribute labels for header

    public $itemTemplate = '<li class="ui-state-default" id="{id}">{content}</li>';

    /**
    * array of column names to be fixed (not sortable)
    * @var mixed
    */
    public $fixedLeft = array();
    public $fixedRight = array();

    /**
    * weather settings stored for each user or not
    *
    * @var mixed
    */
    public $userSpecific = true;

    private $_visibleKeys = array();

    public function init()
    {
        parent::init();

        //register assets
        $cssUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.ecolumns.assets'));
        Yii::app()->getClientScript()->registerCssFile($cssUrl.'/css/ecolumns.css');

        //gridId is required
        if(empty($this->gridId)) throw new CException('You must provide gridId');

        if(is_string($this->fixedLeft)) $this->fixedLeft = array($this->fixedLeft);
        if(is_string($this->fixedRight)) $this->fixedRight = array($this->fixedRight);

        //rewriting columns with unique keys
        $columnsByKey = array();
        foreach($this->columns as $column) {
            $columnKey = $this->getColumnKey($column);
            for($i = 0; true; $i++) {
                $suffix = ($i) ? '_'.$i : '';
                $columnKey .= $suffix;
                if(!array_key_exists($columnKey, $columnsByKey)) break;
            }
            $columnsByKey[$columnKey] = $column;
        }
        $this->columns = $columnsByKey;

        //row id in storage
        $storageId = $this->gridId;
        if($this->userSpecific) $storageId .= '_'.yii::app()->user->id;

        //is form with nnew columns submitted ?
        $isSubmit = yii::app()->request->getParam($this->getRequestSubmit());
        if($isSubmit) { // load from request, and save to storage
            $this->_visibleKeys = yii::app()->request->getParam($this->getRequestParam(), array());
            $this->saveVisibleKeys($storageId);
        } else {  //load from storage
            $this->loadVisibleKeys($storageId);
        }
    }

    public function run()
    {
        //create items for JUISortable
        $widgetColumns = $this->getWidgetColumns();
        $this->items = array();
        foreach($widgetColumns as $key => $column) {
            $this->items[$key] = '<label><input type="checkbox" name="'.$this->getRequestParam().'[]" value="'.$key.'" '.(isset($column['visible']) ? 'checked' : '').'>&nbsp;'.CHtml::encode($column['header']).'</label>';
        }

        $formId = $this->gridId.'-ecolumns';

        echo CHtml::beginForm('', 'POST', array(
           'id' => $formId,
        ));

        echo CHtml::hiddenField($this->getRequestSubmit(), 1);

        parent::run();

        //submit button
        echo '<br/>';
        echo '<div>';
        if(!empty($this->buttonApply)) echo $this->buttonApply;
        if(!empty($this->buttonCancel)) echo $this->buttonCancel;
        if(!empty($this->buttonReset)) echo $this->buttonReset;
        echo '</div>';

        //submit handler
        Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$formId, "
        jQuery('#{$formId}').on('submit', function(){
            jQuery('#{$this->gridId}').yiiGridView('update', {
                data: jQuery(this).serializeArray(),
                type: 'post'
            });
            return false;
        });
        ");

        if(!empty($this->buttonReset)) {
            $defaultOrder = array();
            foreach($this->columns as $key => $column) {
                $defaultOrder[] = array('key' => $key, 'visible' => $this->isVisible($column));
            }

            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$formId.'-reset', "
                jQuery('#{$formId} .reset').on('click', function(){
                   var ul = jQuery('#{$formId} ul'),
                       defaultOrder = ".CJSON::encode($defaultOrder).",
                       buffer = jQuery('<ul>').append(ul.children().detach()),
                       el;

                   for(var i=0; i<defaultOrder.length; i++) {
                      el = buffer.children('#'+defaultOrder[i].key);
                      el.find('input').attr('checked', defaultOrder[i].visible);
                      ul.append(el);
                   }

                   ul.sortable('refresh');
                });
            ");
        }

        echo Chtml::endForm();
    }

    /**
    * returns curently visible columns
    *
    */
    public function columns()
    {
        $result = array();

        $fixed = array_merge($this->fixedLeft, $this->fixedRight);

        //left fixed columns
        foreach($this->fixedLeft as $key) {
            $this->addVisibleColumn($key, $result);
        }

        // middle sortable columns
        if($this->_visibleKeys === false) {
            //when nothing in storage
            foreach($this->columns as $key => $column) {
                if(in_array($key, $fixed)) continue;
                $this->addVisibleColumn($key, $result);
            }
        } else {
            //loaded from storage
            for($i = 0; $i < count($this->_visibleKeys); $i++) {
                $key = $this->_visibleKeys[$i];
                if(in_array($key, $fixed)) continue;
                $this->addVisibleColumn($key, $result);
            }
        }

        //right fixed columns
        foreach($this->fixedRight as $key) {
            $this->addVisibleColumn($key, $result);
        }

        return $result;
    }

    /*--------- PROTECTED SECTION -----------------*/

    /**
    * returns unique column key
    *
    * @param mixed $column
    * @return mixed
    */
    protected function getColumnKey($column)
    {
        if(!is_array($column)) {
            if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $column, $matches)) throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
            $columnKey = $matches[1];
        } elseif(!empty($column['name'])) {
            $columnKey = $column['name'];
        } elseif(!empty($column['header'])) {
            $columnKey = $column['header'];
        } elseif(!empty($column['class'])) {
            $columnKey = $column['class'];
        } else {
            $columnKey = null;
        }

        $columnKey = str_replace(' ', '', $columnKey);

        return $columnKey;
    }

    /**
    * load visible keys from specific storage
    *
    * @param mixed $id
    */
    protected function loadVisibleKeys($id)
    {
        $strKeys = false;
        switch($this->storage) {
            case 'session':
                if(isset(Yii::app()->session[$id])) {
                    $strKeys = yii::app()->session->get($id);
                }
                break;
            case 'cookie':
                if(isset(Yii::app()->request->cookies[$id])) {
                    $strKeys = yii::app()->request->cookies[$id]->value;
                }
                break;
            case 'db':
                $strKeys = yii::app()->db->createCommand('select data from tbl_columns where id = :id')->queryScalar(array(':id' => $id));
                break;
            default:
                throw new CException('Unknown storage: '.$this->storage);
        }

        if($strKeys === false) {
            //take visible keys from grid config
            $this->_visibleKeys = array();
            foreach($this->columns as $key => $column) {
                if($this->isVisible($column)) {
                    $this->_visibleKeys[] = $key;
                }
            }
        } else {
            $this->_visibleKeys = explode($this->delimiter, $strKeys);
        }
    }

    /**
    * save visible keys to storage
    *
    * @param mixed $id
    */
    protected function saveVisibleKeys($id)
    {
        $strKeys = implode($this->delimiter, $this->_visibleKeys);
        switch($this->storage) {
            case 'session':
                yii::app()->session[$id] = $strKeys;
                break;
            case 'cookie':
                $cookie = new CHttpCookie($id, $strKeys);
                $cookie->expire = time()+60*60*24*100; // 100 days
                Yii::app()->request->cookies[$id] = $cookie;
                break;
            case 'db':
                $params = array(':id' => $id);
                $exists = yii::app()->db->createCommand('select id from tbl_columns where id = :id')->queryScalar($params);
                $data = array('data' => $strKeys);
                if($exists) {
                    $criteria = new CDbCriteria(array('condition' => 'id = :id', 'params' => $params));
                    yii::app()->db->getCommandBuilder()->createUpdateCommand('tbl_columns', $data, $criteria)->execute();
                } else {
                    $data['id'] = $id;
                    yii::app()->db->getCommandBuilder()->createInsertCommand('tbl_columns', $data)->execute();
                }
                break;
            default:
                throw new CException('Unknown storage: '.$this->storage);
        }
    }

    /**
    * param name in request that store string of column names separated by delimiter
    *
    */
    protected function getRequestParam()
    {
        return $this->gridId.'-ecolumns';
    }

    /**
    * param name in request to show that new columns submitted
    *
    */
    protected function getRequestSubmit()
    {
        return $this->gridId.'-ecolumns-set';
    }

    /**
    * returns array of all available columns in format (sort visible at first):
    * columns[key] = array(
    *    'header' =>
    *    'visible' =>
    * )
    *
    *  used when manage columns
    *
    */
    protected function getWidgetColumns()
    {
        $widgetColumns = array();

        $fixed = array_merge($this->fixedLeft, $this->fixedRight);

        //list of visible columns first
        if(is_array($this->_visibleKeys)) {
            for($i = 0; $i < count($this->_visibleKeys); $i++) {
                $key = $this->_visibleKeys[$i];
                if(in_array($key, $fixed)) continue;
                if(array_key_exists($key, $this->columns)) {
                    $column = $this->columns[$key];
                    $widgetColumns[$key] = array(
                    'header' => $this->getColumnHeader($key, $column),
                    'visible' => true,
                    );
                }
            }
        }

        //list of hidden columns at the end
        foreach($this->columns as $key => $column) {
            if(in_array($key, $fixed)) continue;
            if(array_key_exists($key, $widgetColumns)) continue;
            $widgetColumns[$key] = array(
               'header' => $this->getColumnHeader($key, $column),
            );
        }

        return $widgetColumns;
    }

    /**
    * adds column to array with correct 'visible' attribute
    *
    * @param mixed $key
    * @param mixed $result
    */
    protected function addVisibleColumn($key, &$result)
    {
        if(array_key_exists($key, $this->columns)) {
            $column = $this->columns[$key];
            if(is_array($column)) {
                $column['visible'] = true;
            }
            $result[] = $column;
        }
    }

    /**
    * is column visible
    *
    * @param mixed $column
    * @return mixed
    */
    protected function isVisible($column)
    {
        return (!is_array($column) || !isset($column['visible']) || $column['visible'] === true);
    }

    /**
    * returns column header
    *
    * @param mixed $key
    * @param mixed $column
    */
    protected function getColumnHeader($key, $column)
    {
        if(is_string($column)) {
            if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/', $column, $matches)) throw new CException(Yii::t('zii','The column must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
            $name = $matches[1];
            if(isset($matches[5])) return $matches[5];  //header specified in format "Name:Type:Label"
            if($this->model instanceOf CModel) return $this->model->getAttributeLabel($name);
            return $name; //header = name
        } else {
            if(is_array($column)) {
                if(isset($column['header'])) return $column['header'];
                if(isset($column['name']) && $this->model instanceOf CModel) return $this->model->getAttributeLabel($column['name']);
            }
            return $key;
        }
    }
}
