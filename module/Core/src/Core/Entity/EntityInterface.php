<?php
namespace Core\Entity;


/**
 * Model interface
 */
interface EntityInterface 
{
    public function __get($property);
    public function __set($property, $value);
    public function __isset($property);
  
}