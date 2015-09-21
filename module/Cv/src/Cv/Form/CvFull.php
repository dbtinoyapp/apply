<?php

namespace Cv\Form;

use Zend\Form\Form;
use Core\Entity\Hydrator\EntityHydrator;
use Zend\InputFilter\InputFilter;
use Zend\Form\FormInterface;

class CvFull extends Form {

    public function getHydrator() {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }

    public function init() {
        $this->setName('cv-create');
        $this->setAttribute('id', 'cv-create');
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));
        
        $this->add(array(
            'type' => 'CvFieldsetFull',
            'options' => array(
                'use_as_base_fieldset' => true
            ),
        ));
        $this->add(array(
            'type' => 'DefaultUnloadButtonsFieldset'
        ));
        $this->setValidationGroup(FormInterface::VALIDATE_ALL);

//        $this->setValidationGroup(array(
//            'csrf',
//
//            'cv' => array(
//                'user' => array(
//                    'info' => array(
//                        'firstName',
//                        'middleName',
//                        'lastName',
//                        'phone',
//                        'email',
//                        'city',
//                        'gender',
//                        'profession',
//                        'street',
//                        'houseNumber',
//                        'dob',
//                        'age',
//                        'citizenship',
//                        'tin',
//                        'sss',
//                        'civilStatus',
//                        'image',
//                        'imageId',
//                    )
//                ),                
//                'educations' => array(
//                    'organizationName',
//                    'competencyName',
//                    'startDate',
//                    'endDate',
//                    'currentIndicator',                    
//                    'organizationCountry'
//                ),
//                'trainings' => array(
//                    'organizationName',
//                    'startDate',
//                    'endDate',
//                    'organizationCountry'
//                ),
//                'employments' => array(
//                    'organizationName',
//                    'startDate',
//                    'endDate',
//                    'currentIndicator',
//                    'description'
//                ),
//                'computingSkills' => array(
//                    'skillName',
//                    'yrsOfExperience',
//                    'competency'
//                ),
//                'nativeLanguages' => array(
//                    'language'
//                ),
//                'otherLanguages' => array(
//                    'language',
//                    'levelReading',
//                    'levelSpoken'
//                ),
//                'summary'
//            )
//        ));
    }

}
