<?php
namespace SlimForm\Annotation;
use Doctrine\ORM\Mapping\Annotation as AnnotationInterface;
/**
 * Class RowElement
 * @package SlimForm\Annotation
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class RowElement extends Element {
    /**
     * which row this element is a child of
     * @var string
     */
    public $rowName;

    public function __construct() {
        $this->class = 'form-control';
    }
}
