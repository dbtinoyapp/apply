<?php
namespace Auth\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Core\Entity\Hydrator\EntityHydrator;

class GroupFieldset extends Fieldset implements InputFilterProviderInterface
{
    /**
     * Initialises the fieldset
     * @see \Zend\Form\Element::init()
     */
    public function init()
    {
        $this->setName('data')
             ->setLabel('Group data')
             ->setUseAsBaseFieldset(true)
             ->setHydrator(new EntityHydrator());
        
        $this->add(array(
            'type' => 'Hidden',
            'name' => 'id',
        ));
        
        $this->add(array(
            'type' => 'Text',
            'name' => 'name',
            'options' => array(
                'label' => /*@translate*/ 'Group name',
                'description' => /* @translate */ 'Select a group name. You can add users to your group and then work together on jobs and job applications.',
                    
            ),
        ));
        
        $this->add(array(
            'type' => 'Auth/Group/Users',
        ));
        
    }
    
    /**
     * {@inheritDoc}
     * @see \Zend\InputFilter\InputFilterProviderInterface::getInputFilterSpecification()
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
                'validators' => array(
                    array('name'    => 'Auth/Form/UniqueGroupName',
                          'options' => array(
                            'allowName' => 'edit' == $this->getOption('mode') 
                                          ? $this->getObject()->getName()
                                          : null
                            )
                    ),
                ),
            ),
        );
    }
}

