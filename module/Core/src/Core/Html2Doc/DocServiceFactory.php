<?php

namespace Core\Html2Doc;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DocServiceFactory implements FactoryInterface
{
     
    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        
        $Html2DocConverter = $serviceLocator->get('Html2DocConverter');
        if (!$Html2DocConverter instanceof DocInterface) {
            throw new \DomainException(sprintf(
                'DocConverter %s does not implements DocInterface',
                get_class($Html2DocConverter)
            ));
        }
        //$configArray = $serviceLocator->get('Config');
        
        $viewManager = $serviceLocator->get('ViewManager');
        $view = $viewManager->getView();
        $viewEvents = $view->getEventManager();
        $Html2DocConverter->attach($viewEvents);
        
        $application = $serviceLocator->get('Application');
        $MvcEvents = $application->getEventManager();
        $Html2DocConverter->attachMvc($MvcEvents);
        //$events->attach(ViewEvent::EVENT_RENDERER_POST, array($this, 'removeLayout'), 1);
        //$viewEvents->attach(ViewEvent::EVENT_RESPONSE, array($this, 'attachDOCtransformer'), 10);

        
        return $Html2DocConverter;
        
    }

}

