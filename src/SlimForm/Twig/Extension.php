<?php
namespace SlimForm\Twig;

class Extension extends \Twig_Extension {
    public function getName()
    {
        return 'slimform';
    }

    public function getFilters() {
        return \SlimForm\Twig\Extension\Filters::getInstance()->getFilters();
    }
}