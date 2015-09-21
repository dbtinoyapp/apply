<?php
namespace Auth\View\Helper;
use Zend\View\Helper\AbstractHelper;

class LoginInfo extends AbstractHelper
{
     public function __invoke() {
         $values = array();
         return $this->getView()->render('auth/index/login-info', $values);
     }
}