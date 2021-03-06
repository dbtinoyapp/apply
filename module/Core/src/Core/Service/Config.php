<?php

namespace Core\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Core\Mapper\Query\Query;

class Config implements ServiceManagerAwareInterface
{
    protected $serviceManager;
    protected $config;
    protected $map = array();
    protected $applicationMap = array();
    protected $configAccess;
    protected $controller;
    protected $namespace;
    
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }
    
    public function setController($controller) {
        if ($controller != $this->controller) {
            $this->controller = $controller;
            $controllerClass = $controller;
            if (is_object($controller)) {
                $controllerClass = get_class($controller);
            }
            $this->setNamespace(substr($controllerClass, 0, strpos($controllerClass, '\\')));
        }
        return $this;
    }
    
    public function setNamespace($name) {
        $this->namespace = $name;
        return $this;
    }
    
    public function getNamespace() {
        return $this->namespace;
    }
     
    public function get($key = null)
    {
        $namespace = $this->getNamespace();
        if (isset($this->map[$key])) {
            
            return isset($this->map[$key][$module]) ? $this->map[$key][$module] : array();
        }
        $config = $this->getConfig($namespace);
        if (isset($key)) {
            return isset($config[$key]) ? $config[$key] : null;
        }
        return $config;
    }
    
    /**
     * fetch the settings for a certain key of all Modules 
     * @param string $key
     * @return array
     */
    public function getByKey($key = null)
    {
        if (!array_key_exists($key, $this->applicationMap)) {
            $this->applicationMap[$key] = array();
            $config = $this->serviceManager->get('Config');
            $appConfig = $this->serviceManager->get('ApplicationConfig');
            foreach ($appConfig['modules'] as $module) {
                if (array_key_exists($module, $config)) {
                    if (array_key_exists($key, $config[$module])) {
                        // The strtolower is essential for later retrieving
                        // namespaces in the repositories
                        // anyway, it's easier to agree everything should be lowercase
                        $this->applicationMap[$key][strtolower($module)] = $config[$module][$key];
                    }
                }
            }
        }
        return $this->applicationMap[$key];
    }
    
    protected function getConfig($namespace = Null)
    {
        if (!$this->config) {
            $this->config = array();
            $config    = $this->serviceManager->get('Config');
            $appConfig = $this->serviceManager->get('ApplicationConfig');
            foreach ($appConfig['modules'] as $module) {
                $this->config[$module] = array_key_exists($module, $config)?$config[$module]:array();
            }
        }
        if (isset($namespace)) {
            return array_key_exists($namespace,$this->config)?$this->config[$namespace]: array();
        }
        return $this->config;
    }
    
    protected function getMapConfig($key)
    {
        if (!isset($this->map[$key])) {
            $config          = $this->serviceManager->getServiceLocator()->get('Config');
            $this->map[$key] = isset($config[$key]) ? $config[$key] : array();
        }
        if (!empty($this->map[$key])) {
            $module          = $this->getNamespace();
            return isset($this->map[$key][$module])?$this->map[$key][$module]:array();
        }
        return array();
    }
    
}
