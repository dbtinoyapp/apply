<?php

namespace Cv\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    
    public function indexAction()
    {
        
        $params = $this->getRequest()->getQuery();
        $params->set('by', 'all');
        $isUser = $this->acl()->isRole('user');
        if ($isUser) {
            return $this->forward()->dispatch('Cv\Controller\Manage', array('action' => 'viewByUser'));
        }
        
        $paginator = $this->paginator('Cv');
            
        $jsonFormat = 'json' == $this->params()->fromQuery('format');
        
        if ($jsonFormat) {
            $viewModel = new JsonModel();
            //$items = iterator_to_array($paginator);
        
            $viewModel->setVariables(array(
                'items' => $this->getServiceLocator()->get('builders')->get('JsonCv')
                                ->unbuildCollection($paginator->getCurrentItems()),
                'count' => $paginator->getTotalItemCount()
            ));
            return $viewModel;
        
        }

        return array(
            'resumes' => $paginator,
            'params' => $params
        );	
    }
}
