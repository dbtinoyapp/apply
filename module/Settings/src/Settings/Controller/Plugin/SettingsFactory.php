<?php
namespace Settings\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $auth     = $services->get('AuthenticationService');
        $user     = $auth->getUser();
        $plugin   = new Settings($user);
        
        return $plugin;
    }
}
