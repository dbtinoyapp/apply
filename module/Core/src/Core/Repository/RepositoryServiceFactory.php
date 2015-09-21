<?php
namespace Core\Repository;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RepositoryServiceFactory implements FactoryInterface
{
    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dm      = $serviceLocator->get('Core/DocumentManager');
        $service = new RepositoryService($dm);
        
        return $service;
    }
}

