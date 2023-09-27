<?php

class LdapAuthManager extends CPhpAuthManager {

    public function init() {
        // Иерархию ролей расположим в файле auth.php в директории config приложения
        if ($this->authFile === null) {
            $this->authFile = Yii::getPathOfAlias('application.config.auth') . '.php';
        }

        parent::init();

        // Для гостей у нас и так роль по умолчанию guest.
        if (!Yii::app()->user->isGuest) {
            // Связываем группы из AD с ролями и юзерами

            $existingRoles = $this->getRoles();
            if(!Yii::app()->user->isGuest){
            $this->assign(strtolower(Yii::app()->user->role), Yii::app()->user->id);
        }
/*            if (Yii::app()->user->groups) {
                foreach (Yii::app()->user->groups as $group){
                    if (isset($existingRoles[strtolower($group)])) {
                        $this->assign(strtolower($group), Yii::app()->user->id);
                    }
                }
            }
*/

        }
    }

}
?>