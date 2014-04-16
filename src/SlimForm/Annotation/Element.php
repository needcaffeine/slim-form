<?php
namespace SlimForm\Annotation;
use Doctrine\ORM\Mapping\Annotation as AnnotationInterface;
use SlimForm\Factory;


/**
 * @Annotation
 * @Target({"PROPERTY", "CLASS", "ANNOTATION"})
 */
class Element implements AnnotationInterface {

    /**
     * @var string
     */
    public $className = 'Input';

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $config = array();

    /**
     * @var array
     */
    public $attributes = array();

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $label;

    /**
     * @var array
     */
    public $validators = array();


    /**
     * @var array<SlimForm\Annotation\Element>
     */
    public $children = array();

    public $importer;

    public $exporter;


}
