<?php

namespace Cv\Form;

use Zend\Form\Fieldset;
use Cv\Entity\Employment as EmploymentEntity;
use Core\Entity\Hydrator\EntityHydrator;
use Zend\InputFilter\InputFilterProviderInterface;

class EmploymentFieldset extends Fieldset implements InputFilterProviderInterface {

    public function init() {
        $this->setName('employment')
                ->setHydrator(new EntityHydrator())
                ->setObject(new EmploymentEntity())
                ->setLabel('Employment');

        $this->add(array(
            'name' => 'organizationName',
            'options' => array(
                'label' => /* @translate */ 'Company Name'),
            'attributes' => array(
                'title' => /* @translate */ 'please enter the name of the company'
            ),
        ));
        $this->add(array(
            'type' => 'DateSelect',
            'name' => 'startDate',
            'options' => array(
                'label' => /* @translate */ 'From',
                'day_attributes' => array(
                    'style' => 'display: none',
                )
            )
        ));
        $this->add(array(
            'type' => 'DateSelect',
            'name' => 'endDate',
            'options' => array(
                'label' => /* @translate */ 'To',
                'day_attributes' => array(
                    'style' => 'display: none',
                )
            )
        ));
        $this->add(array(
            'type' => 'checkbox',
            'name' => 'currentIndicator',
            'options' => array(
                'label' => /* @translate */ 'Ongoing'
            )
        ));
        $this->add(array(
            'name' => 'position',
            'options' => array(
                'label' => /* @translate */ 'Position'
            )
        ));


        $description = new \Zend\Form\Element\Textarea('description');
        $description->setLabel('Description')
                ->setAttribute('class', 'ckeditor')
                ->setAttribute('rows', 8);

        $this->add($description);
    }

    public function getInputFilterSpecification() {
        return array(
            'organizationName' => array(
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 50))
                ),
            ),
            'description' => array(
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('min' => 5))
                ),
            ),
        );
    }

}
