<?php

namespace Cv\Form;

use Cv\Entity\OtherLanguage as OtherLanguageEntity;
use Core\Entity\Hydrator\EntityHydrator;

class OtherLanguageFieldset extends AbstractLanguageFieldset {

    public function init() {
        $this->setName('language')
                ->setHydrator(new EntityHydrator())
                ->setObject(new OtherLanguageEntity())
                ->setLabel('Language');

        parent::init();
        
        $valueArr = array();
        
        for($i=1; $i<=10; $i++) {
            $valueArr[$i] = $i;
        }

        $this->add(array(
            'name' => 'levelReading',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Reading',
                'value_options' => $valueArr
            ),
            'attributes' => array(
                'title' => /* @translate */ 'Reading',
                'class' => 'select2',
                'style' => 'width: 10%',
            )
        ));
//
        $this->add(array(
            'name' => 'levelSpoken',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Spoken',
                'value_options' => $valueArr
            ),
            'attributes' => array(
                'title' => /* @translate */ 'Spoken',
                'class' => 'select2',
                'style' => 'width: 10%',
            )
        ));
    }

}
