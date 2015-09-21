<?php

namespace Auth\Form;

use Core\Entity\Hydrator\EntityHydrator;
use Zend\Form\Fieldset;
use Zend\Form\Element;
use Core\Entity\EntityInterface;
use Core\Entity\RelationEntity;
use Core\Form\ViewPartialProviderInterface;
use Zend\InputFilter\InputFilterProviderInterface;

use Zend\Validator\NotEmpty;
use Zend\Validator\Digits;

class UserInfoFieldset extends Fieldset implements ViewPartialProviderInterface, InputFilterProviderInterface {

    protected $viewPartial = 'form/auth/my-profile';
    
    protected $messages = array(
        'notEmpty' => 'Required.'
    );

    public function setViewPartial($partial) {
        $this->viewPartial = $partial;
        return $this;
    }

    public function getViewPartial() {
        return $this->viewPartial;
    }

    public function getHydrator() {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }

    public function init() {
        $this->setName('info')
                ->setLabel(/* @translate */ 'Personal Informations');


        $this->add(array(
            'name' => 'email',
            'options' => array('label' => /* @translate */ 'Email'),
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => '\Core\Form\Element\Phone',
            'options' => array(
                'label' => /* @translate */ 'Phone',
                'description' => 'Ex: +(02)7654321 or +(63)9187654321'
            ),
            'maxlength' => 20,
        ));

        $this->add(array(
            'name' => 'postalcode',
            'options' => array(
                'label' => /* @translate */ 'Postal Code'
            )
        ));

        $this->add(array(
            'name' => 'city',
            'options' => array(
                'label' => /* @translate */ 'City'
            )
        ));

        $this->add(array(
            'name' => 'gender',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => /* @translate */ 'Salutation',
                'value_options' => array(
                    'male' => /* @translate */ 'Mr.',
                    'female' => /* @translate */ 'Ms.',
                )
            ),
        ));

        $this->add(array(
            'name' => 'firstName',
            'options' => array(
                'label' => /* @translate */ 'Given',
                'maxlength' => 50,
            ),
        ));
        $this->add(array(
            'name' => 'middleName',
            'options' => array(
                'label' => /* @translate */ 'Middle',
                'maxlength' => 50,
            ),
        ));

        $this->add(array(
            'name' => 'lastName',
            'options' => array(
                'label' => /* @translate */ 'Lastname',
                'maxlength' => 50,
            ),
            'required' => true
        ));

        $this->add(array(
            'name' => 'street',
            'options' => array(
                'label' => /* @translate */ 'Street'
            )
        ));

        $this->add(array(
            'name' => 'houseNumber',
            'options' => array(
                'label' => /* @translate */ 'House Number'
            )
        ));
        $this->add(array(
            'type' => 'DateSelect',
            'name' => 'dob',
            'options' => array(
                'min_year' => date('Y') - 60,
                'max_year' => date('Y') - 18,
            ),
        ));
//        $this->add(array(
//            'name' => 'age',
//            'options' => array(
//                'label' => /* @translate */ 'Age'
//            )
//        ));
        $this->add(array(
            'name' => 'citizenship',
            'options' => array(
                'label' => /* @translate */ 'Citizenship'
            )
        ));
        $this->add(array(
            'name' => 'tin',
            'type' => '\Core\Form\Element\TIN',
            'options' => array(
                'label' => /* @translate */ 'TIN ID',
                'description' => 'Ex: XXX-XXX-XXX-000'
            ),
        ));
        $this->add(array(
            'name' => 'sss',
            'type' => '\Core\Form\Element\SSS',
            'options' => array(
                'label' => /* @translate */ 'SSS ID',
                'description' => 'Ex: XX-XXXXXXX-X'
            ),
        ));
        $this->add(array(
            'name' => 'civilStatus',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => /* @translate */ 'Civil Status',
                'value_options' => array(
                    'single' => /* @translate */ 'Single',
                    'married' => /* @translate */ 'Married',
                    'widowed' => /* @translate */ 'Widowed',
                )
            ),
        ));
        $this->add(array(
            'type' => 'hidden',
            'name' => 'imageId',
        ));
        $this->add(array(
            'type' => 'file',
            'name' => 'image',
            'options' => array(
//                 'label' => /*@translate*/ 'Application photo',
            ),
            'attributes' => array(
                'accept' => 'image/*',
            ),
        ));
    }

    public function setValue($value) {
        if ($value instanceOf EntityInterface) {
            if ($value instanceOf RelationEntity) {
                $value = $value->getEntity();
            }
            $data = $this->getHydrator()->extract($value);
            $this->populateValues($data);
            $this->setObject($value);
        }
        return parent::setValue($value);
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
            'middleName' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                  array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                NotEmpty::IS_EMPTY => $this->messages['notEmpty'],
                            ),
                        ),
                    ),
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
            'street' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                ),
            ),
            'houseNumber' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                ),
            ),
            'city' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                ),
            ),
//            'age' => array(
//                'required' => true,
//                'filters' => array(
//                    array('name' => 'Zend\Filter\StringTrim'),
//                ),
//                'validators' => array(
//                    array(
//                        'name' => 'NotEmpty',
//                        'options' => array(
//                            'messages' => array(
//                                NotEmpty::IS_EMPTY => $this->messages['notEmpty'],
//                            ),
//                        ),
//                    ),                    
//                    new \Zend\Validator\StringLength(array('max' => 2)),
//                    array(
//                        'name' => 'Digits',
//                        'options' => array(
//                            'messages' => array(
//                                Digits::STRING_EMPTY => '',
//                                Digits::NOT_DIGITS => 'Invalid.',
//                                Digits::NOT_DIGITS => 'Invalid.',
//                            ),
//                        ),
//                    ),                     
//                ),
//            ),
            'citizenship' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 100)),
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
                    new \Zend\Validator\EmailAddress()
                )
            ),
            'postalcode' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(  
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 5)),
                    new \Zend\Validator\Digits()
                )
            ),
            'image' => array(
                'required' => false,
                'filters' => array(
                ),
                'validators' => array(
                    new \Zend\Validator\File\Exists(),
                    new \Zend\Validator\File\Extension(array('extension' => array('jpg', 'png', 'jpeg', 'gif'))),
                ),
            ),
        );
    }

}
