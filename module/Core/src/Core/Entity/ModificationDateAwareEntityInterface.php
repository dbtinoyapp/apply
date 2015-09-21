<?php
namespace Core\Entity;

use DateTime;

/**
 * Defines an entity which is aware of its creation and modification dates.
 * 
 */
interface ModificationDateAwareEntityInterface
{
    /**
     * Sets the creation date.
     * 
     * @param DateTime $date
     * @return self
     */
    public function setDateCreated(DateTime $date);
    
    /**
     * Gets the creation date.
     * 
     * @return DateTime
     */
    public function getDateCreated();
    
    /**
     * Sets the modification date.
     * 
     * @param DateTime|String $date
     * @return self
     */
    public function setDateModified($date);
    
    /**
     * Gets the modification date.
     * 
     * @return DateTime
     */
    public function getDateModified();
    
}

