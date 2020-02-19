<?php

class AuthAdapter extends Zend_Auth_Adapter_DbTable
{
    protected $_hashPassword = null;
        
    public function setPasswordHash($function)
    {
        if ($type = gettype($function) !== "function") {
            throw new Exception("Password hash requires a function, but $type was provided");
        }
        
        $this->_hashPassword = $function;
    }

    public function authenticate()
    {
        $this->_authenticateSetup();

        $stm = $this->_zendDb->query("SELECT " . $this->_credentialColumn . " FROM " . $this->_tableName . " WHERE " .$this->_identityColumn . "=\"" . $this->_identity . "\";");
        if ($stm->execute()) {
            $row = $stm->fetchAll();

            if (count($row) > 0) {
                $password = $row[0]["password"];
                return AuthController::check_password($this->_credential, $password);
            }

            return false;
        }
        
        return false;
    }
}
