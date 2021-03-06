<?php
namespace Acl\View\Helper;

use Zend\ServiceManager\FactoryInterface;

class AclFactory implements FactoryInterface
{
	/* (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $plugins  = $services->get('controllerpluginmanager');
        $acl      = $plugins->get('acl');
        
        $helper = new Acl($acl);
        return $helper;
    }

    
}

