<?php
namespace Cv\Entity;

use Core\Entity\IdentifiableEntityInterface;

interface AbstractSkillInterface extends IdentifiableEntityInterface
{
    
    
	
    public function setSkillName($skillName);
    public function getSkillName();
    public function setYrsOfExperience($yrsOfExperience);
    public function getYrsOfExperience();
    public function setCompetency($competency);
    public function getCompetency();
    public function setDescription($value);
    public function getDescription();    
    
}