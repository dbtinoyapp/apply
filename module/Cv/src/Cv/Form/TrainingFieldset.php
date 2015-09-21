<?php

namespace Cv\Form;

use Cv\Entity\Training as TrainingEntity;
use Core\Entity\Hydrator\EntityHydrator;
use Zend\InputFilter\InputFilterProviderInterface;

class TrainingFieldset extends EducationFieldset implements InputFilterProviderInterface {

    public function init() {
        parent::init();
        $this->setName('training')
                ->setHydrator(new EntityHydrator())
                ->setObject(new TrainingEntity())
                ->setLabel('Training & Seminars');
        
        $this->remove('competencyName');
        $this->remove('currentIndicator');
    }
    public function getInputFilterSpecification() {
        return array(
            'competencyName' => array(
                'required' => false,
            ),
        );
    }
}
