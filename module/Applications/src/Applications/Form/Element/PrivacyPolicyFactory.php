<?php
namespace Applications\Form\Element;

use Core\Form\Element\PolicyCheckFactory;

class PrivacyPolicyFactory extends PolicyCheckFactory 
{
    protected function getElement() {
        return new PrivacyPolicy();
    }
}