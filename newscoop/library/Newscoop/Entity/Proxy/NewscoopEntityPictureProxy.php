<?php

namespace Newscoop\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class NewscoopEntityPictureProxy extends \Newscoop\Entity\Picture implements \Doctrine\ORM\Proxy\Proxy
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
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }
    
    
    public function getHeadline()
    {
        $this->__load();
        return parent::getHeadline();
    }

    public function getCaption()
    {
        $this->__load();
        return parent::getCaption();
    }

    public function getContentType()
    {
        $this->__load();
        return parent::getContentType();
    }

    public function getPhotographer()
    {
        $this->__load();
        return parent::getPhotographer();
    }

    public function getSource()
    {
        $this->__load();
        return parent::getSource();
    }

    public function isApproved()
    {
        $this->__load();
        return parent::isApproved();
    }

    public function getDate()
    {
        $this->__load();
        return parent::getDate();
    }

    public function getPlace()
    {
        $this->__load();
        return parent::getPlace();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'photographer', 'headline', 'caption', 'source', 'date', 'place', 'contentType', 'location', 'url', 'thumbnailFileName', 'imageFileName', 'uploadedBy', 'created', 'updated', 'status');
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