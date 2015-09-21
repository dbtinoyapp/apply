<?php

namespace Cv\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Core\Entity\AbstractEntity;

/**
 * @ODM\EmbeddedDocument
 */
class NativeLanguage extends AbstractEntity
{
    
    /**
     * 
     * @var unknown
     * @ODM\String
     */
    protected $language;
    
    public function setLanguage($language)
    {
        $this->language = (string) $language;
        return $this;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
}