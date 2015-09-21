<?php
namespace Auth\Form;

use Core\Form\Form;
use Zend\Form\Fieldset;
//use Zend\InputFilter\InputFilterProviderInterface;

class Login extends Form 
{
    
    public function init()
    {
        $this->setName('login-form');
             
        
        $fieldset = new Fieldset('credentials');
        $fieldset->setLabel('Login');
        $fieldset->setOptions(array('renderFieldset' => true));
        $fieldset->add(array(
            'name' => 'login',
            'options' => array(
                'label' => /* @translate */ 'Login name',
            ),
        ));
        
        $fieldset->add(array(
            'type' => 'password',
            'name' => 'credential',
            'options' => array(
                'label' => /* @translate */ 'Password',
                
            ),
        ));
        
        $this->add($fieldset);
            
        $buttons = new \Core\Form\ButtonsFieldset('buttons');
        $buttons->add(array(
            'type' => 'submit',
            'name' => 'button',
            'attributes' => array(
                'id' => 'submit',
                'type' => 'submit',
                'value' => /* @translate */ 'Login',
                'class' => 'btn btn-primary btn-block'
            ),
        ));
        
        $this->add($buttons);
    }
    
    
}