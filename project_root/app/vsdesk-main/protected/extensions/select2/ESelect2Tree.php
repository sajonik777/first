<?php

/**
 * Wrapper for ivaynberg jQuery select2 (https://github.com/ivaynberg/select2)
 * 
 * @author Anggiajuang Patria <anggiaj@gmail.com>
 * @link http://git.io/Mg_a-w
 * @license http://www.opensource.org/licenses/apache2.0.php
 */
class ESelect2Tree extends CInputWidget
{

    /**
     * @var array select2 options
     */
    public $options = array();

    /**
     * @var array CHtml::dropDownList $data param
     */
    public $data = array();
    public $dataProvider;
    public $parentColumn = 'parent_id';

    /**
     * @var string html element selector
     */
    public $selector;

    /**
     * @var array javascript event handlers
     */
    public $events = array();
    
    protected $defaultOptions = array();

    private function orderData($remainingNodes, $orderedData = array())
    {
        // var_dump($remainingNodes);
        if (count($orderedData) == 0)
        {
            // add the roots
            foreach ($remainingNodes as $node)
            {
                if ($node->{$this->parentColumn} == null)
                {
                    $orderedData[] = $node;
                    
                }
            }
            

            if (count($orderedData) == 0)
            {
                throw new Exception("Error, no roots found");
            }
        }
        else
        {
            $oldOrderedData = $orderedData;
            foreach ($remainingNodes as $node)
            {
                // look for the parent
                foreach ($orderedData as $i => $insertedNode)
                {
                    if ($insertedNode->getPrimaryKey() == $node->{$this->parentColumn}
                        && in_array($insertedNode, $oldOrderedData))
                    {
                        // parent is in position $i
                        $positionToInsert = null;
                        $j = $i + 1;
                        while ($j < count($orderedData) && $positionToInsert == null)
                        {
                            $orderedNode = $orderedData[$j];
                            // if the parent is the same, proceed
                            if ($orderedNode->{$this->parentColumn} == $node->{$this->parentColumn})
                            {
                                $j++;
                            }
                            else
                            {
                                $positionToInsert = $j;
                            }
                        }

                        if ($positionToInsert != null)
                        {
                            array_splice($orderedData, $positionToInsert, 0, array($node));
                            break;
                        }
                        else
                        {
                            $orderedData[] = $node;
                            break;
                        }
                    }
                }
            }
        }
        $remainingNodes = array_udiff($remainingNodes, $orderedData, function ($nodeA, $nodeB) 
        {
            return $nodeA->getPrimaryKey() - $nodeB->getPrimaryKey();
        }
        );

        if ($remainingNodes != null)
        {
        return $this->orderData($remainingNodes, $orderedData);
        }
        else
        {
            
        return $orderedData;
        }
    }


    public function init()
    {
        parent::init();

        // parent must be followed by their children, so, order the dataProvider
        $this->dataProvider = Tcategory::model()->search();
        // $data = Tcategory::model()->search()->getData();
        $data = $this->dataProvider->getData();
        // var_dump($data);
        $newData = $this->orderData($data);
        // var_dump("$newData");
        // var_dump($newData);
        $this->dataProvider->setData($newData);
        $data = $this->dataProvider->getData();
        $this->data = $data;
        foreach ($data as $key => $value) {
            // var_dump($value["id"]);
            // var_dump("<br><br>");
        }
        // var_dump("<br><br>");
        // var_dump($data);
        // var_dump("<br><br>");

        $this->defaultOptions = array(
            'formatNoMatches' => 'js:function(){return "' . Yii::t('ESelect2Tree.select2', 'No matches found') . '";}',
            'formatInputTooShort' => 'js:function(input,min){return "' . Yii::t('ESelect2Tree.select2', 'Please enter {chars} more characters', array('{chars}' => '"+(min-input.length)+"')) . '";}',
			'formatInputTooLong' => 'js:function(input,max){return "' . Yii::t('ESelect2Tree.select2', 'Please enter {chars} less characters', array('{chars}' => '"+(input.length-max)+"')) . '";}',
            'formatSelectionTooBig' => 'js:function(limit){return "' . Yii::t('ESelect2Tree.select2', 'You can only select {count} items', array('{count}' => '"+limit+"')) . '";}',
            'formatLoadMore' => 'js:function(pageNumber){return "' . Yii::t('ESelect2Tree.select2', 'Loading more results...') . '";}',
            'formatSearching' => 'js:function(){return "' . Yii::t('ESelect2Tree.select2', 'Searching...') . '";}',
        );
    }

    public function run()
    {
        if ($this->selector == null) {
            list($this->name, $this->id) = $this->resolveNameId();
            $this->selector = '#' . $this->id;

            if (isset($this->htmlOptions['placeholder']))
                $this->options['placeholder'] = $this->htmlOptions['placeholder'];

            if (!isset($this->htmlOptions['multiple'])) {
                $data = array();
                if (isset($this->options['placeholder']))
                    $data[''] = '';
                $this->data = $data + $this->data;
            }

            if ($this->hasModel()) {
                // echo CHtml::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
                // echo 123;
            } else {
                // $this->htmlOptions['id'] = $this->id;
                // echo CHtml::dropDownList($this->name, $this->value, $this->data, $this->htmlOptions);
                $bu = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets/');
                $cs = Yii::app()->clientScript;
                $cs->registerCssFile($bu . '/select2_403.min.css');
                $cs->registerCssFile($bu . '/select2totree.css');

                if (YII_DEBUG){
                    $cs->registerScriptFile($bu . '/select2.min.js');
                    $cs->registerScriptFile($bu . '/select2totree.js');
                }else{
                    // $cs->registerScriptFile($bu . '/jquery-1.11.1.min.js');
                    $cs->registerScriptFile($bu . '/select2_403.min.js');
                    $cs->registerScriptFile($bu . '/select2totree.js');
                    // $cs->registerScriptFile($bu . '/main.js');
                }
                // var_dump($this->data);
                echo'
                <select id="sel_1" style="width:16em" multiple>
                </select>
                <script>
                    var mydata = 
                    '.json_encode($this->data).';
                    $("#sel_1").select2ToTree({treeData: {dataArr: mydata}, maximumSelectionLength: 3});
                </script>';
            }
        }

        // $bu = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets/');
        // $cs = Yii::app()->clientScript;
        // $cs->registerCssFile($bu . '/select2.css');
        // $cs->registerCssFile($bu . '/select2totree.css');

        // if (YII_DEBUG){
        //     $cs->registerScriptFile($bu . '/select2_403.min.js');
        //     $cs->registerScriptFile($bu . '/select2totree.js');
        // }
        // else{
        //     // $cs->registerScriptFile($bu . '/jquery-1.11.1.min.js');
        //     $cs->registerScriptFile($bu . '/select2_403.min.js');
        //     $cs->registerScriptFile($bu . '/select2totree.js');
        // }
        // $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
        // ob_start();
        // echo "jQuery('{$this->selector}').select2({$options})";
        // foreach ($this->events as $event => $handler)
        //     echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";

        // $cs->registerScript(__CLASS__ . '#' . $this->id, ob_get_clean() . ';');
        
    }

}
