<?php

namespace Proxies\__CG__\models\Entities\Product;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Color extends \models\Entities\Product\Color implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getID()
    {
        $this->__load();
        return parent::getID();
    }

    public function setName($value)
    {
        $this->__load();
        return parent::setName($value);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setCode($value)
    {
        $this->__load();
        return parent::setCode($value);
    }

    public function getCode()
    {
        $this->__load();
        return parent::getCode();
    }

    public function setPosition($value)
    {
        $this->__load();
        return parent::setPosition($value);
    }

    public function getPosition()
    {
        $this->__load();
        return parent::getPosition();
    }

    public function setStatus($value)
    {
        $this->__load();
        return parent::setStatus($value);
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function getProductColors()
    {
        $this->__load();
        return parent::getProductColors();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'code', 'position', 'status', 'product_colors');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}