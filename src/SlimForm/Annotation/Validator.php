<?php

namespace SlimForm\Annotation;

use Doctrine\ORM\Mapping\Annotation as AnnotationInterface;


/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Validator {
    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    public $options = array();
}