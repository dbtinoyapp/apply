<?php

namespace Auth\Form;

use Zend\Form\Form;
use Core\Entity\Hydrator\EntityHydrator;

class Registration extends Form
{
    
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
        $this->setName('registration');
        $this->setAttribute('id', 'registration');
 
        
        $this->add(array(
            'type' => 'Auth/RegistrationFieldset',
            'name' => 'register',
            'options' => array(
                'use_as_base_fieldset' => true
            ),
        ));       
        
    }
}