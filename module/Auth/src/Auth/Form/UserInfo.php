<?php

namespace Auth\Form;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Core\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Entity\Hydrator\EntityHydrator;
//use Zend\InputFilter\InputFilterProviderInterface;

class UserInfo extends Form implements ServiceLocatorAwareInterface//, InputFilterProviderInterface
{
    protected $forms;
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->forms = $serviceLocator;
        return $this;
    }
    
    public function getServiceLocator()
    {
        return $this->forms;
    }
    
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }
    
    public function init()
    {
        
        $this->add(
            $this->forms->get('Auth/UserInfoFieldset')
                        //->setUseAsBaseFieldset(true)
        );
       
    }
    

    
    
}