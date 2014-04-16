<?php

namespace SlimForm\Twig\Extension;

class Filters {

    private static $_instance;

    public function __construct() {

    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new Filters();
        }
        return self::$_instance;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('formElement', array($this, 'renderFormElement'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('e2f', array($this, 'convertEntity')),
            new \Twig_SimpleFilter('entityForm', array($this, 'renderEntity'), array('is_safe' => array('html'))),
        );
    }


    public function renderFormElement(\SlimForm\Element\Element $form) {
        return $form->render();
    }

    public function convertEntity($entity) {
        $parser = new \SlimForm\Service\EntityParser();
        return $parser->modelToElement($entity);
    }

    public function renderEntity($entity) {
        return $this->renderFormElement($this->convertEntity($entity));
    }

}
