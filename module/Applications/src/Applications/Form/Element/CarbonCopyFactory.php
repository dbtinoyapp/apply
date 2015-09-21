<?php

namespace Applications\Form\Element;

use Core\Form\Element\PolicyCheckFactory;

class CarbonCopyFactory extends PolicyCheckFactory 
{
    protected function getElement() {
        return new CarbonCopy();
    }
}