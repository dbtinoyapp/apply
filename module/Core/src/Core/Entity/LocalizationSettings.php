<?php
namespace Core\Entity;

use Settings\Entity\SettingsContainer as Container;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class LocalizationSettings extends Container
{
    /**
     * @ODM\String
     */
    protected $language;
}

