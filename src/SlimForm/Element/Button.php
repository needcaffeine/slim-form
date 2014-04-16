<?php
namespace SlimForm\Element;

class Button extends Element {
    public function __construct($name, $config) {
        $this->_config['noLabel'] = true;
        parent::__construct($name,$config);
        $this->_tag = 'button';
        $this->_defaultTemplateFile = 'partial/form/button.twig';
    }
}