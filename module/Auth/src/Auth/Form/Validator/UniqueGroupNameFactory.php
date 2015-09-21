<?php
namespace Auth\Form\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UniqueGroupNameFactory implements FactoryInterface
{
    
    /**
     * Options.
     * @var array
     */
    protected $options = array();
    
    /**
     * Creates an instance.
     * 
     * @param array $options
     */
    public function __construct($options) 
    {
        $this->options['allowName'] = isset($options['allowName']) ? $options['allowName'] : null;
    }
    
    /**
     * Creates an UniqueGroupName validator.
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services  = $serviceLocator->getServiceLocator();
        $auth      = $services->get('AuthenticationService');
        $user      = $auth->getUser();
        $options   = $this->options;
        $options['user'] = $user;
        $validator = new UniqueGroupName($options);

        return $validator;
    }
}

