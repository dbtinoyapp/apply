<?php

namespace Jobs\Form;

use Zend\Form\Fieldset;
use Core\Entity\Hydrator\EntityHydrator;

class JobFieldset extends Fieldset {

    public function getHydrator() {
        if (!$this->hydrator) {
            $hydrator = new EntityHydrator();
            /*
              $datetimeStrategy = new Hydrator\DatetimeStrategy();
              $datetimeStrategy->setHydrateFormat(Hydrator\DatetimeStrategy::FORMAT_MYSQLDATE);
              $hydrator->addStrategy('datePublishStart', $datetimeStrategy);
             */
            $this->setHydrator($hydrator);
        }
        return $this->hydrator;
    }

    public function init() {
        $this->setAttribute('id', 'job-fieldset');
        $this->setLabel('Job details');

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Text',
            'name' => 'title',
            'options' => array(
                'label' => /* @translate */ 'Job title'
            ),
        ));

        $this->add(array(
            'type' => 'Jobs/ApplyId',
            'name' => 'applyId',
            'options' => array(
                'label' => /* @translate */ 'Apply Identifier'
            ),
        ));
        $this->add(array(
            'type' => 'Location',
            'name' => 'location',
            'options' => array(
                'label' => /* @translate */ 'Location'
            ),
        ));
        
        $description = new \Zend\Form\Element\Textarea('description');
        $description->setLabel('Description')
            ->setAttribute('class', 'ckeditor')        
            ->setAttribute('rows', 15);        
        
        
        $this->add($description);        

        $duty = new \Zend\Form\Element\Textarea('responsibility');
        $duty->setLabel('Responsibilities')
            ->setAttribute('class', 'ckeditor')        
            ->setAttribute('rows', 15);        
        
        
        $this->add($duty);
        
        $qualification = new \Zend\Form\Element\Textarea('qualification');
        $qualification->setLabel('Qualifications')
            ->setAttribute('class', 'ckeditor')        
            ->setAttribute('rows', 15);        
        
        
        $this->add($qualification);
        
        $this->add(array(
            'type' => 'Text',
            'name' => 'contactEmail',
            'options' => array(
                'label' => /* @translate */ 'Contact email'
            ),
        ));


        $this->add(array(
            'type' => 'Text',
            'name' => 'reference',
            'options' => array(
                'label' => /* @translate */ 'Reference number'
            ),
        ));

//       $this->add(array(
//           'type' => 'Core/PermissionsCollection'
//       ));
    }

}
