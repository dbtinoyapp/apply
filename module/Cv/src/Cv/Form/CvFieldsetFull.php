<?php

namespace Cv\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;

class CvFieldsetFull extends CvFieldset implements ServiceLocatorAwareInterface, InputFilterProviderInterface
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

    public function init() {
        
        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        )); // Investigate and remove if not applicable
        $this->add($this->forms
                         ->get('Auth/UserInfoFieldset')
                         ->setLabel('Personal Informations')
                         ->setName('contact')
                         ->setObject(new \Applications\Entity\Contact()))
                ;
        parent::init();
    }
}
