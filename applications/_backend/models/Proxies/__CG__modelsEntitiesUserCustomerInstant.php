<?php

namespace Proxies\__CG__\models\Entities\User\Customer;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Instant extends \models\Entities\User\Customer\Instant implements \Doctrine\ORM\Proxy\Proxy
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

    public function setFirstName($value)
    {
        $this->__load();
        return parent::setFirstName($value);
    }

    public function getFirstName()
    {
        $this->__load();
        return parent::getFirstName();
    }

    public function setLastName($value)
    {
        $this->__load();
        return parent::setLastName($value);
    }

    public function getLastName()
    {
        $this->__load();
        return parent::getLastName();
    }

    public function setAddress($value)
    {
        $this->__load();
        return parent::setAddress($value);
    }

    public function getAddress()
    {
        $this->__load();
        return parent::getAddress();
    }

    public function setPhone($value)
    {
        $this->__load();
        return parent::setPhone($value);
    }

    public function getPhone()
    {
        $this->__load();
        return parent::getPhone();
    }

    public function setEmail($value)
    {
        $this->__load();
        return parent::setEmail($value);
    }

    public function getEmail()
    {
        $this->__load();
        return parent::getEmail();
    }

    public function getPostalCode()
    {
        $this->__load();
        return parent::getPostalCode();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'first_name', 'last_name', 'address', 'phone', 'email', 'postal_code');
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
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}