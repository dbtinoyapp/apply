<?php
namespace Auth\Form;

use Core\Form\Form;
use Zend\Form\Fieldset;
use Core\Entity\Hydrator\EntityHydrator;

class Group extends Form 
{
    
    /**
     * Initialises the form.
     * @see \Zend\Form\Element::init()
     */
    public function init()
    {

        $this->add(array(
            'type' => 'Auth/Group/Data',
            'options' => array(
                'mode' => $this->getOption('mode')
            ),
        ));

        $this->add(array(
            'type' => 'DefaultButtonsFieldset',
        ));
    }
    
  
}

