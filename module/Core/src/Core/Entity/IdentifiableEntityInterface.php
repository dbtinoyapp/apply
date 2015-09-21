<?php
namespace Core\Entity;


/**
 * Model interface
 */
interface IdentifiableEntityInterface 
{

    /**
     * Sets the id.
     * 
     * @param mixed $id
     */
    public function setId($id);
    
    /**
     * Gets the id.
     * 
     * @return mixed
     */
    public function getId();
    
}