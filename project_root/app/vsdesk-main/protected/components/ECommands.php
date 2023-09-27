<?php

class ECommands
{
    public function __construct()
    {
        if (ini_get('date.timezone') == '')
            date_default_timezone_set(Yii::app()->params['timezone']);
    }

    /**
     * @param $string
     * @param string $trim_chars
     * @return mixed
     */
    static function mb_trim($string, $trim_chars = '\s')
    {
        return preg_replace('/^[' . $trim_chars . ']*(?U)(.*)[' . $trim_chars . ']*$/u', '\\1', $string);
    }

    /**
     * @param $command
     * @param $content
     * @param string $delimiter
     * @return null|string
     */
    static function explodeText($command, $content, $delimiter = '@')
    {
        $explArr = explode($command, $content);
        if (!empty($explArr)) {
            $explArr2 = explode($delimiter, $explArr[1]);
            if (!empty($explArr2) and isset($explArr2[0])) {
                return ECommands::mb_trim($explArr2[0]);
            }
        }

        return null;
    }

    static function getCommandsInMailComment($request_id, $content, $user_id)
    {
        $commandsTemplate = [
            'status' => '@Статус:',
            'priority' => '@Приоритет:',
            'start_plan' => '@Начало работ (план):',
            'end_plan' => '@Окончание работ (план):',
            'service' => '@Сервис:',
            'manager' => '@Исполнитель:',
            'group' => '@Группа:',
            'category' => '@Категория:',
        ];

        $request = Request::model()->findByPk($request_id);
        $user = CUsers::model()->findByPk($user_id);

        $commands = [];
        foreach ($commandsTemplate as $key => $commandTemplate) {
            $ret = ECommands::explodeText($commandTemplate, $content);
            if (!empty($ret)) {
                $commands[$key] = trim($ret);
            }
        }

        foreach ($commands as $command => $value) {
            switch ($command) {
                case 'status':
                    $rolemanager = ECommands::checkRole($user->role_name, 'systemManager');
                    $roleadmin = ECommands::checkRole($user->role_name, 'systemAdmin');
                    if ($roleadmin == true OR $rolemanager == true) {
                        $status = Status::model()->findByAttributes(array('name' => $value));
                        if (!empty($status)){
                            $wstatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 2));
                            $estatus = Status::model()->findByAttributes(array('enabled' => 1, 'close' => 3));
                            if ($status->id == $estatus->id OR $status->id == $wstatus->id) {
                                $_POST['Request']['Managers_id'] = $user->Username;
                                $_POST['Request']['mfullname'] = $user->fullname;
                                ECommands::AddHistory('Изменен исполнитель: ' . '<b>' . $user->fullname . '</b>', $request_id, $user);
                            }
                            $_POST['Request']['Status'] = $value;
                            $request->attributes = $_POST['Request'];
                            $request->save();
                            ECommands::AddHistory('Изменен статус: ' . '<b>' . $status->label . '</b>', $request_id, $user);
                            unset($_POST['Request']);
                            break;
                        }
                    }else{
                        return;
                    }
                case 'category':
                    $rolemanager = ECommands::checkRole($user->role_name, 'systemManager');
                    $roleadmin = ECommands::checkRole($user->role_name, 'systemAdmin');
                    if ($roleadmin == true OR $rolemanager == true) {
                        $ret = Yii::app()->db
                            ->createCommand('SELECT id FROM zcategory WHERE name = :name')
                            ->bindParam(":name", $value, PDO::PARAM_STR)
                            ->queryScalar();
                        if ($ret !== false) {
                            Request::model()->updateByPk($request_id, ['ZayavCategory_id' => $value]);
                            ECommands::AddHistory('Изменена категория: ' . '<b>' . $value . '</b>', $request_id, $user);
                        }
                        break;
                    }else{
                        return;
                    }

                case 'priority':
                    $rolemanager = ECommands::checkRole($user->role_name, 'systemManager');
                    $roleadmin = ECommands::checkRole($user->role_name, 'systemAdmin');
                    if ($roleadmin == true OR $rolemanager == true) {
                        $_POST['Request']['Priority'] = $value;
                        $request->attributes = $_POST['Request'];
                        $request->save();
                        ECommands::AddHistory('Изменен приоритет: ' . '<b>' . $value . '</b>', $request_id, $user);
                        unset($_POST['Request']);
                        break;
                    }else{
                        return;
                    }


                case 'start_plan':
                    $rolechange = ECommands::checkRole($user->role_name, 'updateDatesRequest');
                    if ($rolechange == true) {
                        $time = strtotime($value);
                        $newDateTime = date('d.m.Y H:i', $time);
                        Request::model()->updateByPk($request_id, ['StartTime' => $newDateTime]);
                        ECommands::AddHistory('Начало работ (план) установлено в: ' . '<b>' . $newDateTime . '</b>', $request_id, $user);
                        break;
                    }else{
                        return;
                    }

                case 'end_plan':
                    $rolechange = ECommands::checkRole($user->role_name, 'updateDatesRequest');
                    if ($rolechange == true) {
                        $time = strtotime($value);
                        $newDateTime = date('d.m.Y H:i', $time);
                        Request::model()->updateByPk($request_id, ['EndTime' => $newDateTime]);
                        ECommands::AddHistory('Окончание работ (план) установлено в: '. '<b>' . $newDateTime . '</b>', $request_id, $user);
                        break;
                    }else{
                        return;
                    }

                case 'service':
                    $rolemanager = ECommands::checkRole($user->role_name, 'systemManager');
                    $roleadmin = ECommands::checkRole($user->role_name, 'systemAdmin');
                    if ($roleadmin == true OR $rolemanager == true) {
                        $ret = Yii::app()->db
                            ->createCommand('SELECT id FROM service WHERE name = :name')
                            ->bindParam(":name", $value, PDO::PARAM_STR)
                            ->queryScalar();
                        if ($ret !== false) {
                            $_POST['Request']['service_id'] = $ret;
                            $_POST['Request']['service_name'] = $value;
                            $service = Service::model()->findByPk($ret);
                            $_POST['Request']['Priority'] = $service['priority'];
                                if ($service['gtype'] == 1) {
                                    $_POST['Request']['Managers_id'] = $service['manager'];
                                } else {
                                    $_POST['Request']['gfullname'] = $service['group'];
                                    $group = Groups::model()->findByAttributes(array('name' => $service['group']));
                                    $_POST['Request']['groups_id'] = $group->id;
                                }
                            $request->attributes = $_POST['Request'];
                            $request->save();
                            ECommands::AddHistory('Изменен сервис: ' . '<b>' . $value . '</b>', $request_id, $user);
                            unset($_POST['Request']);
                        }
                        break;
                    }else{
                        return;
                    }

                case 'manager':
                    $rolechange = ECommands::checkRole($user->role_name, 'canAssignRequest');
                    if ($rolechange == true) {
                        $mfullname = CUsers::model()->findByAttributes(array('fullname' => $value));
                        if(!empty($mfullname) AND isset($mfullname)){
                            $ismanager = ECommands::checkRole($mfullname->role_name, 'systemManager');
                            $isadmin = ECommands::checkRole($mfullname->role_name, 'systemAdmin');
                            if($ismanager == true OR $isadmin == true){
                                $_POST['users'] = $mfullname->id;
                                Request::model()->updateByPk($request_id,
                                    array('Managers_id' => $value, 'mfullname' => $mfullname->fullname));
                                ECommands::AddHistory('Изменен исполнитель: ' . '<b>' . $mfullname->fullname . '</b>', $request_id, $user);
                                unset($_POST['users']);
                            }
                            break;
                        }
                    }else{
                     return;
                    }

                case 'group':
                    $rolechange = ECommands::checkRole($user->role_name, 'canAssignRequest');
                    if ($rolechange == true) {
                        $group = Groups::model()->findByAttributes(['name' => $value]);
                        if(isset($group) AND !empty($group)){
                            $_POST['groups_id'] = $group->id;
                            Request::model()->updateByPk($request_id, array(
                                'groups_id' => $group->id,
                                'gfullname' => $group->name,
                                'mfullname' => null,
                                'Managers_id' => null
                            ));
                            ECommands::AddHistory('Изменена группа исполнителей: ' . '<b>' . $group->name . '</b>', $request_id, $user);
                            unset($_POST['groups_id']);
                        }
                        break;
                    }else{
                      return;
                    }

            }
        } //foreach
    }

    /**
     * @param $content
     * @return array
     */
    static function getCommandsInMail($content)
    {
        $commandsTemplate = [
            'service' => '@Сервис:',
            'priority' => '@Приоритет:',
            'user' => '@Пользователь:',
            'name' => '@Наименование:',
            'content' => '@Описание:',
            'category' => '@Категория:',
        ];
        $fields = Yii::app()->db
            ->createCommand('SELECT `id`, CONCAT("@", `name`, ":") AS `name` FROM fieldsets_fields')
            ->queryAll();
        foreach ($fields as $row) {
            $commandsTemplate["{$row['id']}"] = $row['name'];
        }

        $commands = [];
        foreach ($commandsTemplate as $key => $commandTemplate) {
            $ret = ECommands::explodeText($commandTemplate, $content);
            if (!empty($ret)) {
                $commands[$key] = trim($ret);
            }
        }

        $attributes = [];
        foreach ($commands as $command => $value) {
            switch ($command) {
                case 'service':
                    $ret = Yii::app()->db
                        ->createCommand('SELECT id FROM service WHERE name = :name')
                        ->bindParam(":name", $value, PDO::PARAM_STR)
                        ->queryScalar();
                    if ($ret !== false) {
                        $attributes['service_id'] = $ret;
                        $attributes['service_name'] = $value;
                        $service = Service::model()->findByPk($ret);
                            $attributes['Priority'] = $service->priority;
                                if ($service->gtype == 1) {
                                    $attributes['Managers_id'] = $service->manager;
                                } else {
                                    $attributes['Managers_id'] = NULL;
                                    $attributes['mfullname'] = NULL;
                                    $attributes['gfullname'] = $service->group;
                                    $group = Groups::model()->findByAttributes(array('name' => $service->group));
                                    $attributes['groups_id'] = $group->id;
                                }
                    }
                    break;

                case 'priority':
                    $ret = Yii::app()->db
                        ->createCommand('SELECT id FROM zpriority WHERE name = :name')
                        ->bindParam(":name", $value, PDO::PARAM_STR)
                        ->queryScalar();
                    if ($ret !== false) {
                        $attributes['Priority'] = $value;
                    }
                    break;

                case 'user':
                    $ret = Yii::app()->db
                        ->createCommand('SELECT id FROM CUsers WHERE fullname = :name')
                        ->bindParam(":name", $value, PDO::PARAM_STR)
                        ->queryScalar();
                    if ($ret !== false) {
                        $attributes['CUsers_id'] = $ret;
                        $attributes['fullname'] = $value;
                    }
                    break;

                case 'name':
                    $attributes['Name'] = $value;
                    break;

                case 'content':
                    $attributes['Content'] = $value;
                    break;

                case 'category':
                    $ret = Yii::app()->db
                        ->createCommand('SELECT id FROM zcategory WHERE name = :name')
                        ->bindParam(":name", $value, PDO::PARAM_STR)
                        ->queryScalar();
                    if ($ret !== false) {
                        $attributes['ZayavCategory_id'] = $value;
                    }
                    break;

                default:
                    if (is_numeric($command)) {
                        $ret = Yii::app()->db
                            ->createCommand('SELECT id FROM fieldsets_fields WHERE id = :id')
                            ->bindParam(":id", $command, PDO::PARAM_INT)
                            ->queryScalar();
                        if ($ret !== false) {
                            $attributes['flds']["$command"] = $value;
                        }
                    }
                    break;
            }
        } //foreach

        return $attributes;
    }

    static function checkRole($rolename, $params)
    {
        $rolesrights = RolesRights::model()->findByAttributes(array('rname' => $rolename, 'name' => $params));
        if ($rolesrights['value'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    static function AddHistory($action, $id, $user)
    {
        $history = new History();
        $history->datetime = date("d.m.Y H:i");
        $history->cusers_id = $user->fullname;
        $history->zid = $id;
        $history->action = $action;
        $history->save(false);
    }

}