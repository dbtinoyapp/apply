<?php

namespace Applications\Filter;

use Zend\Filter\FilterInterface;
use Applications\Entity\StatusInterface as Status;

class ActionToStatus implements FilterInterface
{

    protected $actionToStatusMap = array(
        'hire' => Status::HIRED,
        'process' => Status::PROCESS,
        'reset' => Status::NEWLY,
    );
    
    public function filter($value)
    {
        return isset($this->actionToStatusMap[$value])
            ? $this->actionToStatusMap[$value]
            : false;
    }
}