<?php
namespace Auth\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Core\DocumentManager\DocumentManagerAwareInterface;

class RegistrationFieldset extends Fieldset implements InputFilterProviderInterface, DocumentManagerAwareInterface {

    use \Core\DocumentManager\DocumentManagerAwareTrait;
    
    public function init()
    {
         $this->setName('registration-form');
        $this->setAttribute('id', 'registration-form');            
        
//        $fieldset = new Fieldset('credentials');
        $this->setLabel('Register');
        $this->setOptions(array('renderFieldset' => true));
        
        $this->add(array(
            'name' => 'firstName',
            'options' => array(
                'label' => /* @translate */ 'First name',
            ),
        ));
        $this->add(array(
            'name' => 'lastName',
            'options' => array(
                'label' => /* @translate */ 'Last name',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => /* @translate */ 'Email',
            ),
            
        ));
        $this->add(array(
            'name' => 'login',
            'options' => array(
                'label' => /* @translate */ 'Login name',
            ),
        ));
        
        $this->add(array(
            'type' => 'password',
            'name' => 'credential',
            'options' => array(
                'label' => /* @translate */ 'Password',
                
            ),
        ));
        $this->add(array(
            'type' => 'password',
            'name' => 'confirm_credential',
            'options' => array(
                'label' => /* @translate */ 'Repeat Password',
                
            ),
        ));

//        $this->add($fieldset);
            
        $buttons = new \Core\Form\ButtonsFieldset('buttons');
        $buttons->add(array(
            'type' => 'submit',
            'name' => 'button',
            'attributes' => array(
                'id' => 'submit',
                'type' => 'submit',
                'value' => /* @translate */ 'Register',
                'class' => 'btn btn-primary btn-block'
            ),
        ));
        
        $this->add($buttons);
    }
      /**
     * (non-PHPdoc)
     * @see \Zend\InputFilter\InputFilterProviderInterface::getInputFilterSpecification()
     */
    public function getInputFilterSpecification() {
        return array(
            'firstName' => array(
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 50))
                ),
            ),
            'lastName' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 50))
                ),
            ),
            'login' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 50)),
                    array(
                        'name'      => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->getDocumentManager()->getRepository('Auth\Entity\User'),
                            'fields'            => 'login',
                            'messages' => array(
                                \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'Login already exist.'
                            ) 
                        ),
                    ),                    
                ),
            ),
            'credential' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('min' => 6, 'max' => 50))
                ),
            ),
            'confirm_credential' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'credential', 
                            'messages' => array(
                                \Zend\Validator\Identical::NOT_SAME => 'Password didn\'t matched.'
                            ) 
                        ),
                    )
                )
            ),
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 100)),
                    new \Zend\Validator\EmailAddress(),
                    array(
                        'name'      => 'DoctrineModule\Validator\NoObjectExists',
                        'options' => array(
                            'object_repository' => $this->getDocumentManager()->getRepository('Auth\Entity\User'),
                            'fields'            => 'email',
                            'messages' => array(
                                \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => 'Email already exist.'
                            ) 
                        ),
                    ),
                )
            ),
        );
    }
    
}