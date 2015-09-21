<?php
namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Settings\Repository\Settings as SettingsRepository;
//use Settings\Repository\Settings;
/**
 * Main Action Controller for the application.
 * Responsible for displaying the home site.  
 *
 */
class IndexController extends AbstractActionController
{
    
    /**
     * Home site
     *
     */
    public function indexAction()
    { 
        $auth = $this->auth();
        
        if (!$auth->isLoggedIn()) {
            return $this->forward()->dispatch('Auth\Controller\Index', array('action' => 'index'));
        }
        
        $services = $this->getServiceLocator();
        $config   = $services->get('Config');
        
        $dashboardConfig = array(
            'controller' => 'Core\Controller\Index',
            'action'     => 'dashboard',
            'params'     => array()
        );
        if(is_callable($route = $config['login_redirect_route']) && $default = $route($auth->getUser())) {
            return $this->redirect()->toRoute($default);
        } elseif (isset($config['dashboard'])) {
            $dashboardConfig = array_merge(
                $dashboardConfig, 
                /** Intersect array to filter out invalid keys that might be in config */
                array_intersect_key($config['dashboard'], $dashboardConfig)
            );
        }
        
        extract($dashboardConfig); // $controller, $action, $params;
        $params['action'] = $action;
        return $this->forward()->dispatch($controller, $params);
        
    }
    
    public function dashboardAction()
    {
        $model = new ViewModel();
        $model->setTemplate('core/index/dashboard');
        
        $widgets = array();
        $modules = $this->getServiceLocator()->get('ModuleManager')->getLoadedModules();
        $widgets = array();
        foreach ($this->config('dashboard', array_keys($modules)) as $module => $cfg) {
            if (!isset($cfg['enabled']) || true !== $cfg['enabled']) {
                continue;
            }
            foreach ($cfg['widgets'] as $captureTo => $spec) {
                if (isset($spec['controller'])) {
                    $params = array('action' => 'dashboard');
                    if (isset($spec['params'])) {
                        $params = array_merge($params, $spec['params']);
                    }
                    
                    $viewModel = $this->forward()->dispatch($spec['controller'], $params);
                    // we ignore all errors and simply continue with the error template as widget content
                    $response = $this->getResponse();
                    if (200 != $response->getStatusCode()) {
                        $response->setStatusCode(200);
                        $viewModel = array(
                            'content' => 'Error loading widget.', 
                        );
                    }
                    if (!$viewModel instanceOf ViewModel) {
                        $viewModel = new ViewModel($viewModel);
                    }
                    if ($template = $viewModel->getTemplate()) {
                        $viewModel->setVariable('script', $template);
                    }
                } else if (isset($spec['script'])) {
                    $viewModel = new ViewModel(array('script' => $spec['script']));
                } else if (isset($spec['content'])) {
                    $viewModel = new ViewModel(array('content' => $spec['content']));
                }
            
                $viewModel->setTemplate('core/index/dashboard-widget.phtml');
                $model->addChild($viewModel, "dashboard_{$module}_{$captureTo}");
                //$widgets[] = $viewModel;
            }
        }
        //$model->setVariable('widgets', $widgets);
        return $model;
        
    }
    
    public function errorAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('error/index')
                  ->setVariable('message', 'An unexpected error had occured. Please try again later.');
        return $viewModel;
    }
}
