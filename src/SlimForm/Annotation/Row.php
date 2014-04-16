<?php
namespace SlimForm\Annotation;
use Doctrine\ORM\Mapping\Annotation as AnnotationInterface;
use SlimForm\Factory;


/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class Row extends Element {
    public function __construct() {
        $this->className = 'Row';
        $this->class = 'form-group';
    }
}
