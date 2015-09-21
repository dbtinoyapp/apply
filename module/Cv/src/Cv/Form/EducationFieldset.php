<?php

namespace Cv\Form;

use Zend\Form\Fieldset;
use Cv\Entity\Education as EducationEntity;
use Core\Entity\Hydrator\EntityHydrator;
use Zend\InputFilter\InputFilterProviderInterface;

class EducationFieldset extends Fieldset implements InputFilterProviderInterface {

    public function init() {
        $this->setName('education')
                ->setHydrator(new EntityHydrator())
                ->setObject(new EducationEntity())
                ->setLabel('Education');
        $this->add(array(
            'name' => 'course',
            'options' => array(
                'label' => /* @translate */ 'Course'
            )
        ));

        $this->add(array(
            'name' => 'competencyName',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => /* @translate */ 'Degree',
                'value_options' => array(
                    'Doctorate Degree' => 'Doctorate Degree',
                    'Master\'s Degree' => 'Master\'s Degree',
                    'Bachelor\'s Degree' => 'Bachelor\'s Degree',
                    'Ladderized Program' => 'Ladderized Program',
                    'Vocational' => 'Vocational',
                    'Secondary' => 'Secondary',
                    'Primary' => 'Primary'
                )
            )
        ));
        $this->add(array(
            'name' => 'organizationName',
            'options' => array(
                'label' => /* @translate */ 'Institution'),
            'attributes' => array(
                //'id' => 'education-organizationname',
                'title' => /* @translate */ 'please enter the name of the university or school',
//                'class' => 'autosuggest-select',
//                'data-url' => '/en/cvs/populate'   
            ),
        ));

        $this->add(array(
            'name' => 'organizationCountry',
            'options' => array(
                'label' => /* @translate */ 'Country'),
            'attributes' => array(
                //'id' => 'education-country',
                'title' => /* @translate */ 'please select the country'
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
                'label' => /* @translate */ 'Ongoing?'
            )
        ));
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
                    new \Zend\Validator\StringLength(array('max' => 200))
                ),
            ),
            'organizationCountry' => array(
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 100))
                ),
            ),
            'competencyName' => array(
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 50))
                ),
            ),
        );
    }

}
