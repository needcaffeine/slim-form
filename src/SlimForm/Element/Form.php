<?php

namespace SlimForm\Element;

class Form extends Element {

    public function __construct($name, $config) {
        parent::__construct($name, $config);
        $this->setTag('form');
        $this->_attributes['role'] = 'form';
    }
}