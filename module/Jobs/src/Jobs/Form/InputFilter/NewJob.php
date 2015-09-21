<?php
namespace Jobs\Form\InputFilter;


class NewJob extends EditJob
{
    
    public function init()
    {
        parent::init();
        $input = $this->get('applyId')
                      ->getValidatorChain()
                      ->attachByName('Jobs/Form/UniqueApplyId');
        
    }
}

