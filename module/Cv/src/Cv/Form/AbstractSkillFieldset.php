<?php

namespace Cv\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

abstract class AbstractSkillFieldset extends Fieldset implements InputFilterProviderInterface {

    public function init() {

        $this->add(array(
            'name' => 'skillName',
            'options' => array(
                'label' => /* @translate */ 'Skill Name'
            ),
            'attributes' => array(
                'title' => /* @translate */ 'please enter the skill name',
                'class' => 'autosuggest-select',
                'data-url' => '/en/cvs/populate'
            ),
        ));
        $valueArr = array();
        
        for($i=1; $i<=20; $i++) {
            $valueArr[$i] = $i;
        }
        
        $this->add(array(
            'name' => 'yrsOfExperience',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Years of Experience',
                'value_options' => $valueArr
            ),
            'attributes' => array(
                'title' => /* @translate */ 'please enter the years of experience'
            ),
        ));

        $this->add(array(
            'name' => 'competency',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Competency',
                'value_options' => array(
                    'Entry Level' => /* @translate */ 'Entry Level',
                    'Intermmediate' => /* @translate */ 'Intermmediate',
                    'Advanced' => /* @translate */ 'Advanced',
                )
            ),
            'attributes' => array(
                'title' => /* @translate */ 'please enter the skill competency'
            ),
        ));

    }

    public function getInputFilterSpecification() {
        return array(
            'skillName' => array(
                'required' => true,
                'filters' => array(
                    array('name' => '\Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    new \Zend\Validator\NotEmpty(),
                    new \Zend\Validator\StringLength(array('max' => 100))
                ),
            ),
            'competency' => array(
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
