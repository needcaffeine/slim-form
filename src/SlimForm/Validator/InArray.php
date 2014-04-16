<?php
namespace SlimForm\Validator;

use SlimForm\Element\Element;

class InArray extends AbstractValidator {
    const OPT_OPTIONS = 'options';
    const ERR_NOT_MEMBER = 'errNotMember';

    protected $_errorMessages = array(self::ERR_NOT_MEMBER => '"%s" is not a valid selection');
    public function __construct(Element $element, $options = array()) {
        parent::__construct($element, $options);
        if (!isset($options[self::OPT_OPTIONS])) {
            throw new \InvalidArgumentException('The InArray validator requires you to specify ');
        }
    }


    public function validate() {
        if (isset($this->_options[self::OPT_OPTIONS][$this->_element->getValue()])) {
            return true;
        }
        $this->_errorCode = self::ERR_NOT_MEMBER;
        return false;
    }
}