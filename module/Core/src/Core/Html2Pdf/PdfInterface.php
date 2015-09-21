<?php

namespace Core\Html2Pdf;
use Zend\EventManager\EventManagerInterface;

interface PdfInterface {
    public function attach(EventManagerInterface $events);
    
    public function attachMvc(EventManagerInterface $events);
    
}