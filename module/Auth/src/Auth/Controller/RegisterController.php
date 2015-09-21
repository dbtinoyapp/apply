<?php

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\Parameters;

class RegisterController extends AbstractActionController {

    /**
     * Login with username and password
     */
    public function indexAction() {
        $this->layout('layout/layout-auth');
        $viewModel = new ViewModel();
        $services = $this->getServiceLocator();

        $registrationForm = $services->get('FormElementManager')
                ->get('Auth/Registration');

            if ($this->request->isPost()) {

                $post = $this->request->getPost();
                $registrationForm->setData($post);

                if ($registrationForm->isValid()) {
     

                    $data = $this->params()->fromPost();

                    $adapter = $services->get('Auth/Adapter/UserLogin');
                    $adapter->setIdentity($data['register']['login'])
                            ->setCredential($data['register']['credential']);

                    $reg = $services->get('RegistrationService');
                    $user = $reg->createUser($data['register']);
                    $result = $reg->authenticate($adapter);

                    if ($result->isValid()) {
                        $user = $reg->getUser(); 
                        $settings = $user->getSettings('Core');
                        $language = $settings->localization->language;
                        if (!$language) {
                            $headers = $this->getRequest()->getHeaders();
                            if ($headers->has('Accept-Language')) {
                                $locales = $headers->get('Accept-Language')->getPrioritized();
                                $language = $locales[0]->type;
                            } else {
                                $language = 'en';
                            }
                        }                        
                        $services->get('Log/Core/Ats')->info('User ' . $user->login . ' logged in');

                        $text = /* @translate */ 'Registration successful. You are now logged in.';
                        $this->notification()->success($text);                

                        $urlHelper = $services->get('ViewHelperManager')->get('url');
                        $url = $urlHelper('lang', array('lang' => $language));
                        return $this->redirect()->toUrl($url);
                    }
                } else { // form is invalid
                    $text = /* @translate */ 'Registration failed. Please check the marked fields.';
                    $this->notification()->error($text);

                }
            }
            
        $viewModel->setVariable('registrationForm', $registrationForm);
        
        return $viewModel;
    }

}
