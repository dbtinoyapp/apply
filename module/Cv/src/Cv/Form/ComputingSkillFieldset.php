<?php

namespace Cv\Form;

use Cv\Entity\ComputingSkill as ComputingSkillEntity;
use Core\Entity\Hydrator\EntityHydrator;
//use Core\Form\ViewPartialProviderInterface;

class ComputingSkillFieldset extends AbstractSkillFieldset /*implements ViewPartialProviderInterface */ {
    public function init() {
        $this->setName('computingSkill')
                ->setHydrator(new EntityHydrator())
                ->setObject(new ComputingSkillEntity())
                ->setLabel('Computing Skill');
        
        parent::init();
    }
}
