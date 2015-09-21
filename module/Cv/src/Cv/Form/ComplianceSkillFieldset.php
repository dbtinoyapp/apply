<?php

namespace Cv\Form;

use Cv\Entity\ComplianceSkill as ComplianceSkillEntity;
use Core\Entity\Hydrator\EntityHydrator;

class ComplianceSkillFieldset extends AbstractSkillFieldset {

    public function init() {
        $this->setName('complianceSkill')
                ->setHydrator(new EntityHydrator())
                ->setObject(new ComplianceSkillEntity())
                ->setLabel('Compliance Skill');
        
        parent::init();
    }
}
