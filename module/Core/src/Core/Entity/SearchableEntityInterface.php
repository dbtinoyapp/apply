<?php
namespace Core\Entity;

interface SearchableEntityInterface
{
    
    /**
     * @return array searchable properties names.
     */
    public function getSearchableProperties();
    public function setKeywords(array $keywords);
    public function clearKeywords();
    public function getKeywords();
}

