<?php
namespace SlimForm\Validator;

use SlimForm\Element\Element;

abstract class AbstractValidator implements ValidatorInterface {

    const OPT_ERROR_MESSAGES = 'errors';
    const ERR_GENERAL = 'errGeneral';

    /**
     * @var array
     */
    protected $_options = array();

    /**
     * @var string
     */
    protected $_errorCode;

    /**
     * Actual array of error codes, the $_errorMessages array gets appended
     * @var array
     */
    private $_errorCodeMessages;

    /**
     * @var array
     */
    protected $_errorMessages = array();

    /**
     * The form element this validator is validating.
     * @var Element
     */
    protected $_element;

    public function __construct(Element $element, $options = array()) {
        $this->_element = $element;
        $this->_options = $options;
        if (!isset($this->_options[self::OPT_ERROR_MESSAGES])) {
            $this->_options[self::OPT_ERROR_MESSAGES] = array();
        }
    }

    public abstract function validate();

    protected final function _getErrorMessages() {
        if (!isset($this->_errorCodeMessages)) {
            $this->_errorCodeMessages = array_merge($this->_errorMessages, array(self::ERR_GENERAL => '%s is an invalid value'), $this->_options[self::OPT_ERROR_MESSAGES]);
        }
        return $this->_errorCodeMessages;
    }

    public function getCode() {
        return $this->_errorCode;
    }

    public function getMessage() {
        $messages = $this->_getErrorMessages();
        if (empty($this->_errorCode) || !isset($messages[$this->_errorCode])) {
            return null;
        }
        return sprintf($messages[$this->_errorCode], $this->_element->getValue());
    }
}