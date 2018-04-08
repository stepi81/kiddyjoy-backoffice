<?php

namespace Proxies\__CG__\models\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Newsletters extends \models\Entities\Newsletters implements \Doctrine\ORM\Proxy\Proxy
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

    public function setTemplate($value)
    {
        $this->__load();
        return parent::setTemplate($value);
    }

    public function getTemplate()
    {
        $this->__load();
        return parent::getTemplate();
    }

    public function setUsersGroup($value)
    {
        $this->__load();
        return parent::setUsersGroup($value);
    }

    public function getUsersGroup()
    {
        $this->__load();
        return parent::getUsersGroup();
    }

    public function setTitle($value)
    {
        $this->__load();
        return parent::setTitle($value);
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function setMessage($value)
    {
        $this->__load();
        return parent::setMessage($value);
    }

    public function getMessage()
    {
        $this->__load();
        return parent::getMessage();
    }

    public function setSendDate($value)
    {
        $this->__load();
        return parent::setSendDate($value);
    }

    public function getSendDate()
    {
        $this->__load();
        return parent::getSendDate();
    }

    public function setImage(\models\Entities\NewsletterImage $value)
    {
        $this->__load();
        return parent::setImage($value);
    }

    public function getImages()
    {
        $this->__load();
        return parent::getImages();
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


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'template', 'users_group', 'title', 'message', 'send_date', 'offset', 'status', 'images');
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