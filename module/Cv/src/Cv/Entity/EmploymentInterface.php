<?php

namespace Cv\Entity;

use Core\Entity\EntityInterface;

interface EmploymentInterface extends EntityInterface
{
    public function setStartDate($startDate);
    public function getStartDate();
    public function setEndDate($endDate);
    public function getCurrentIndicator();
    public function setCurrentIndicator($currentIndicator);
    public function getEndDate();
    public function setOrganizationName($value);
    public function getOrganizationName();
    public function setDescription($value);
    public function getDescription();
    
}