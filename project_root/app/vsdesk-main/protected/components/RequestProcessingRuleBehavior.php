<?php

/**
 * @property Request $owner
 */
class RequestProcessingRuleBehavior extends CActiveRecordBehavior
{
    const BOT_CHANNEL = [
        'telegram',
        'viber',
        'skype',
        'facebook',
        'slack',
        'webchat',
    ];

    /**
     * @inheritDoc
     */
    public function beforeSave($event)
    {
        $rules = RequestProcessingRules::model()->findAll();
        if (empty($rules)) {
            return;
        }

        foreach ($rules as $rule) {

            if (empty($rule->conditions) || empty($rule->actions)) {
                continue;
            }

            if (!$rule->is_apply_to_bots && $this->isBot()) {
                continue;
            }

            $allCondition = 0;
            foreach ($rule->conditions as $condition) {

                $success = false;

                switch ($condition->target) {
                    case RequestProcessingRuleConditions::TARGET_SENDER:
                        if ($this->owner->CUsers_id) {
                            $user = CUsers::model()->findByAttributes(['Username' => $this->owner->CUsers_id]);
                            if ($user) {
                                $success = $this->checkCondition($user->Email, $condition->val, $condition->condition);
                            }
                        } else {
                            $success = $this->checkCondition($this->owner->fullname, $condition->val, $condition->condition);
                        }
                        break;

                    case RequestProcessingRuleConditions::TARGET_SUBJECT:
                        if (!$this->isBot()) {
                            $success = $this->checkCondition($this->owner->Name, $condition->val, $condition->condition);
                        }
                        break;

                    case RequestProcessingRuleConditions::TARGET_CONTENT:
                        $success = $this->checkCondition(strip_tags($this->owner->Content), $condition->val,
                            $condition->condition);
                        break;
                }

                if ($success) {
                    $allCondition++;
                }

            }

            if (($rule->is_all_match && $allCondition === count($rule->conditions)) || (!$rule->is_all_match && $allCondition !== 0)) {

                foreach ($rule->actions as $action) {

                    switch ($action->target) {
                        case RequestProcessingRuleActions::TARGET_STATUS:
                            $status = Status::model()->findByAttributes(['name' => $action->val]);
                            $this->owner->Status = $action->val;
                            $this->owner->slabel = $status->label;
                            break;

                        case RequestProcessingRuleActions::TARGET_PRIORITY:
                            $this->owner->Priority = $action->val;
                            break;

                        case RequestProcessingRuleActions::TARGET_CATEGORY:
                            $this->owner->ZayavCategory_id = $action->val;
                            break;

                        case RequestProcessingRuleActions::TARGET_SERVICE:
                            $service = Service::model()->findByPk($action->val);
                            $this->owner->service_name = $service->name;
                            $this->owner->service_id = $service->id;
                            break;

                        case RequestProcessingRuleActions::TARGET_COMPANY:
                            $this->owner->company = $action->val;
                            break;

                        case RequestProcessingRuleActions::TARGET_DEPARTS:
                            $depart = Depart::model()->findByAttributes(['name' => $action->val]);
                            $this->owner->depart = $action->val;
                            $this->owner->depart_id = $depart->id;
                            break;

                        case RequestProcessingRuleActions::TARGET_MANAGER:
                            $manager = CUsers::model()->findByAttributes(['Username' => $action->val]);
                            $this->owner->Managers_id = $manager->Username;
                            $this->owner->mfullname = $manager->fullname;
                            break;

                        case RequestProcessingRuleActions::TARGET_GROUP:
                            $group = Groups::model()->findByPk($action->val);
                            $this->owner->gfullname = $group->name;
                            $this->owner->groups_id = $group->id;
                            break;
                    }

                }

            }
        }

    }

    /**
     * @return bool
     */
    private function isBot()
    {
        if (empty($this->owner->channel)) {
            return false;
        }

        return in_array(mb_strtolower($this->owner->channel), self::BOT_CHANNEL, false);
    }

    /**
     * @param string $text
     * @param string $val
     * @param int $condition
     *
     * @return bool
     */
    private function checkCondition($text, $val, $condition)
    {
        switch ($condition) {
            case RequestProcessingRuleConditions::CONDITION_EQUALS:
                return $text == $val;

            case RequestProcessingRuleConditions::CONDITION_NOT_EQUALS:
                return $text != $val;

            case RequestProcessingRuleConditions::CONDITION_CONTAINS:
                return strpos($text, $val) !== false;

            case RequestProcessingRuleConditions::CONDITION_NOT_CONTAINS:
                return strpos($text, $val) === false;
        }

        return false;
    }
}
