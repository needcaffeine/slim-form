<?php
namespace SlimForm\Validator;

class DbNotExists extends DbExists {
    const ERR_EXIST  = 'errExist';
    protected $_errorMessages = array(self::ERR_EXIST => '%s is already present in the database');
    public function validate() {
        $valid = !parent::validate();
        if ($valid) {
            $this->_errorCode = null;
            return true;
        }
        $this->_errorCode = self::ERR_EXIST;
        return false;
    }
}