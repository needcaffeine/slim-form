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
        if (!in_array($slimFormTemplatePath, $view->twigTemplateDirs)) {
            /**
             * Add this library's template directory to the view's search paths for templates.
             */
            $view->twigTemplateDirs[] = $slimFormTemplatePath;
            /**
             * Adding twig view extensions needed by the template files
             */
            $view->parserExtensions[] = new \SlimForm\Twig\Extension();
        }
        return $this;
    }

}