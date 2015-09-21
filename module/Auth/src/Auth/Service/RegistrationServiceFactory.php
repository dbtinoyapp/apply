<?php
namespace Auth\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Auth\Repository\User as UserRepository;
use Auth\RegistrationService;

/**
 * RegistrationServiceFactory adapter factory
 */
class RegistrationServiceFactory implements FactoryInterface 
{

    /**
     * 
     * - injects the UserMapper fetched from the service manager.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repository = $serviceLocator->get('repositories')->get('Auth/User');
        $reg       = new RegistrationService($repository);
        return $reg;
    }
    
}