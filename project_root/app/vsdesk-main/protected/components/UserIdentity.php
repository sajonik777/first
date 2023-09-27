<?php

class UserIdentity extends CUserIdentity
{
    // Будем хранить id.
    protected $_id;

    public $domain;

    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate()
    {
        if (1 == !Yii::app()->ldap_conf->ad_enabled) {
            // Производим стандартную аутентификацию, описанную в руководстве.
            $user = CUsers::model()->find('LOWER(Username)=?', array(strtolower($this->username)));
            if (($user === null) or ($user->Password !== md5('mdy65wtc76' . $this->password))) {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            } else if($user->active === '0') {
                $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            } else {
                $this->_id = $user->id;
                $this->username = $user->Username;
                $this->errorCode = self::ERROR_NONE;
            }
        } else {
            $ldap = Yii::app()->ldap;
            $usergroup = null;
            $fastAuthUser = false;
            $oldap = false;
            $result = $ldap->authenticate($this->username, $this->password);
            if (Yii::app()->ldap_conf->type == 'openldap'){
                $oldap = true;
                $ldapUserInfo = $ldap->user()->infoCollection($this->username, ["mail", "displayname", "telephonenumber", "postaladdress", "employeenumber", "departmentnumber", "title", "manager", "physicaldeliveryofficename", "mobile"]);
            }else{
                $ldapUserInfo = $ldap->user()->infoCollection($this->username, [
                    'mail',
                    'displayname',
                    'telephoneNumber',
                    'memberof',
                    'Address',
                    'company',
                    'department',
                    'title',
                    'manager',
                    'physicalDeliveryOfficeName',
                    'ipPhone',
                    'thumbnailPhoto',
                    'jpegPhoto',
                    'l',
                    'homePhone',
                    'mobile',
                ]);
            }
            $existingRoles = Yii::app()->ldap->user()->groups($this->username, true, false);

            //if(false !== $existingRoles && false !== $result){
                foreach ($existingRoles as $groupname) {
                    $roles = Roles::model()->findByAttributes(array('value' => strtolower($groupname)));
                    if ($roles) {
                        switch ($groupname) {
                            case 'univefadmin':
                                $usergroup = $groupname;
                                Yii::app()->session->add('fastAuth', 'no');
                                $fastAuthUser = false;
                                break;
                            /*$usergroup = $groupname;
                            Yii::app()->session->add('fastAuth', 'no');
                            header('Location: /');
                            //Yii::app()->request->redirect('/');
                            break;*/
                            case 'univefmanager':
                                $usergroup = $groupname;
                                Yii::app()->session->add('fastAuth', 'yes');
                                $fastAuthUser = true;
                                break;
                            /*$usergroup = $groupname;
                            Yii::app()->session->add('fastAuth', 'no');
                            header('Location: /');
                            //Yii::app()->request->redirect('/');
                            break;*/
                            case 'univefuser':
                                $usergroup = $groupname;
                                Yii::app()->session->add('fastAuth', 'yes');
                                $fastAuthUser = true;
                                break;
                            default:
                                $usergroup = $groupname;
                                Yii::app()->session->add('fastAuth', 'yes');
                                $fastAuthUser = true;
                                break;
                        }
                        break;
                    }
                }

                $this->setState('fullname', $ldapUserInfo->displayname);
                $this->setState('email', $ldapUserInfo->mail);
            //}

            if (!$result) {
                // Сквозная авторизация
                if(Yii::app()->ldap_conf->fastAuth == 1 and $fastAuthUser){
                    $user = CUsers::model()->find('LOWER(Username)=?', array(strtolower($this->username)));
                    if ($user === null) {
                        $this->errorCode = self::ERROR_USERNAME_INVALID;
                    } else {
                        $this->_id = $user->id;
                        $this->username = $user->Username;
                        $this->errorCode = self::ERROR_NONE;
                    }
                } else {
                    $user = CUsers::model()->find('LOWER(Username)=?', array(strtolower($this->username)));
                    if (($user === null) or ($user->Password !== md5('mdy65wtc76' . $this->password))) {
                        $this->errorCode = self::ERROR_USERNAME_INVALID;
                    } else {
                        $this->_id = $user->id;
                        $this->username = $user->Username;
                        $this->errorCode = self::ERROR_NONE;
                    }
                }
            } else {
                $dbUser = CUsers::model()->findByAttributes(array('Username' => $this->username));
                $acc_enabled = $ldapUserInfo->useraccountcontrol;
                $enabled = ($acc_enabled == 514 OR $acc_enabled == 66050) ? 0 : 1;
                $cyr = preg_match('/[А-Яа-я]/', $this->username);
                if (!$dbUser AND $cyr !== 1 AND $enabled == 1) {
                    $dbUser = new CUsers;
                    preg_match('|CN=(.+?),OU|sei', $ldapUserInfo->manager, $arr);
                    $manager_name = $arr[1];
                    $dbUser->Username = $this->username;
                    $dbUser->Password = $this->password;
                    $dbUser->fullname = trim($ldapUserInfo->displayname);
                    $dbUser->Email = $ldapUserInfo->mail;
                    $dbUser->Phone = $oldap ? $ldapUserInfo->telephonenumber : $ldapUserInfo->telephoneNumber;
                    $dbUser->intphone = $oldap ? $ldapUserInfo->mobile : $ldapUserInfo->ipPhone;
                    $dbUser->position = $ldapUserInfo->title;
                    $dbUser->department = $oldap ? $ldapUserInfo->departmentnumber : $ldapUserInfo->department;
                    $dbUser->role = $usergroup;
                    $dbUser->umanager = $manager_name;
                    $dbUser->room = $oldap ? $ldapUserInfo->physicaldeliveryofficename:$ldapUserInfo->physicalDeliveryOfficeName;
                    $dbUser->company = $oldap ? $ldapUserInfo->employeenumber: $ldapUserInfo->company;
                    $dbUser->sendmail = $ldapUserInfo->mail ? 1 : 0;
                    $dbUser->sendsms = 0;
                    $dbUser->lang = Yii::app()->params['languages'];
                    $dbUser->city = $ldapUserInfo->l;
                    $dbUser->mobile = $ldapUserInfo->mobile;
                    if ($dbUser->save(false)) {
                        if (!$oldap && !empty($ldapUserInfo->thumbnailPhoto)) {
                            $dbUser->refresh();
                            $img = imagecreatefromstring($ldapUserInfo->thumbnailPhoto);
                            $photo = imagepng($img, __DIR__ . '/../../media/userphoto/' . $dbUser->id . '.png');
                            CUsers::model()->updateByPk($dbUser->id, [
                                'photo' => (int)$photo,
                            ]);
                        }
                    }
                } else {
                    $photo = false;
                    if (!$oldap && !empty($ldapUserInfo->thumbnailPhoto)) {
                        $img = imagecreatefromstring($ldapUserInfo->thumbnailPhoto);
                        $photo = imagepng($img, __DIR__ . '/../../media/userphoto/' . $dbUser->id . '.png');
                    }

                    preg_match('|CN=(.+?),OU|sei', $ldapUserInfo->manager, $arr);
                    $manager_name = $arr[1];
                    $role_name = Roles::model()->findByAttributes(array('value' => $usergroup));
                    $acc_enabled = $ldapUserInfo->useraccountcontrol;
                    $enabled = ($acc_enabled == 514 OR $acc_enabled == 66050) ? 0 : 1;
                    CUsers::model()->updateByPk($dbUser->id, array(
                        'active' => $enabled,
                        'fullname' => trim($ldapUserInfo->displayname),
                        'Email' => $ldapUserInfo->mail,
                        'Phone' => $oldap ? $ldapUserInfo->telephonenumber : $ldapUserInfo->telephoneNumber,
                        'intphone' => $oldap ? $ldapUserInfo->mobile : $ldapUserInfo->ipPhone,
                        'position' => $ldapUserInfo->title,
                        'department' => $oldap ? $ldapUserInfo->departmentnumber : $ldapUserInfo->department,
                        'umanager' => $manager_name,
                        'room' => $oldap ? $ldapUserInfo->physicaldeliveryofficename:$ldapUserInfo->physicalDeliveryOfficeName,
                        'role' => $usergroup,
                        'role_name' => $role_name->name,
                        'photo' => (int)$photo,
                        'company' => $oldap ? $ldapUserInfo->employeenumber: $ldapUserInfo->company,
                        'city' => $ldapUserInfo->l,
                        'mobile' => $ldapUserInfo->mobile,
                        'sendmail' => $ldapUserInfo->mail ? 1 : 0,

                    ));
                }
                $this->_id = $dbUser->id;
                $this->errorCode = self::ERROR_NONE;
            }
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}
