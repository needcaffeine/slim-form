<?php
namespace SlimForm\Validator;

use SlimForm\Element\Element;

class RegEx extends AbstractValidator {

    const OPT_REGEX = 'regex';

    const ERR_NO_MATCH = 'errNoMatch';

    protected $_errorMessages = array(self::ERR_NO_MATCH => 'This input has an invalid format.');

    public function __construct(Element $element, $options = array()) {
        parent::__construct($element, $options);
        if (!isset($options[self::OPT_REGEX])) {
            throw new \InvalidArgumentException('The RegEx validator requires a regular expression option.');
        }
    }

    public function validate() {
        if (preg_match($this->_options[self::OPT_REGEX],$this->_element->getValue()) > 0) {
            return true;
        }
        $this->_errorCode = self::ERR_NO_MATCH;
        return false;
    }
}