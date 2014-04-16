<?php

namespace SlimForm\Element;

class TextArea extends Element {
    public function __construct($name, $config) {
        parent::__construct($name, $config);
        $this->_tag = 'textarea';
        $this->_defaultTemplateFile = '/slimform/partial/form/textarea.twig';
    }
}