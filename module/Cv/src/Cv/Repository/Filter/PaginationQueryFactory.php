<?php
namespace Cv\Repository\Filter;

use Zend\ServiceManager\FactoryInterface;

class PaginationQueryFactory implements FactoryInterface
{
	public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
            
        $services = $serviceLocator->getServiceLocator();
        $auth     = $services->get('AuthenticationService');
        $user     = $auth->hasIdentity() ? $auth->getUser() : null;
        $filter   = new PaginationQuery($user);
        
        return $filter;
        
    }

    
}

