<?php

class CronReqCommand extends CConsoleCommand
{
    public function run($args)
    {
        if (YII_DEBUG == true) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        date_default_timezone_set(Yii::app()->params['timezone']);
        $criteria = new CDbCriteria;
        $now = date('Y-m-d H:i:s');
        $criteria->condition = 'enabled = 1';
        $criteria->addCondition('Date < "' . $now . '" ');
        $criteria->addCondition('Date_end > "' . $now . '" ');

        $cronReqs = CronReq::model()->findAll($criteria);

        if (!empty($cronReqs)) {
            foreach ($cronReqs as $cronReq) {
                /** @var $cronReq CronReq */

                //echo $cronReq->Name . "\r\n";

                /** @var MailRequest */
                $newReq = new MailRequest();
                $newReq->attachEventHandler('onAfterSave', ['JiraTicket', 'createJiraTicket']);
                $newReq->channel = 'Planned';
                $newReq->channel_icon = 'fa-regular fa-calendar-days';

                // Start copy attributes
                $newReq->service_id = $cronReq->service_id;
                $newReq->CUsers_id = $cronReq->CUsers_id;
                $newReq->Status = $cronReq->Status;
                $newReq->sla = $cronReq->sla;
                $newReq->ZayavCategory_id = $cronReq->ZayavCategory_id;
                $newReq->Priority = $cronReq->Priority;
                $newReq->Name = $cronReq->Name;
                $newReq->Content = $cronReq->Content;
                if (!empty($cronReq->watchers))
                    $newReq->watchers = $cronReq->watchers;
                if (!empty($cronReq->cunits))
                    $newReq->cunits = $cronReq->cunits;
                // End copy attributes

                /** @var CUsers */
                $user = CUsers::model()->findByAttributes(array('Username' => $newReq->CUsers_id));
                $newReq->fullname = $user->fullname;
                $newReq->creator = $user->fullname;
                $newReq->company = $user->company ? $user->company : NULL;
                $address = Companies::model()->findByAttributes(['name' => $user->company]);
                $newReq->company_id = $address->id;
                $newReq->depart = $user->department;
                $depart = Depart::model()->findByAttributes(['name' => $user->department, 'company' => $user->company]);
                $newReq->depart_id = $depart->id;
                $newReq->room = $user->room ? $user->room : NULL;
                $newReq->phone = $user->Phone ? $user->Phone : NULL;

                /** @var Service */
                $service = Service::model()->findByPk($cronReq->service_id);
                $newReq->service_name = $service->name ? $service->name : NULL;

                /** @var CUsers */
                if(isset($service['manager']) AND !empty($service['manager'] AND empty($service['group']))){
                    $manager = CUsers::model()->findByAttributes(array('Username' => $service['manager']));
                    $newReq->mfullname = $manager->fullname ? $manager->fullname : NULL;
                    $newReq->Managers_id = $manager->Username ? $manager->Username : NULL;
                }
                if(isset($service['group']) AND !empty($service['group'])){
                    $group = Groups::model()->findByAttributes(array('name' => $service['group']));
                    $newReq->gfullname = $group->name ? $group->name : NULL;
                    $newReq->groups_id = $group->id ? $group->id : NULL;
                }

                $newReq->Date = date("d.m.Y H:i");
                $newReq->timestamp = date('Y-m-d H:i:s');

                /** @var Status */
                $status = Status::model()->findByAttributes(array('name' => $cronReq->Status));
                $newReq->slabel = $status->label;
                var_dump($newReq);
                if ($newReq->save(false)) {
                    var_dump("Save OK");
                    if (isset($cronReq->fields)){
                        var_dump('$cronReq->fields');
                        var_dump(json_decode($cronReq->fields));
                        var_dump("--------------");
                        $fields = json_decode($cronReq->fields);
                        foreach ($fields as $field) {
                                $fieldset = new RequestFields();
                                $fieldset->rid = $newReq->id;
                                $fieldset->name = $field->name;
                                $fieldset->type = $field->type;
                                $fieldset->value = $field->value;
                                $fieldset->save(false);
                        }
                    }
                    var_dump('Email::prepare($newReq->id, 1, NULL);');
                    
                    Email::prepare($newReq->id, 1, NULL);
                    var_dump("OK");
                    var_dump("--------------");

                    // Определяемся что делать дальше с плановой заявкой
                    switch ($cronReq->repeats) {
                        case 0:
                            $cronReq->enabled = 0; // Отключаем задание
                            break;

                        case 1:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+1 days")); // Переносим на завтра
                            break;

                        case 2:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+1 week")); // Переносим на неделю
                            break;

                        case 3:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+1 month")); // Переносим на месяц
                            break;

                        case 4:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+1 year")); // Переносим на год
                            break;

                        case 5:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+2 days")); // Переносим на 2 дня
                            break;

                        case 6:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+3 days")); // Переносим на 3 дня
                            break;

                        case 7:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+4 days")); // Переносим на 4 дня
                            break;

                        case 8:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+5 days")); // Переносим на 5 дней
                            break;

                        case 9:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+6 days")); // Переносим на 6 дней
                            break;

                        case 10:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+2 week")); // Переносим на 2 недели
                            break;

                        case 11:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+3 week")); // Переносим на 3 недели
                            break;

                        case 12:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+2 month")); // Переносим на 2 месяца
                            break;

                        case 13:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+3 month")); // Переносим на 3 месяца
                            break;

                        case 14:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+4 month")); // Переносим на 4 месяца
                            break;

                        case 15:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+5 month")); // Переносим на 6 месяцев
                            break;

                        case 16:
                            $cronReq->Date = date('Y-m-d H:i:s', strtotime($cronReq->Date . "+6 month")); // Переносим на 6 месяцев
                            break;

                        default:
                            $cronReq->enabled = 0; // Отключаем задание
                    }
                    $cronReq->save();


                } else {
                    var_dump("ERROR HERE");
                    /* обработчик ошибок */
                }

            } // end foreach
        }

    }
}