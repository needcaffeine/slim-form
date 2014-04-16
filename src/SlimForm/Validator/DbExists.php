<?php
namespace SlimForm\Validator;

use SlimForm\Element\Element;

/**
 * Ensures that the element's value is already in the db for the specified class's property
 * Class DbExists
 * @package SlimForm\Validator
 */
class DbExists extends AbstractValidator {
    const OPT_ENTITY = 'entity';
    const OPT_PROPERTY = 'property';

    const ERR_NOT_EXIST = 'errNotExist';

    protected $_errorMessages = array(self::ERR_NOT_EXIST => '"%s" is not present in the database');
    public function __construct(Element $element, $options = array()) {
        parent::__construct($element, $options);
        if (!isset($options[self::OPT_ENTITY]) || !isset($options[self::OPT_PROPERTY])) {
            throw new \InvalidArgumentException('The InArray validator requires you to specify ');
        }
    }


    /**
     * @return bool
     */
    public function validate() {
        /* @var \Doctrine\ORM\EntityManager $em */
        $em = \Slim\Slim::getInstance()->em;
        $entity = $em->getRepository($this->_options[self::OPT_ENTITY])->findOneBy(array($this->_options[self::OPT_PROPERTY] => $this->_element->getValue()));
        if ($entity !== null) {
            return true;
        }
        $this->_errorCode = self::ERR_NOT_EXIST;
        return $entity !== null;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function _getEntityManager() {
        return \Slim\Slim::getInstance()->em;
    }
}