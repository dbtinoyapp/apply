<?php

namespace Cv\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Element\Collection;

class TrainingCollectionFactory implements FactoryInterface
{
    
    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
    */
    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $collection = new Collection('trainings');
        $collection->setLabel('Trainings & Seminars')
                   ->setCount(0)
                   ->setShouldCreateTemplate(true)
                   ->setAllowAdd(true)
                   ->setTargetElement($serviceLocator->get('TrainingFieldset'));
        return $collection;
    }
    
    
}