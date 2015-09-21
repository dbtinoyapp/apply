<?php

namespace Cv\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class ComplianceSkill extends AbstractSkill {
    private static $defaults = array(
        'skillName' => array(
            array('id' => 'ITIL', 'text' => 'ITIL'),
            array('id' => 'PMI', 'text' => 'PMI'),
            array('id' => 'CMMI', 'text' => 'CMMI'),
            array('id' => 'Six Sigma', 'text' => 'Six Sigma'),
            array('id' => 'BS 7799', 'text' => 'BS 7799'),
            array('id' => 'ISO 17799', 'text' => 'ISO 17799'),
            array('id' => 'SOX', 'text' => 'SOX'),
        )
    );
    
    public static function getDefaults($key) {
        return self::$defaults[$key];
    }
}
