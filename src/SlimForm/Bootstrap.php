<?php
namespace SlimForm;

use Doctrine\Common\Annotations\Reader as ReaderInterface;

class Bootstrap {
    private static $_instance;

    protected $_view;

    private function __construct() {

    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new Bootstrap();
        }
        return self::$_instance;
    }

    public function setAnnotationReader(ReaderInterface $reader) {
        Reader::setAnnotationReader($reader);
        return $this;
    }

    public function setView(\Slim\Views\Twig $view) {
        $slimFormTemplatePath = realpath(implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'templates')));
        $view->getInstance()->addExtension(new \SlimForm\Twig\Extension());
        $loader = $view->getInstance()->getLoader()->addPath($slimFormTemplatePath);
        $this->_view = $view;
        return $this;
    }

    public function getView() {
        if (!isset($this->_view)) {
            throw new \RuntimeException('Unable to get view before it was set in SlimForm Bootstrap.');
        }
        return $this->_view;
    }

}