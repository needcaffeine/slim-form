<?php
namespace SlimForm;

use Slim\Views\Twig as TwigView;

class View extends TwigView {

    public function setTemplatesDirectory($directory) {
        parent::setTemplatesDirectory($directory);
        \SlimForm\View\TemplateMap::getInstance()->addTemplatePath($this->templatesDirectory);
    }

    protected function render($template, $data = null) {
        foreach (\SlimForm\View\TemplateMap::getInstance()->getTemplatePaths() as $directory) {
            $this->templatesDirectory = $directory;
            if (is_file($this->getTemplatePathname($template))) {
                break;
            }
        }
        return parent::render($template, $data);
    }
}