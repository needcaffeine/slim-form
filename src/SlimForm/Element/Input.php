<?php
namespace SlimForm\Element;

class Input extends Element {
    public function __construct($name, $config) {
        parent::__construct($name, $config);
        $this->_tag = 'input';
        if (!$this->getAttribute('type')) {
            $this->setAttribute('type', 'text');
        }
        $this->_config += array('singular' => true);
    }
}