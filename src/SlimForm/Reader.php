<?php
namespace SlimForm;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

use Doctrine\Common\Annotations\Reader as ReaderInterface;

class Reader {

    /**
     * @var \ReflectionClass
     */
    protected $_reflectionClass;

    /**
     * @var \SlimForm\Annotation\Form
     */
    protected $_classAnnotation;

    /**
     * Two dimensional array of arrays:
     *  each element is an array with
     *      'property' => \ReflectionProperty,
     *      'annotation' => \SlimForm\Annotation\RowElement
     * @var array
     */
    protected $_propertyAnnotations = array();

    /**
     * @var SimpleAnnotationReader
     */
    protected static $_reader;

    protected static $_namespaceAdded = false;

    public static function setAnnotationReader(ReaderInterface $reader) {
        if (!self::$_namespaceAdded) {
            $directory = realpath(__DIR__.DIRECTORY_SEPARATOR.'..');
            \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace('SlimForm', $directory);

            \Slim\Extras\Views\Twig::$twigTemplateDirs[] = realpath($directory . DIRECTORY_SEPARATOR . 'templates');
            self::$_namespaceAdded = true;
        }
        self::$_reader = $reader;
    }

    /**
     * @return \Doctrine\Common\Annotations\CachedReader|SimpleAnnotationReader
     */
    protected static function getAnnotationReader() {
        if (!isset(self::$_reader)) {
            throw new \InvalidArgumentException('Please set up the annotation reader.');
        }
        return self::$_reader;
    }

    public function __construct($model) {
        $reader = self::getAnnotationReader();
        $this->_reflectionClass = new \ReflectionClass($model);
        /* @var $formAnnotation \SlimForm\Annotation\Form */
        $this->_classAnnotation = $reader->getClassAnnotation($this->_reflectionClass, 'SlimForm\Annotation\Form');
        foreach ($this->_reflectionClass->getProperties() as $property) {
            $propertyAnnotation = $reader->getPropertyAnnotation($property, 'SlimForm\Annotation\RowElement');
            if (empty($propertyAnnotation)) {
                continue;
            }
            $this->_propertyAnnotations[] = array(
                'property' => $property,
                'annotation' => $propertyAnnotation,
            );
        }
    }

    /**
     * @param \SlimForm\Annotation\Form $classAnnotation
     */
    public function setClassAnnotation($classAnnotation)
    {
        $this->_classAnnotation = $classAnnotation;
        return $this;
    }

    /**
     * @return \SlimForm\Annotation\Form
     */
    public function getClassAnnotation()
    {
        return $this->_classAnnotation;
    }

    /**
     * @param array $propertyAnnotations
     */
    public function setPropertyAnnotations($propertyAnnotations)
    {
        $this->_propertyAnnotations = $propertyAnnotations;
        return $this;
    }

    /**
     * @return array
     */
    public function getPropertyAnnotations()
    {
        return $this->_propertyAnnotations;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     */
    public function setReflectionClass($reflectionClass)
    {
        $this->_reflectionClass = $reflectionClass;
        return $this;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectionClass()
    {
        return $this->_reflectionClass;
    }


}