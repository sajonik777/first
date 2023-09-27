<?php

/**
 * Class SyncusersCommand
 */
class SyncusersCommand extends CConsoleCommand
{
    /**
     * @param array $args
     * @return int|void
     * @throws adLDAPException
     * @throws Exception
     */
    public function run($args)
    {
        if (true === YII_DEBUG) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }

        $dataList = $this->loadData();

        if (0 === count($dataList)) {
            return;
        }

        $oldap = false;

        foreach ($dataList as $fName) {
            $content = file_get_contents($fName);
            $arr1 = unserialize(base64_decode($content));
            if ('openldap' === $arr1['type']) {
                $ad = [
                    'type' => $arr1['type'],
                    'ad_enabled' => 1,
                    'host' => $arr1['host'],
                    'account' => $arr1['account'],
                    'password' => $arr1['password'],
                    'baseDN' => $arr1['baseDN'],
                    'usersDN' => $arr1['usersDN'],
                    'groupsDN' => $arr1['groupsDN'],
                    'accountSuffix' => $arr1['accountSuffix'],
                    'fastAuth' => 0,
                ];
                $ldap = new OpenLdapComponent($ad);
                $ldap->type = 'openldap';
                $ldap->ad_enabled = 1;
                $ldap->host = $arr1['host'];
                $ldap->account = $arr1['account'];
                $ldap->password = $arr1['password'];
                $ldap->baseDN = $arr1['baseDN'];
                $ldap->usersDN = $arr1['usersDN'];
                $ldap->groupsDN = $arr1['groupsDN'];
                $ldap->accountSuffix = $arr1['accountSuffix'];
                $ldap->fastAuth = 0;
            } else {
                $ad = [
                    'ad_enabled' => $arr1['ad_enabled'],
                    'baseDn' => $arr1['basedn'],
                    'accountSuffix' => $arr1['accountSuffix'],
                    'domainControllers' => [$arr1['domaincontrollers']],
                    'adminUsername' => $arr1['adminusername'],
                    'adminPassword' => $arr1['adminpassword'],
                ];
                $ldap = new LdapComponent();
                $ldap->type = 'ad';
                $ldap->ad_enabled = 1;
                $ldap->baseDn = $arr1['basedn'];
                $ldap->accountSuffix = $arr1['accountSuffix'];
                $ldap->domainControllers = [$arr1['domaincontrollers']];
                $ldap->adminUsername = $arr1['adminusername'];
                $ldap->adminPassword = $arr1['adminpassword'];
            }

            $ldap->init();
            $ldap->connect();

            $roles = Roles::model()->all();

            foreach ($roles as $role => $value) {
                $users = $ldap->group()->members($role, true);
                if (isset($users) AND $users !== null) {
                    foreach ($users as $user) {
                        $username = CUsers::model()->findByAttributes(['Username' => strtolower($user)]);
                        $cyr = preg_match('/[А-Яа-я]/', $user);
                        if (!isset($username) AND $user !== null AND $cyr !== 1) {
                            if ('openldap' === $arr1['type']) {
                                $oldap = true;
                                $ldapUserInfo = $ldap->user()->infoCollection($user, [
                                    'mail',
                                    'displayname',
                                    'telephonenumber',
                                    'postaladdress',
                                    'employeenumber',
                                    'departmentnumber',
                                    'title',
                                    'manager',
                                    'physicaldeliveryofficename',
                                    'mobile',
                                ]);
                            } else {
                                $ldapUserInfo = $ldap->user()->infoCollection($user, [
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
                            $acc_enabled = $ldapUserInfo->useraccountcontrol;
                            $enabled = ($acc_enabled == 514 OR $acc_enabled == 66050) ? 0 : 1;
                            if($enabled == 1){
                                $dbUser = new CUsers;
                                preg_match('|CN=(.+?),OU|sei', $ldapUserInfo->manager, $arr);
                                $manager_name = $arr[1];
                                $dbUser->Username = $user;
                                $dbUser->Password = md5('mdy65wtc76' . '!1qazxcv');
                                $dbUser->fullname = trim($ldapUserInfo->displayname);
                                $dbUser->Email = $ldapUserInfo->mail;
                                $dbUser->Phone = $oldap ? $ldapUserInfo->telephonenumber : $ldapUserInfo->telephoneNumber;
                                $dbUser->intphone = $oldap ? $ldapUserInfo->mobile : $ldapUserInfo->ipPhone;
                                $dbUser->position = $ldapUserInfo->title;
                                $dbUser->department = $oldap ? $ldapUserInfo->departmentnumber : $ldapUserInfo->department;
                                $dbUser->role = $role;
                                $dbUser->umanager = $manager_name;
                                $dbUser->room = $oldap ? $ldapUserInfo->physicaldeliveryofficename : $ldapUserInfo->physicalDeliveryOfficeName;
                                $dbUser->company = $oldap ? $ldapUserInfo->employeenumber : $ldapUserInfo->company;
                                $dbUser->sendmail = $ldapUserInfo->mail ? 1 : 0;
                                $dbUser->sendsms = 0;
                                $dbUser->lang = Yii::app()->params['languages'];
                                $dbUser->city = $ldapUserInfo->l;
                                $dbUser->mobile = $ldapUserInfo->mobile;
                                //$dbUser->Phone = $ldapUserInfo->homePhone;

                                if ($dbUser->save(false)) {
                                    Yii::log('User ' . $user . ' successfully imported from AD', 'info', 'USER_IMPORT');
                                    if (!$oldap && !empty($ldapUserInfo->thumbnailPhoto)) {
                                        $dbUser->refresh();
                                        $img = imagecreatefromstring($ldapUserInfo->thumbnailPhoto);
                                        $photo = imagepng($img, __DIR__ . '/../../media/userphoto/' . $dbUser->id . '.png');
                                        CUsers::model()->updateByPk($dbUser->id, [
                                            'photo' => (int)$photo,
                                        ]);
                                    }
                                }
                            }
                        } else {
                            if ('openldap' === $arr1['type']) {
                                $oldap = true;
                                $ldapUserInfo = $ldap->user()->infoCollection($user, [
                                    'mail',
                                    'displayname',
                                    'telephonenumber',
                                    'postaladdress',
                                    'employeenumber',
                                    'departmentnumber',
                                    'title',
                                    'manager',
                                    'physicaldeliveryofficename',
                                    'mobile',
                                ]);
                            } else {
                                $ldapUserInfo = $ldap->user()->infoCollection($user, [
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

                            $photo = false;
                            if (!$oldap && !empty($ldapUserInfo->thumbnailPhoto)) {
                                $img = imagecreatefromstring($ldapUserInfo->thumbnailPhoto);
                                $photo = imagepng($img, __DIR__ . '/../../media/userphoto/' . $username->id . '.png');
                            }

                            preg_match('|CN=(.+?),OU|sei', $ldapUserInfo->manager, $arr);
                            $manager_name = $arr[1];
                            $role_name = Roles::model()->findByAttributes(['value' => $role]);
                            $acc_enabled = $ldapUserInfo->useraccountcontrol;
                            $enabled = ($acc_enabled == 514 OR $acc_enabled == 66050) ? 0 : 1;
                            CUsers::model()->updateByPk($username->id, [
                                'active' => $enabled,
                                'fullname' => trim($ldapUserInfo->displayname),
                                'Email' => $ldapUserInfo->mail,
                                'Phone' => $oldap ? $ldapUserInfo->telephonenumber : $ldapUserInfo->telephoneNumber,
                                'intphone' => $oldap ? $ldapUserInfo->mobile : $ldapUserInfo->ipPhone,
                                'position' => $ldapUserInfo->title,
                                'department' => $oldap ? $ldapUserInfo->departmentnumber : $ldapUserInfo->department,
                                'umanager' => $manager_name,
                                'room' => $oldap ? $ldapUserInfo->physicaldeliveryofficename : $ldapUserInfo->physicalDeliveryOfficeName,
                                'role' => $role,
                                'role_name' => $role_name->name,
                                'company' => $oldap ? $ldapUserInfo->employeenumber : $ldapUserInfo->company,
                                'photo' => (int)$photo,
                                'city' => $ldapUserInfo->l,
                                'mobile' => $ldapUserInfo->mobile,
                                'sendmail' => $ldapUserInfo->mail ? 1 : 0,
//                                'Phone' => $ldapUserInfo->homePhone,
                            ]);
                            //Yii::log('User '.$username->Username.' successfully updated from AD', 'info', 'USER_IMPORT');
                        }
                    }
                }
            }

        }

    }

    /**
     * @return array
     */
    private function loadData()
    {
        $configDirPath = __DIR__ . '/../config/';
        $mask = $configDirPath . 'ad*.inc';
        $dataList = [];

        foreach (glob($mask) as $filename) {
            $content = file_get_contents($filename);
            $confArr = unserialize(base64_decode($content));
            $confArr['fileName'] = $filename;
            if (1 === (int)$confArr['ad_enabled']) {
                $dataList[] = $filename;
            }
        }

        return $dataList;
    }
}