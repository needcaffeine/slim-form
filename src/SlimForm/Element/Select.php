<?php

namespace SlimForm\Element;

class Select extends Element {

    protected $_options;

    protected $_defaultTemplateFile = '/slimform/partial/form/select.twig';
    public function __construct($name, $config) {
        parent::__construct($name, $config);
        $this->_tag = 'select';
        if (isset($config['options'])) {
            $this->setOptions($config['options']);
        }
    }

    public function setOptions(array $options) {
        $this->_options = array();
        foreach ($options as $option) {
            $this->addOption($option);
        }
        return $this;
    }

    public function addOption($option) {
        if (is_string($option)) {
            $option = array(
                'value' => $option,
                'text' => $option,
                'order' => 0,
            );
        }
        $this->_options[] = $option;
        return $this;
    }

    public function getOptionsOrdered() {
        $orderedOptions = array();
        if ($this->_options) {
            $indexed = array();
            foreach ($this->_options as $option) {
                $order = isset($option['order']) ? intval($option['order']) : 0;
                if (!isset($indexed[$order])) {
                    $indexed[$order] = array();
                }
                $indexed[$order][] = $option;
            }
            ksort($indexed, SORT_NUMERIC);
            foreach ($indexed as $options) {
                foreach ($options as $option) {
                    $orderedOptions[$option['value']] = $option['text'];
                }
            }
        }
        return $orderedOptions;
    }

    public function getOptions() {
        $options = array();
        if (isset($this->_options)) {
            foreach ($this->_options as $option) {
                $options[$option['value']] = $option['text'];
            }
        }
        return $options;
    }
}