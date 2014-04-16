<?php
namespace SlimForm\Validator;

class Number extends AbstractValidator {

    const ERR_NOT_NUMERIC = 'errNotNumeric';
    protected $_errorMessages = array(self::ERR_NOT_NUMERIC => 'Please enter a number');
    public function validate() {
        if (is_numeric($this->_element->getValue())) {
            return true;
        }
        $this->_errorCode = self::ERR_NOT_NUMERIC;
        return false;
    }
}