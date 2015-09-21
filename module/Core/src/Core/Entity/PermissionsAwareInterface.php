<?php
namespace Core\Entity;

interface PermissionsAwareInterface
{
    
    public function getPermissions();
    public function setPermissions(PermissionsInterface $permissions);
}

