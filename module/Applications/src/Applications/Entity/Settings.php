<?php
namespace Applications\Entity;



use Settings\Entity\ModuleSettingsContainer;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Settings extends ModuleSettingsContainer {
    
    /**
     * 
     * @ODM\Boolean
     * @formLabel Send mail
     */
    protected $mailAccess = false;
    
    /**
     * 
     * @ODM\Boolean
     * @formLabel Send BlindCarbonCopy to owner
     */
    protected $mailBCC = false;
    
    
    /**
     *
     * @ODM\Boolean
     * @formLabel Send mail
     */
    protected $formDisplaySkills = false;
    

    /**
     *
     * @ODM\Boolean
     * @formLabel HIRE application immidiatly after submitting
     */
    protected $autoConfirmMail = false;
    
    /**
     *
     * @ODM\String
     * @formLabel Send mail
     */
    protected $mailAccessText;
    
    /**
     *
     * @ODM\String
     * @formLabel Send mail
     */
    protected $mailProcessText;
    
    
    /**
     *
     * @ODM\String
     * @formLabel Send mail
     */
    protected $mailConfirmationText;
    
    /**
     *
     * @ODM\String
     * @formLabel Send mail
     */
    protected $mailFailureText;
    
	
    public function setMailAccess($mailAccess)
    {
        $this->mailAccess = (bool) $mailAccess;
        return $this;
    }
    
	public function setFormDisplaySkills ($formDisplaySkills)
    {
        $this->formDisplaySkills = (bool) $formDisplaySkills;
        return $this;
    }

}
