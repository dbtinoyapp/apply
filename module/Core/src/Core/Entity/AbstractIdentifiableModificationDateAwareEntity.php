<?php
namespace Core\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use DateTime;

/**
 * Abstract Identifiable Modification Date Aware Entity
 * 
 * @ODM\MappedSuperclass
 */
abstract class AbstractIdentifiableModificationDateAwareEntity 
    extends    AbstractIdentifiableEntity 
    implements ModificationDateAwareEntityInterface,
               PreUpdateAwareInterface
{
    /**
     * Creation date.
     * 
     * @var DateTime
     * @ODM\Field(type="tz_date")
     */
    protected $dateCreated;
    
    /**
     * Modification date.
     * @var DateTime
     * @ODM\Field(type="tz_date")
     */
    protected $dateModified;
    
    
    /**
     * {@inheritDoc}
     */
    public function getDateCreated ()
    {
        return $this->dateCreated;
    }

    /**
     * {@inheritDoc}
     */
    public function setDateCreated (DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
	 * {@inheritDoc}
     */
    public function getDateModified ()
    {
        return $this->dateModified;
    }

    /**
     * {@inheritDoc}
     */
    public function setDateModified ($dateModified)
    {
        if (is_string($dateModified)) {
            $dateModified = new DateTime($dateModified);
        }        
        $this->dateModified = $dateModified;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate($isNew=false)
    {
        if ($isNew) {
            $this->setDateCreated(new DateTime());
        } else {
            $this->setDateModified(new DateTime());
        }
    }
    
}