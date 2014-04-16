<?php
namespace SlimForm\Annotation;
use Doctrine\ORM\Mapping\Annotation as AnnotationInterface;

/**
 * @Annotation
 * @Target({"PROPERTY", "CLASS"})
 */
class Form extends Element {
    /**
     * @var array<SlimForm\Annotation\Row>
     */
    public $rows = array();

    public function __construct() {
        $this->className = 'Form';
        $this->class = 'form-horizontal';
    }

}

