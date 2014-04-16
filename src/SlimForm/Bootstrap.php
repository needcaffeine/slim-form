<?php
namespace SlimForm;

use Doctrine\Common\Annotations\Reader as ReaderInterface;

class Bootstrap {
    private static $_instance;

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
        return $this;
    }

}