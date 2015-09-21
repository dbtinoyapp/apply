<?php

namespace Cv\Entity;

use Core\Entity\AbstractIdentifiableEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

class AbstractSkill extends AbstractIdentifiableEntity implements AbstractSkillInterface {

    /** @var string 
     * @ODM\String */     
    protected $skillName;

    /** @var string
     * @ODM\String */
    protected $yrsOfExperience;
    
    /** @var string
     * @ODM\String */
    protected $competency;
    
    /** @var string
     * @ODM\String */
    protected $description;

    public function getSkillName() {
        return $this->skillName;
    }

    public function setSkillName($skillName) {
        $this->skillName = $skillName;
        return $this;
    }

    public function setDescription($value) {
        $this->description = $value;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setYrsOfExperience($yrsOfExperience) {
        $this->yrsOfExperience = $yrsOfExperience;
        return $this;
    }

    public function getYrsOfExperience() {
        return $this->yrsOfExperience;
    }
    
    public function getCompetency() {
        return $this->competency;
    }

    public function setCompetency($competency) {
        $this->competency = $competency;
        return $this;
    }
    
}
