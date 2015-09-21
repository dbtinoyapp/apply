<?php
namespace Applications\Entity;

use Core\Entity\EntityInterface;

/**
 * Application StatusInterface
 */
interface StatusInterface extends EntityInterface
{
    const NEWLY  = 'newly';
    const HIRED = 'hired';
    const PROCESS   = 'process';
    const FAILED  = 'failed';
    
    public function __construct($status = self::NEWLY);

    /**
     * Gets the Name of an application state.
     */
    public function getName();
    
    /**
     * Gets an integer of an application state.
     */
    public function getOrder();
    
    /**
     * Converts an status entity into a string
     */
    public function __toString();
}

