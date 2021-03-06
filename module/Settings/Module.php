<?php
namespace Settings;

use Zend\Mvc\MvcEvent;
use Settings\Listener\InjectSubNavigationListener;

/**
 * Bootstrap class of the Settings module
 * 
 */
class Module
{
    /**
     * Sets up services on the bootstrap event.
     * 
     * @internal
     *     Creates the translation service and a ModuleRouteListener
     *      
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getApplication()->getEventManager();
        $events->attach(
            array(MvcEvent::EVENT_RENDER, MvcEvent::EVENT_RENDER_ERROR),
            new InjectSubNavigationListener(),
            10
        );
    }

    /**
     * Loads module specific configuration.
     * 
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Loads module specific autoloader configuration.
     * 
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
}
