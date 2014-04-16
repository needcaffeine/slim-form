<?php
namespace SlimForm\Validator;

use SlimForm\Element\Element;

class Email extends AbstractValidator {

    const ERR_EMAIL_INVALID = 'errEmailInvalid';

    protected $_errorMessages = array(self::ERR_EMAIL_INVALID => 'The email address "%s" is invalid.');

    public function validate() {
        if (filter_var($this->_element->getValue(), FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        $this->_errorCode = self::ERR_EMAIL_INVALID;
        return false;
    }

}