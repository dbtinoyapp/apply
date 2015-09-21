<?php
namespace Applications\Form;

use Zend\Form\Fieldset;

class BaseFieldset extends Fieldset
{
    
     
    public function init()
    {
        $this->setName('base')
             ->setLabel('Summary')
             ->setHydrator(new \Core\Entity\Hydrator\EntityHydrator());
             
                     
        $summary = new \Zend\Form\Element\Textarea('summary');
        $summary->setLabel('Summary')
            ->setAttribute('ckeditor', 10)        
            ->setAttribute('class', 'ckeditor');        
        
        $this->add($summary);           
        
    }
}

