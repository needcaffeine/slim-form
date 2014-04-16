<?php

namespace SlimForm\Element;

use SlimForm\Service\ValidatorFactory;
use SlimForm\Validator\ValidatorInterface;

class Element {

    private static $_view;

    /**
     * The HTML element tag name
     * @var string
     */
    protected $_tag = 'element';

    /**
     * The identifier of this element
     * @var string
     */
    protected $_name;

    /**
     * The id attribute for this element
     * @var
     */
    protected $_id;

    /**
     * The class attribute for this element
     * @var
     */
    protected $_class;

    /**
     * Array of attribute name/value pairs
     * @var  array
     */
    protected $_attributes = array();
    /**
     * Indexed by a name
     * @var array
     */
    protected $_children = array();


    /**
     * Element label (if one exists)
     * @var string
     */
    protected $_label;

    /**
     * @var array
     */
    protected $_validators = array();

    /**
     * When Element is being rendered, what it will call to get value
     * @var Callable
     */
    protected $_importCallable;

    /**
     * When Element is being saved to entity, what it will call with its value
     * @var Callable
     */
    protected $_exportCallable;


    /**
     * Array of child key names to be validated (omit all others)
     * @var array
     */
    protected $_validationGroup;

    /**
     * Settings and configurations
     *  options:
     *      singular = closed tag (no children)
     *      displayOrder = order in which this appears in the form
     *      noLabel = no label element rendered
     *
     * @var array
     */
    protected $_config = array();

    /**
     * Default template file
     * @var
     */
    protected $_defaultTemplateFile = '/slimform/partial/form/element.twig';

    /**
     * @var
     */
    protected $_entity;

    /**
     * @var array
     */
    protected $_messages = array();

    /**
     * @var Element
     */
    protected $_rootElement;

    public function __construct($name, $config) {
        $this->setName($name);
        if (!empty($config)) {
            $this->_config = array_merge($this->_config, $config);
        }
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
        return $this;
    }

    public function mergeAttributes($newAttributes) {
        if (is_array($newAttributes)) {
            $this->_attributes = array_merge($this->_attributes, $newAttributes);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function getAttribute($key) {
        return isset($this->_attributes[$key]) ? $this->_attributes[$key] : null;
    }

    public function setAttribute($key, $value) {
        $this->_attributes[$key] = $value;
        return $this;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children)
    {
        $this->_children = $children;
        return $this;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    public function getChildrenOrdered() {
        $orderedArray = array();
        /* @var $child Element */
        foreach ($this->_children as $child) {
            $order = intval($child->config('displayOrder'));
            if (!isset($orderedArray[$order])) {
                $orderedArray[$order] = array();
            }
            $orderedArray[$order][] = $child;
        }
        ksort($orderedArray, SORT_NUMERIC);
        $finalOrder = array();
        foreach ($orderedArray as $elements) {
            $finalOrder = array_merge($finalOrder, $elements);
        }
        return $finalOrder;
    }

    public function hasChild($childName) {
        return isset($this->_children[$childName]);
    }

    public function countChildren() {
        return count($this->_children);
    }

    public function addChild(Element $child) {
        $this->_children[$child->getName()] = $child;
        return $this;
    }

    public function removeChild($name) {
        unset($this->_children[$name]);
        return $this;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->_class = $class;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->_tag = $tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->_tag;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    public function setValue($value) {
        if (isset($this->_exportCallable) && is_callable($this->_exportCallable)) {
            call_user_func($this->_exportCallable, $this->getEntity(), $value);
        }
        return $this;
    }

    public function getValue() {
        if (isset($this->_importCallable) && is_callable($this->_importCallable)) {
            return call_user_func($this->_importCallable, $this->getEntity());
        }
        return null;
    }


    /**
     * @return \Slim\Views\Twig
     */
    protected function _getView() {
        if (empty(self::$_view)) {
            self::$_view = new \Slim\Views\Twig();
            $config = require 'config.php';
            self::$_view->parserOptions = $config['twig'];
            self::$_view->parserExtensions = array(
                new \Slim\Views\TwigExtension(),
                new \App\Twig\Extension\App(),
            );
            self::$_view->setTemplatesDirectory($config['slim']['templates.path']);
        }
        return self::$_view;
    }

    public function render() {
        $view = $this->_getView();
        $view->clear();
        $view->set('element', $this);
        $templateFile = $this->config('template') ? $this->config('template') : $this->_defaultTemplateFile;
        $beforeTemplateFile = $this->config('beforeTemplate');
        $afterTemplateFile = $this->config('afterTemplate');
        return (empty($beforeTemplateFile) ? '' : $view->render($beforeTemplateFile)).$view->render($templateFile).(empty($afterTemplateFile) ? '' : $view->render($afterTemplateFile));
    }

    public function config($key, $value = null) {
        if ($value === null) {
            return isset($this->_config[$key]) ? $this->_config[$key] : null;
        }
        $this->_config[$key] = $value;
        return $this;
    }

    /**
     * @param Callable $exportCallable
     */
    public function setExportCallable($exportCallable)
    {
        if (is_string($exportCallable)) {
            $exportCallable = array($this->_entity, $exportCallable);
        }
        $this->_exportCallable = $exportCallable;
        return $this;
    }

    /**
     * @return Callable
     */
    public function getExportCallable()
    {
        return $this->_exportCallable;
    }

    /**
     * @param Callable $importCallable
     */
    public function setImportCallable($importCallable)
    {
        if (is_string($importCallable)) {
            $importCallable = array($this->_entity, $importCallable);
        }
        $this->_importCallable = $importCallable;
        return $this;
    }

    /**
     * @return Callable
     */
    public function getImportCallable()
    {
        return $this->_importCallable;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @param array $validators
     */
    public function setValidators(array $validators)
    {
        $this->_validators = array();
        /* @var \SlimForm\Annotation\Validator $validatorAnnotation */
        foreach ($validators as $validatorAnnotation) {
            // create a validator from the annotation
            $this->_validators[] = ValidatorFactory::createValidator($this, $validatorAnnotation->type, $validatorAnnotation->options);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->_validators;
    }

    public function addValidator(ValidatorInterface $validator) {
        $this->_validators[] = $validator;
        return $this;
    }

    public function removeValidator(ValidatorInterface $validator) {
        $index = array_search($validator, $this->_validators, true);
        if ($index !== false) {
            unset($this->_validators[$index]);
        }
        return $this;
    }

    public function getEntity() {
        return $this->_entity;
    }

    public function setEntity($entity) {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * @param $name
     * @return Element
     */
    public function get($name) {
        if (isset($this->_children[$name])) {
            return $this->_children[$name];
        }
        foreach ($this->_children as $child) {
            $found = $child->get($name);
            if ($found !== null) {
                return $found;
            }
        }
        return null;
    }

    public function remove($name, $removeEmptyRows = true) {
        if (isset($this->_children[$name])) {
            unset($this->_children[$name]);
            return true;
        }
        /* @var \SlimForm\Element\Element $child */
        foreach ($this->_children as $index => $child) {
            if ($child->remove($name, $removeEmptyRows)) {
                // child contained the matching name
                if ($removeEmptyRows && $child->countChildren() < 1) {
                    unset($this->_children[$index]);
                }
                return true;
            }
        }
        return false;
    }

    public function setValidationGroup(array $elementNames) {
        $this->_validationGroup = $elementNames;
        return $this;
    }

    /**
     * A way to set data onto a form based on a key => value array
     * @param $data
     */
    public function setData($data) {
        // if no validation group set (everything validates) or it is set and this element's name is in the list
        // attempt to set value from data array
        if (empty($this->_validationGroup) || isset($this->_validationGroup[$this->getName()])) {
            if (isset($data[$this->getName()])) {
                $this->setValue($data[$this->getName()]);
            }
        }
        /* @var Element $child */
        foreach ($this->_children as $child) {
            $child->setData($data);
        }
        return $this;
    }

    /**
     * Validates the currently set data based on the element's validation rules
     */
    public function validate() {
        $valid = true;
        // run all validators on self
        /* @var \SlimForm\Validator\AbstractValidator $validator */
        foreach ($this->getValidators() as $validator) {
            if (!$validator->validate()) {
                $valid = false;
                // add messages
                $this->addErrorMessage($this, $validator->getCode(), $validator->getMessage());
                break;
            }
        }
        // run all validators on children
        /* @var Element $child */
        foreach ($this->getChildren() as $child) {
            $valid = $child->validate() && $valid;
        }
        // return whether this tree validated.
        return $valid;
    }

    public function addErrorMessage(Element $element, $errorKey, $errorMessage) {
        $elementKey = $element->getName();
        if (!isset($this->_messages[$elementKey])) {
            $this->_messages[$elementKey] = array();
        }
        $this->_messages[$elementKey][$errorKey] = $errorMessage;
        return $this;
    }

    public function getMessages() {
        $messages = $this->_messages;
        foreach ($this->_children as $child) {
            $messages = array_merge($messages, $child->getMessages());
        }
        return $messages;
    }

    /**
     * @return Element
     */
    public function getRoot() {
        return $this->_rootElement;
    }

    /**
     * @param Element $root
     * @return $this
     */
    public function setRoot(Element $root) {
        $this->_rootElement = $root;
        return $this;
    }

}