<?php

class LdapUser extends CWebUser {

    protected $_groups = null;
    protected $_model;

    public function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
    }

    public function getGroups() {
        if ($this->_groups === null) {
            if ($user = $this->getModel()) {
                $this->_groups = Yii::app()->ldap->user()->groups($user->Username);
            }
        }
        return $this->_groups;
    }

    public function getModel() {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = CUsers::model()->findByPk($this->id);
        }
        return $this->_model;
    }

}
?>