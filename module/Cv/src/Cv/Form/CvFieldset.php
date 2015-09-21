<?php

namespace Cv\Form;

use Zend\Form\Fieldset;
use Core\Entity\Hydrator\EntityHydrator;
use Core\Form\Hydrator\Strategy\CollectionStrategy;
use Zend\InputFilter\InputFilterProviderInterface;
use Core\DocumentManager\DocumentManagerAwareInterface;

class CvFieldset extends Fieldset implements InputFilterProviderInterface, DocumentManagerAwareInterface {

    use \Core\DocumentManager\DocumentManagerAwareTrait;

    public function getHydrator() {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            $collectionStrategy = new CollectionStrategy();
            $hydrator->addStrategy('educations', $collectionStrategy)
                    ->addStrategy('employments', $collectionStrategy)
                    ->addStrategy('trainings', $collectionStrategy)
                    ->addStrategy('computingSkills', $collectionStrategy)
                    ->addStrategy('nativeLanguages', $collectionStrategy)
                    ->addStrategy('otherLanguages', $collectionStrategy)
            ;

            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }

    public function init() {

        $this->setName('cv');
        $this->setAttribute('id', 'cv');
        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'positionApplied',
            'options' => array(
                'label' => /* @translate */ 'Position Applied',
                'target_class' => 'Jobs\Entity\Job',
                'object_manager' => $this->getDocumentManager(),
                'property' => 'title',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('status' => 'active'),
                    ),
                ),
                'empty_option' => 'Please Select',
            ),
            'attributes' => array(
                'class' => 'select2',
            ),
        ));
        $this->add(array(
            'type' => 'checkbox',
            'name' => 'freshGraduateIndicator',
            'options' => array(
                'label' => /* @translate */ 'Fresh Graduate?'
            ),
            'attributes' => array(
                'class' => 'freshGradToggle',
            ),
        ));
        $this->add(array(
            'type' => 'Collection',
            'name' => 'educations',
            'options' => array(
                'label' => /* @translate */ 'Educations',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'EducationFieldset'
                )
            ),
        ));
        $this->add(array(
            'type' => 'Collection',
            'name' => 'trainings',
            'options' => array(
                'label' => /* @translate */ 'Certificates',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'TrainingFieldset'
                )
            ),
        ));

        $this->add(array(
            'type' => 'Collection',
            'name' => 'employments',
            'options' => array(
                'label' => /* @translate */ 'Employment History',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'EmploymentFieldset'
                )
            ),
            'attributes' => array(
                'class' => 'hidden',
            ),
        ));

        $this->add(array(
            'type' => 'Collection',
            'name' => 'computingSkills',
            'options' => array(
                'label' => /* @translate */ 'Computing Skills',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'ComputingSkillFieldset'
                )
            ),
        ));
        $this->add(array(
            'type' => 'Collection',
            'name' => 'nativeLanguages',
            'options' => array(
                'label' => /* @translate */ 'Native Language',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'NativeLanguageFieldset'
                )
            ),
        ));

        $this->add(array(
            'type' => 'Collection',
            'name' => 'otherLanguages',
            'options' => array(
                'label' => /* @translate */ 'Other Language',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'OtherLanguageFieldset'
                )
            ),
        ));
        $description = new \Zend\Form\Element\Textarea('summary');
        $description->setLabel('Summary')
                ->setAttribute('class', 'ckeditor')
                ->setAttribute('rows', 8);

        $this->add($description);
    }

    public function getInputFilterSpecification() {
        return array(
//            'summary' => array(
//                'required' => true,
//                'filters' => array(
//                    array('name' => '\Zend\Filter\StringTrim'),
//                ),
//                'validators' => array(
//                    new \Zend\Validator\NotEmpty(),
//                    new \Zend\Validator\StringLength(array('min' => 5))
//                ),
//            ),
        );
    }

}
