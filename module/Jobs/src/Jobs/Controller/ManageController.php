<?php

namespace Jobs\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Exception\UnauthorizedAccessException;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class ManageController extends AbstractActionController {

    public function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        
        
        /* This must run before onDispatch, because we could alter the action param */
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkPostRequest'), 10);
        
        return $this;
    }
    
    public function checkPostRequest(MvcEvent $e)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $routeMatch = $e->getRouteMatch();
            if (!$routeMatch) {
                return; // Let parent::onDispatch handle failure
            }
            /* All POST requests are handled by the saveAction! */
            $action = $routeMatch->getParam('action');
            $routeMatch->setParam('action', 'save');
            $routeMatch->setParam('origAction', $action);
        }
    }
    
    /**
     * Action called, when a new job should be created.
     * 
     */
    public function newAction()
    {
        $job = $this->getJob(/* create */ true);
        $this->acl($job, 'new');
        $user = $this->auth()->getUser();
        $job->setContactEmail($user->info->email);
        $job->setApplyId(
            uniqid(substr(md5($user->login), 0, 3))
        );
        $form  = $this->getForm($job); 
        $model = $this->getViewModel($form, 'new');
        
        return $model;
    }
    
    public function editAction()
    {
        $job = $this->getJob();
        $this->acl($job, 'edit');
        $form  = $this->getForm($job);
        $model = $this->getViewModel($form, 'edit'); 
        
        return $model;
    }
    
    public function saveAction()
    {
        $origAction = $this->params('origAction');
        $create     = 'new' == $origAction;
        $job        = $this->getJob($create);
        $this->acl($job, $origAction);
        $form       = $this->getForm($job);
        
        if ($form->isValid()) {
            if ($create) {
                $this->notification()->success(/*@translate*/ 'Job published.');
                $job->setStatus('active');
                $job->setUser($this->auth()->getUser());
                $this->getServiceLocator()->get('repositories')->persist($job);
            } else {
                $this->notification()->success(/*@translate*/ 'Job saved.');
            }
            return $this->redirect()->toRoute('lang/jobs');
        }
        
        $this->notification()->error(/*@translate*/ 'There were errors in the form');
        return $this->getViewModel($form, $origAction);
    }
    
    public function checkApplyIdAction()
    {
        $services = $this->getServiceLocator();
        $validator = $services->get('validatormanager')->get('Jobs/Form/UniqueApplyId');
        if (!$validator->isValid($this->params()->fromQuery('applyId'))) {
            return array(
                'ok' => false,
                'messages' => $validator->getMessages(),
            );
        }
        return array('ok' => true);
    }
    
    protected function getForm($job)
    {
        $services = $this->getServiceLocator();
        $forms    = $services->get('FormElementManager');
        $form     = $forms->get('Jobs/Job', array(
            'mode' => $job->id ? 'edit' : 'new'
        ));
        $form->bind($job);
        
        if ($this->getRequest()->isPost()) {
            $form->setData($_POST);
        }

        return $form;
    }
    
    protected function getJob($create = false)
    {
        $services     = $this->getServiceLocator();
        $repositories = $services->get('repositories');
        $repository   = $repositories->get('Jobs/Job');
        
        if ($create) {
            $job = $repository->create();
            return $job;
        }
        
        if ($this->getRequest()->isPost()) {
            $jobData = $this->params()->fromPost('job');
            $id      = isset($jobData['id']) ? $jobData['id'] : null;
        } else {
            $id = $this->params()->fromQuery('id');
        }

        if (!$id) {
            throw new \RuntimeException('Missing job id.');
        }

        $job = $repository->find($id);

        if (!$job) {
            throw new \RuntimeException('No job found with id "' . $id . '"');
        }

        return $job;
    }
    
    protected function getViewModel($form, $action, array $params = array())
    {
        $variables = array(
            'form' => $form,
            'action' => $action,
        );
        $viewVars  = array_merge($variables, $params);
        
        $model = new ViewModel($viewVars);
        $model->setTemplate("jobs/manage/form");
        
        return $model;
    }
    
    public function populateAction() {
        $viewModel = new JsonModel();

        $repository = $this->getServiceLocator()->get('repositories')->get('Jobs/Job');

        $jobs = $repository->getPopulate();
        $results = array();
        
        foreach ($jobs as $j) {
            array_push($results, array('id' => $j['_id']->{'$id'}, 'text' => $j['title']));
        }

        $viewModel->setVariables(array('results' => $results));

        return $viewModel;
    }
}

