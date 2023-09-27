<?php

trait RequestProcessingRuleTrait
{
    protected $botChannels = [
        'telegram',
        'viber',
        'skype',
        'facebook',
        'slack',
        'webchat',
        'whatsapp',
    ];

    /**
     * @return bool
     */
    public function isBot()
    {
        if (empty($this->channel)) {
            return false;
        }

        return in_array(mb_strtolower($this->channel), $this->botChannels, false);
    }

    /**
     * @param string $text
     * @param string $val
     * @param int $condition
     *
     * @return bool
     */
    protected function checkCondition($text, $val, $condition)
    {
        switch ($condition) {
            case RequestProcessingRuleConditions::CONDITION_EQUALS:
                return $text == $val;

            case RequestProcessingRuleConditions::CONDITION_NOT_EQUALS:
                return $text != $val;

            case RequestProcessingRuleConditions::CONDITION_CONTAINS:
                return mb_stripos($text, $val) !== false;

            case RequestProcessingRuleConditions::CONDITION_NOT_CONTAINS:
                return mb_stripos($text, $val) === false;
        }

        return false;
    }
}