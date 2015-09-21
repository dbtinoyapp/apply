<?php

namespace Cv\Form;

use Cv\Entity\ManagementSkill as ManagementSkillEntity;
use Core\Entity\Hydrator\EntityHydrator;

class ManagementSkillFieldset extends AbstractSkillFieldset {

    public function init() {
        $this->setName('managementSkill')
                ->setHydrator(new EntityHydrator())
                ->setObject(new ManagementSkillEntity())
                ->setLabel('Management Skill');
        
        parent::init();
    }
}
