<?php

namespace Proxies\__CG__\models\Entities\News;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Info extends \models\Entities\News\Info implements \Doctrine\ORM\Proxy\Proxy
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

    public function setVendor($value)
    {
        $this->__load();
        return parent::setVendor($value);
    }

    public function getVendor()
    {
        $this->__load();
        return parent::getVendor();
    }

    public function setNewsTypeID($value)
    {
        $this->__load();
        return parent::setNewsTypeID($value);
    }

    public function getNewsTypeID()
    {
        $this->__load();
        return parent::getNewsTypeID();
    }

    public function setDate($value)
    {
        $this->__load();
        return parent::setDate($value);
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

    public function setPage($value)
    {
        $this->__load();
        return parent::setPage($value);
    }

    public function getPage()
    {
        $this->__load();
        return parent::getPage();
    }

    public function setThumb($value)
    {
        $this->__load();
        return parent::setThumb($value);
    }

    public function getThumb()
    {
        $this->__load();
        return parent::getThumb();
    }

    public function getThumbURL()
    {
        $this->__load();
        return parent::getThumbURL();
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

    public function setImage(\models\Entities\News\InfoImage $value)
    {
        $this->__load();
        return parent::setImage($value);
    }

    public function getImages()
    {
        $this->__load();
        return parent::getImages();
    }

    public function setSummary($value)
    {
        $this->__load();
        return parent::setSummary($value);
    }

    public function getSummary()
    {
        $this->__load();
        return parent::getSummary();
    }

    public function setProduct(\models\Entities\Product $value)
    {
        $this->__load();
        return parent::setProduct($value);
    }

    public function getProducts()
    {
        $this->__load();
        return parent::getProducts();
    }

    public function setStatisticComments($value)
    {
        $this->__load();
        return parent::setStatisticComments($value);
    }

    public function getStatisticComments()
    {
        $this->__load();
        return parent::getStatisticComments();
    }

    public function getFrontURL()
    {
        $this->__load();
        return parent::getFrontURL();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'type_id', 'date', 'title', 'page', 'thumb', 'status', 'summary', 'statistic_comments', 'images', 'products', 'vendor');
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