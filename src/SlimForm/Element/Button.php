<?php
namespace SlimForm\Element;

class Button extends Element {
    public function __construct($name, $config) {
        $this->_config['noLabel'] = true;
        parent::__construct($name,$config);
        $this->_tag = 'button';
        $this->_defaultTemplateFile = 'slimform/partial/form/button.twig';
    }
}