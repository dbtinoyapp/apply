<?php
namespace Cv\Entity;

use Core\Entity\EntityInterface;

interface NativeLanguageInterface extends EntityInterface
{
    public function setLanguage($language);
    public function getLanguage();
    
}