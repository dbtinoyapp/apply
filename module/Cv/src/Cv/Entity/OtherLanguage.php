<?php

namespace Cv\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class OtherLanguage extends AbstractEntity implements OtherLanguageInterface
{
    
    /**
     * 
     * @var unknown
     * @ODM\String
     */
    protected $language;
    
    /**
     * @ODM\String
     * @var unknown
     */
    protected $levelReading;

    /**
     * @ODM\String
     * @var unknown
     */
    protected $levelSpoken;

    public function setLanguage($language)
    {
        $this->language = (string) $language;
        return $this;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function setLevelReading($level)
    {
        $this->levelReading = $level;
        return $this;
    }
    
    public function getLevelReading()
    {
        return $this->levelReading;
    }
    
    public function setLevelSpoken($level)
    {
        $this->levelSpoken = $level;
        return $this;
    }
    
    public function getLevelSpoken()
    {
        return $this->levelSpoken;
    }
}