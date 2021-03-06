<?php
namespace Core\Acl;

use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;
use Core\Entity\FileInterface;
use Auth\Entity\UserInterface;
use Core\Entity\PermissionsInterface;

/**
* ensures that attachments can be viewed only by persons who have access to the application.
* eg. a recruiter may only see an attached file of an application, if he owns the application.
*/

class FileAccessAssertion implements AssertionInterface
{
    /* (non-PHPdoc)
     * @see \Zend\Permissions\Acl\Assertion\AssertionInterface::assert()
     */
    public function assert(Acl $acl, 
                           RoleInterface $role = null, 
                           ResourceInterface $resource = null, 
                           $privilege = null) 
    {
        if (!$role instanceOf UserInterface || !$resource instanceOf FileInterface) {
            return false;
        }
        
        return $resource->getPermissions()->isGranted($role, PermissionsInterface::PERMISSION_VIEW);
    }
}