<?php

namespace Cv\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class ManagementSkill extends AbstractSkill {
    private static $defaults = array(
        'skillName' => array(
            array('id' => 'Business Development', 'text' => 'Business Development'),
            array('id' => 'Marketing/Sales', 'text' => 'Marketing/Sales'),
            array('id' => 'Accounts Management', 'text' => 'Accounts Management'),
            array('id' => 'Operations', 'text' => 'Operations'),
            array('id' => 'IT Services', 'text' => 'IT Services'),
            array('id' => 'IS Management', 'text' => 'IS Management')
        )
    );
    
    public static function getDefaults($key) {
        return self::$defaults[$key];
    }
}
