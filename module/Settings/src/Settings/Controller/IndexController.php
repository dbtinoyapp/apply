<?php

namespace Settings\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Applications\Form\Application as ApplicationForm;
//use Applications\Model\Application as ApplicationModel;
//use Applications\Form\ApplicationHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\JsonModel;
use Zend\EventManager\Event;

/**
 * Main Action Controller for Applications module.
 *
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {   
        $services = $this->getServiceLocator();
        $translator = $services->get('translator');
        $moduleName = $this->params('module', 'Core');
        
        $settings = $this->settings($moduleName);
        $jsonFormat = 'json' == $this->params()->fromQuery('format');
        if (!$this->getRequest()->isPost() && $jsonFormat) {
            return $settings->toArray();
        }
        
        $mvcEvent = $this->getEvent();
        $mvcEvent->setParam('__settings_active_module', $moduleName);
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $formName = $moduleName . '/SettingsForm';
        if (!$formManager->has($formName)) {
            $formName = "Settings/Form";
        }
        
        // Fetching an distinct Settings
        
        
        // Write-Access is per default only granted to the own module - change that
        $settings->enableWriteAccess();

        
        //$settings = $this->settings();
        //$settingsAuth = $this->settings('auth');
        
        $form = $formManager->get($formName);
        
        // Binding the Entity to the Form
        $form->bind($settings);
        $data = $this->getRequest()->getPost();
        if (0 < count($data)) {
            $form->setData($data);
            
            if ($valid = $form->isValid()) {
                //$this->getServiceLocator()->get('repositories')->detach($settings);
                $vars = array(
                   'status' => 'success',
                   'text' => $translator->translate('Changes successfully saved') . '.');
                $event = new Event(
                    'SETTINGS_CHANGED',
                    $this,
                    array('settings' => $settings)
                );
                $this->getEventManager()->trigger($event);
            } else {
                $vars = array(
                   'status' => 'danger',
                   'text' => $translator->translate('Changes could not be saved') . '.');
            }
        }
        
        if ($jsonFormat) {
            return array('status' => 'success',
                         'settings' => $settings->toArray(),
                        'data' => $data,
                        'valid' => $valid,
                        'errors' => $form->getMessages());
        }

        $vars['form']=$form;
        return $vars;
    }
}