<?php
namespace SlimForm\View;

class TemplateMap {
    private static $_instance;

    protected $_templates;

    private function __construct() {
        $this->_templates = array();
        $this->addTemplatePath(realpath(implode(DIRECTORY_SEPARATOR, array(__DIR__, '..','..','templates'))));
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new TemplateMap();
        }
        return self::$_instance;
    }

    public function addTemplatePath($path) {
        if (!in_array($path, $this->_templates)) {
            $this->_templates[] = $path;
        }
        return $this;
    }

    public function getTemplatePaths() {
        return $this->_templates;
    }
}