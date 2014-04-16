<?php
namespace SlimForm\Service;

use SlimForm\Element\Element;

class ValidatorFactory {

    /**
     * Searches for a class by $name. If no validator is found
     * looks for a class in the \SlimForm\Validator namespace
     * @param Element $element
     * @param $name
     * @param array $options
     * @return \SlimForm\Validator\AbstractValidator
     * @throws \InvalidArgumentException
     */
    public static function createValidator(Element $element, $name, $options = array()) {
        if (!class_exists($name)) {
            $name = '\\SlimForm\\Validator\\'.ucfirst($name);
        }
        if (!class_exists($name)) {
            throw new \InvalidArgumentException('No validator by the name '.$name.' could be found');
        }
        return new $name($element, $options);
    }
}