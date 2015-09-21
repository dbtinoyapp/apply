<?php

namespace Cv\Entity;

//use Core\Entity\AbstractIdentifiableEntity;
use Doctrine\Common\Collections\Collection as CollectionInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
//use JMS\Serializer\Annotation as Jms;
use Auth\Entity\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Core\Entity\AbstractIdentifiableModificationDateAwareEntity as BaseEntity;
/**
 * 
 * @ODM\Document(collection="cvs", repositoryClass="\Cv\Repository\Cv")
 */
class Cv extends BaseEntity implements CvInterface {

    /** @var string 
     * @ODM\String
     */
    protected $title;

    /**
     * 
     * @var \Auth\Entity\User
     * @ODM\ReferenceOne(targetDocument="\Auth\Entity\User", simple=true)
     */
    protected $user;
    /**
     * 
     * @var \Jobs\Entity\Job
     * @ODM\ReferenceOne(targetDocument="\Jobs\Entity\Job", simple=true)
     */
    protected $positionApplied;
    /**
     * 
     * @var \Applications\Entity\Application
     * @ODM\ReferenceOne(targetDocument="\Applications\Entity\Application", simple=true)
     */
    protected $application;
    /**
     * personal informations, contains firstname, lastname, email, 
     * phone etc.
     *
     * @ODM\EmbedOne(targetDocument="\Applications\Entity\Contact")
     */
    protected $contact;
    /**
     * 
     * @var \Cv\Entity\Education
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\Education")
     */
    protected $educations;

    /**
     * 
     * @var \Cv\Entity\Training
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\Training")
     */
    protected $trainings;

    /**
     * 
     * @var \Cv\Entity\Employment
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\Employment")
     */
    protected $employments;
    
    /** @var bool 
     * @ODM\Boolean */
    protected $freshGraduateIndicator;
    
    /**
     * 
     * @var \Cv\Entity\OtherLanguage
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\OtherLanguage")
     */
    protected $otherLanguages;

    /**
     * 
     * @var \Cv\Entity\NativeLanguage
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\NativeLanguage")
     */
    protected $nativeLanguages;

    /**
     * 
     * @var \Cv\Entity\ComputingSkill
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\ComputingSkill")
     */
    protected $computingSkills;
    /**
     * 
     * @var \Cv\Entity\ManagementSkill
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\ManagementSkill")
     */
    protected $managementSkills;
    /**
     * 
     * @var \Cv\Entity\ComplianceSkill
     * @ODM\EmbedMany(targetDocument="\Cv\Entity\ComplianceSkill")
     */
    protected $complianceSkills;
    /** @var string 
     * @ODM\String
     */
    protected $summary;
    
    
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    public function getFreshGraduateIndicator() {
        return $this->freshGraduateIndicator;
    }

    public function setFreshGraduateIndicator($freshGraduateIndicator) {
        $this->freshGraduateIndicator = $freshGraduateIndicator;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(UserInterface $user) {
        $this->user = $user;
        return $this;
    }
    /**
     * {@inheritDoc}
     * @see \Applications\Entity\ApplicationInterface::getContact()
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * {@inheritDoc}
     * @see \Applications\Entity\ApplicationInterface::setContact()
     * @return Application
     */
    public function setContact(\Auth\Entity\InfoInterface $contact) {
        $this->contact = $contact;
        return $this;
    }
    public function getPositionApplied() {
        return $this->positionApplied;
    }

    public function setPositionApplied($positionApplied) {
        $this->positionApplied = $positionApplied;
        return $this;
    }
    public function getApplication() {
        return $this->application;
    }

    public function setApplication(\Applications\Entity\ApplicationInterface $application) {
        $this->application = $application;
        return $this;
    }

        /**
     * @return the $educations
     */
    public function getEducations() {
        if (!$this->educations) {
            $this->setEducations(new ArrayCollection());
        }
        return $this->educations;
    }

    /**
     * @param field_type $educations
     */
    public function setEducations(CollectionInterface $educations) {
        $this->educations = $educations;
        return $this;
    }
    /**
     * @param field_type $educations
     */
    public function removeEducations() {
        $this->educations = new ArrayCollection();
        return $this;
    }

    /**
     * @return the $educations
     */
    public function getTrainings() {
        if (!$this->trainings) {
            $this->setTrainings(new ArrayCollection());
        }
        return $this->trainings;
    }

    /**
     * @param field_type $trainings
     */
    public function setTrainings(CollectionInterface $trainings) {
        $this->trainings = $trainings;
        return $this;
    }

    /**
     * @return the $employments
     */
    public function getEmployments() {
        if (!$this->employments) {
            $this->setEmployments(new ArrayCollection());
        }
        return $this->employments;
    }

    /**
     * @param field_type $employments
     */
    public function setEmployments(CollectionInterface $employments) {
        $this->employments = $employments;
        return $this;
    }

    /**
     * @return the $otherLanguages
     */
    public function getOtherLanguages() {
        if (!$this->otherLanguages) {
            $this->setOtherLanguages(new ArrayCollection());
        }
        return $this->otherLanguages;
    }

    /**
     * @param field_type $otherLanguages
     */
    public function setOtherLanguages(CollectionInterface $otherLanguages) {
        $this->otherLanguages = $otherLanguages;
        return $this;
    }

    /**
     * @return the $nativeLanguages
     */
    public function getNativeLanguages() {
        if (!$this->nativeLanguages) {
            $this->setNativeLanguages(new ArrayCollection());
        }
        return $this->nativeLanguages;
    }

    /**
     * @param field_type $nativeLanguages
     */
    public function setNativeLanguages(CollectionInterface $nativeLanguages) {
        $this->nativeLanguages = $nativeLanguages;
        return $this;
    }

    /**
     * @return the $computingSkills
     */
    public function getComputingSkills() {
        if (!$this->computingSkills) {
            $this->setComputingSkills(new ArrayCollection());
        }
        return $this->computingSkills;
    }

    /**
     * @param field_type $computingSkills
     */
    public function setComputingSkills(CollectionInterface $computingSkills) {
        $this->computingSkills = $computingSkills;
        return $this;
    }
    
    public function getManagementSkills() {
        if (!$this->managementSkills) {
            $this->setManagementSkills(new ArrayCollection());
        }
        return $this->managementSkills;
    }

    public function getComplianceSkills() {
        if (!$this->complianceSkills) {
            $this->setComplianceSkills(new ArrayCollection());
        }
        return $this->complianceSkills;
    }

    public function setManagementSkills(CollectionInterface $managementSkills) {
        $this->managementSkills = $managementSkills;
        return $this;
    }

    public function setComplianceSkills(CollectionInterface $complianceSkills) {
        $this->complianceSkills = $complianceSkills;
        return $this;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
        return $this;
    }


}
