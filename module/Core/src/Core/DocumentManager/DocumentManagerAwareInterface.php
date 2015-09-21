<?php 
namespace Core\DocumentManager;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
* Interface for enforcing Document Manager Awareness
*
* @author Matt Cockayne <matt@zucchi.co.uk>
* @package ZucchiDoctrine
* @subpackage DocumentManager
*/
interface DocumentManagerAwareInterface
{
    /**
* set the DocumentManager
* @param DocumentManager $em
* @return $this
*/
    public function setDocumentManager(DocumentManager $em);

    /**
* get the currently set DocumentManager
* @return DocumentManager
*/
    public function getDocumentManager();
}