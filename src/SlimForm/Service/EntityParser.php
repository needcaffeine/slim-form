<?php

namespace SlimForm\Service;
use SlimForm\Annotation\Element;
use SlimForm\Element\Element as FormElement;

/**
 * Consumes a Doctrine model and parses it into a Form element
 * Class EntityParser
 * @package SlimForm\Service
 */
class EntityParser {
    /**
     * @var EntityParser
     */
    private static $_instance;

    /**
     * @return EntityParser
     */
    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new EntityParser();
        }
        return self::$_instance;
    }

    public function modelToElement($model) {
        return $this->createFormElement($this->parseModel($model), $model);
    }

    /**
     * Convert a model into a connected graph of annotation objects
     * to be consumed by the createFormElement
     * @param $model
     * @return \SlimForm\Annotation\Form
     */
    public function parseModel($model) {
        $reader = new \SlimForm\Reader($model);
        // get entity's class annotation
        /* @var $form \SlimForm\Annotation\Form */
        $form = $reader->getClassAnnotation();
        // register all form row annotations with their respective names (or generate them)
        $rows = array();
        $rowCount = 0;
        /* @var \SlimForm\Annotation\Row $row */
        foreach ($form->rows as $row) {
            $index = empty($row->name) ? "row{$rowCount}" : $row->name;
            $rows[$index] = $row;
            $rowCount++;
        }
        // get entity's property annotations and attach them as child of form or row
        $extras = array();
        /* @var \SlimForm\Annotation\RowElement $rowElement */
        $parsedProperties = $reader->getPropertyAnnotations();
        foreach ($parsedProperties as $parsedProperty) {
            $rowElement = $this->_setAnnotationInformationExchangers($parsedProperty['annotation'], $parsedProperty['property']);
            if ($rowElement->rowName !== null) {
                // row is referenced by element, but no such row exists.
                // creating Just In Time
                if (!isset($rows[$rowElement->rowName])) {
                    $jitRow = new \SlimForm\Annotation\Row();
                    $jitRow->name = $rowElement->rowName;
                    $rows[$rowElement->rowName] = $jitRow;
                }
                $rows[$rowElement->rowName]->children[] = $rowElement;
            } else {
                $extras[] = $rowElement;
            }
        }
        // attach all rows and children to form
        $form->children = array_merge($form->children, $rows, $extras);
        return $form;
    }

    protected function _setAnnotationInformationExchangers(\SlimForm\Annotation\RowElement $element, \ReflectionProperty $property) {
        $property->setAccessible(true);
        if (empty($element->importer)) {
            $element->importer = array($property, 'getValue');
        }
        if (empty($element->exporter)) {
            $element->exporter = array($property, 'setValue');
        }
        return $element;
    }

    /**
     * Consumes an
     * @param Element $element
     * @param $model
     * @return Element
     * @throws \InvalidArgumentException
     */
    public function createFormElement(Element $element, $model, FormElement $root = null) {
        $className = '\\SlimForm\\Element\\'.ucfirst($element->className);
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Unable to instantiate the class: '.$className);
        }
        /* @var $e FormElement */
        $e = new $className($element->name, $element->config);
        if ($root === null) {
            $root = $e;
        }
        $e->setEntity($model)
            ->mergeAttributes($element->attributes)
            ->setClass($element->class)
            ->setId($element->id)
            ->setExportCallable($element->exporter)
            ->setImportCallable($element->importer)
            ->setLabel($element->label)
            ->setValidators($element->validators)
            ->setRoot($root);
        /* @var \SlimForm\Annotation\Element $childAnnotation */
        foreach ($element->children as $childAnnotation) {
            $e->addChild($this->createFormElement($childAnnotation, $model, $root));
        }
        return $e;
    }


}