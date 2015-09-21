<?php

namespace Core\Html2Doc;
use Zend\EventManager\EventManagerInterface;

interface DocInterface {
    public function attach(EventManagerInterface $events);
    
    public function attachMvc(EventManagerInterface $events);
    
}