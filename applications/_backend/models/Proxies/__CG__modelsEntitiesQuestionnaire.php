<?php

namespace Proxies\__CG__\models\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Questionnaire extends \models\Entities\Questionnaire implements \Doctrine\ORM\Proxy\Proxy
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

    public function setDate()
    {
        $this->__load();
        return parent::setDate();
    }

    public function getDate()
    {
        $this->__load();
        return parent::getDate();
    }

    public function getFormatedDate()
    {
        $this->__load();
        return parent::getFormatedDate();
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

    public function setImage($value)
    {
        $this->__load();
        return parent::setImage($value);
    }

    public function getImage()
    {
        $this->__load();
        return parent::getImage();
    }

    public function getImageURL()
    {
        $this->__load();
        return parent::getImageURL();
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

    public function setQuestion(\models\Entities\Questionnaire\Question $value)
    {
        $this->__load();
        return parent::setQuestion($value);
    }

    public function getQuestions()
    {
        $this->__load();
        return parent::getQuestions();
    }

    public function setResult(\models\Entities\Questionnaire\Result $value)
    {
        $this->__load();
        return parent::setResult($value);
    }

    public function getResults()
    {
        $this->__load();
        return parent::getResults();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'date', 'title', 'image', 'status', 'questions', 'results');
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