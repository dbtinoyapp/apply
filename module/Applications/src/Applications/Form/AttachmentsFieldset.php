<?php
namespace Applications\Form;

use Zend\Form\Fieldset;

class AttachmentsFieldset extends Fieldset
{
    
     
    public function init()
    {
        $this->setName('attachment')
             ->setLabel('Attachment');
                     
        $this->add(array(
            'type' => 'file',
            'name' => 'file',
        ));
    }
}

