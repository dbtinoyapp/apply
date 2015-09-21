<?php
namespace Auth\View;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class InjectLoginInfoListener
{
    
    public function injectLoginInfo(MvcEvent $e)
    {
        if ( ($viewModel = $e->getViewModel()) instanceOf JsonModel) {
            // We don't need the login-info in a json response.
            return;
        }
        

        $loginInfoModel = new ViewModel();
        $loginInfoModel->setTemplate('auth/index/login-info');
        
        $viewModel->addChild($loginInfoModel, 'loginInfo');
    }
}