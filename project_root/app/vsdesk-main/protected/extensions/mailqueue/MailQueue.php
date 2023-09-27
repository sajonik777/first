<?php

namespace yiicod\mailqueue;

use Yii;
use CMap;
use CApplicationComponent;

/**
 * Comments extension settings.
 *
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */
class MailQueue extends CApplicationComponent
{
    /**
     * @var array table settings
     */
    public $modelMap = [];

    /**
     * @var string Component name, default PhpMailer
     */
    public $mailer = null;

    /**
     * @var array components settings
     */
    public $components = [];

    /**
     * @var array
     */
    public $commandMap = [];

    public function init()
    {
        parent::init();
        //Merge main extension config with local extension config
        $config = include dirname(__FILE__).'/config/main.php';
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $this->{$key} = CMap::mergeArray($value, $this->{$key});
            } elseif (null === $this->{$key}) {
                $this->{$key} = $value;
            }
        }

        if (Yii::app() instanceof \CConsoleApplication) {
            //Merge commands map
            Yii::app()->commandMap = CMap::mergeArray($this->commandMap, Yii::app()->commandMap);
            Yii::app()->commandMap = array_filter(Yii::app()->commandMap);
        }

        Yii::import($this->modelMap['MailQueue']['alias']);
        Yii::setPathOfAlias('yiicod', realpath(dirname(__FILE__).'/..'));

        //Set components
        if (count($this->components)) {
            $exists = Yii::app()->getComponents(false);
            foreach ($this->components as $component => $params) {
                if (isset($exists[$component]) && is_object($exists[$component])) {
                    unset($this->components[$component]);
                } elseif (isset($exists[$component])) {
                    $this->components[$component] = \CMap::mergeArray($params, $exists[$component]);
                }
            }
            Yii::app()->setComponents(
                $this->components, false
            );
        }
    }
}
