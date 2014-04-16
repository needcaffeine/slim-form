<?php
namespace SlimForm\Validator;

class NotEmpty extends AbstractValidator {

    const ERR_EMPTY = 'errEmpty';

    protected $_errorMessages = array(self::ERR_EMPTY => 'Please provide a value');

    public function validate()
    {
        $value = $this->_element->getValue();
        if (!empty($value)) {
            return true;
        }
        $this->_errorCode = self::ERR_EMPTY;
        return false;
    }
}