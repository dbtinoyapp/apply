<?php

namespace Cv\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class ComputingSkill extends AbstractSkill  {

    private static $defaults = array(
        'skillName' => array(
            array('id' => '.NET', 'text' => '.NET'),
            array('id' => 'C++', 'text' => 'C++'),
            array('id' => 'HTML', 'text' => 'HTML'),
            array('id' => 'PHP/MySQL', 'text' => 'PHP/MySQL')
        )
    );
    
    public static function getDefaults($key) {
        return self::$defaults[$key];
    }
}
