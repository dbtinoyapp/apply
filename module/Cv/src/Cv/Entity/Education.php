<?php

namespace Cv\Entity;

use Core\Entity\AbstractIdentifiableEntity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Education extends AbstractIdentifiableEntity {

    /** @var string 
     * @ODM\String
     */
    protected $startDate;

    /** @var string 
     * @ODM\String */
    protected $endDate;

    /** @var bool 
     * @ODM\Boolean */
    protected $currentIndicator;

    /** @var string
     * @ODM\String */
    protected $course;
    
    /** @var string
     * @ODM\String */
    protected $competencyName;

    /** @var string 
     * @ODM\String */
    protected $organizationCountry;
    
    /** @var string 
     * @ODM\String */
    protected $organizationName;


    public function setStartDate($startDate) {
        $this->startDate = (string) $startDate;
        return $this;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
        return $this;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * marks the education as ongoing
     * 
     * @param bool $currentIndicator
     * @return \Cv\Entity\Education
     */
    public function setCurrentIndicator($currentIndicator) {
        $this->currentIndicator = $currentIndicator;
        return $this;
    }

    /**
     * indicates that the education is ongoing
     * 
     * @return bool
     */
    public function getCurrentIndicator() {
        return $this->currentIndicator;
    }

    public function setCompetencyName($competencyName) {
        $this->competencyName = $competencyName;
        return $this;
    }

    public function getCompetencyName() {
        return $this->competencyName;
    }

    /**
     * @param field_type $organizationCountry
     */
    public function setOrganizationCountry($organizationCountry) {
        $this->organizationCountry = $organizationCountry;
        return $this;
    }
    /**
     * @return the $organizationCountry
     */
    public function getOrganizationCountry() {
        return $this->organizationCountry;
    }

    /**
     * @return the $organizationName
     */
    public function getOrganizationName() {
        return $this->organizationName;
    }    
    /**
     * @param field_type $organizationName
     */
    public function setOrganizationName($organizationName) {
        $this->organizationName = $organizationName;
        return $this;
    }
    public function getCourse() {
        return $this->course;
    }

    public function setCourse($course) {
        $this->course = $course;
        return $this;
    }

        public function fromArray($array) 
    {
        $this->startDate=$array['startDate'];
        $this->endDate=$array['endDate'];
        $this->currentIndicator=$array['currentIndicator'];
        $this->competencyName=$array['competencyName'];
        $this->organizationCountry=$array['organizationCountry'];
        $this->organizationName=$array['organizationName'];        
        return($this);   
    }
    
    /**
     * convert an Info object into an Array
     * @param Info $info
     * @return Array
     */
    static function toArray($info) 
    {
        $array['startDate']=$info->startDate;
        $array['endDate']=$info->endDate;
        $array['currentIndicator']=$info->currentIndicator;
        $array['competencyName']=$info->competencyName;
        $array['organizationCountry']=$info->organizationCountry;
        $array['organizationName']=$info->organizationName;
        return $array;
    }
}
