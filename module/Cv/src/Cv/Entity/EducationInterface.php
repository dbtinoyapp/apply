<?php
namespace Cv\Entity;

use Core\Entity\IdentifiableEntityInterface;

interface EducationInterface extends IdentifiableEntityInterface
{
	
    public function setStartDate($startDate);
    public function getStartDate();
    public function setEndDate($endDate);
    public function getEndDate();
    public function setCurrentIndicator($currentIndicator);
    public function getCurrentIndicator();
    public function setCompetencyName($competencyName);
    public function getCompetencyName();
//    public function setDescription($value);
//    public function getDescription();
    
}