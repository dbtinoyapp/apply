<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\Parameters;

/**
 * Main Action Controller for Authentication module.
 */
class IndexController extends AbstractActionController {

    
  public function indexAction() {
        $this->layout('layout/layout-auth');
        $viewModel = new ViewModel();
        $services = $this->getServiceLocator();
        $loginForm = $services->get('FormElementManager')
                ->get('Auth/Login');
        $registrationForm = $services->get('FormElementManager')
                ->get('Auth/Registration');

        $ref = $this->params()->fromQuery('ref', false);

        if ($ref) {
            $req = $this->params()->fromQuery('req', false);
            if ($req) {
                $this->getResponse()->setStatusCode(403);
                $viewModel->setVariable('required', true);
            }
            $viewModel->setVariable('ref', $ref);
        }

        $viewModel->setVariable('loginForm', $loginForm);
        $viewModel->setVariable('registrationForm', $registrationForm);
        
        return $viewModel;
    }  
}
