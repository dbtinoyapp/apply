<?php

namespace Core\View\Helper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\View\Helper\Params;

/**
 * Factors a params helper instance.
 * @see \Core\View\Helper\Params
 */
class ParamsHelperFactory implements FactoryInterface {

    /**
     * Creates an instance of \Core\View\Helper\Params
     *
     * - injects the MvcEvent instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Core\View\Helper\Params
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $event = $serviceLocator->getServiceLocator()->get('Application')->getMvcEvent();
        $helper = new Params($event);
        return $helper;
    }

}
