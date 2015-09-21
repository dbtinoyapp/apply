<?php

namespace Cv\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * Main Action Controller for the application.
 * Responsible for displaying the home site.  
 *
 */
class ManageController extends AbstractActionController {

    protected $user;
    protected $form;
    protected $entity;
    protected $repositories;
    protected $services;

    public function detailFullAction() {
        
        $this->user = $this->auth()->getUser();
        $repository = $this->getServiceLocator()->get('repositories')->get('Cv/Cv');
        $cv = $repository->find($this->params('id'));

        $list = $this->paginationParams('Cv\Cv', $repository);
        
        $list->setCurrent($cv->id);
        
        $params = array('cv' => $cv, 'list' => $list);

        $view = new ViewModel($params);
        return $view;
    }

    public function viewByUserAction() {
        $params = array();
        
        $repository = $this->getServiceLocator()->get('repositories')->get('Cv/Cv');
        $this->user = $this->auth()->getUser();
        if ($this->user->getCv()) {
            $cv = $repository->find($this->user->getCv()->id);
            $params = array('cv' => $cv);
        }

        $view = new ViewModel($params);
        $view->setTemplate('cv/manage/detail-by-user');
        return $view;
    }

    public function newAction() {
        
        $this->user = $this->auth()->getUser();
        
        if ($this->user->getCv() && $this->acl()->isRole('user')) {
            return $this->forward()->dispatch('Cv\Controller\Manage', array('action' => 'edit', 'id' => $this->user->getCv()->id));
        }
        
        $this->services = $this->getServiceLocator();
        $this->repositories = $this->services->get('repositories');
        $cvs = $this->repositories->get('Cv/Cv');
        $user = $this->repositories->get('Auth/User');
        
        $this->form = $this->services->get('FormElementManager')->get('CvFormFull');


        
        $this->entity = $cvs->create(array("user" => $this->user));
        $this->entity->setUser($this->user);

        $this->entity->setContact($user->copyUserInfo($this->user->getInfo()));
        $this->repositories->store($this->entity);
        $this->form->bind($this->entity);
        if ($this->request->isPost()) {
            $this->save();
        }

        $model = $this->getViewModel($this->form, 'new');
        return $model;
    }

    public function editAction() {
        $this->services = $this->getServiceLocator();
        $this->repositories = $this->services->get('repositories');
        $cvs = $this->repositories->get('Cv/Cv');

        $this->user = $this->auth()->getUser();

        if (!$this->user->getCv() && $this->acl()->isRole('user')) {
            return $this->forward()->dispatch('Cv\Controller\Manage', array('action' => 'new'));
        }

        $this->form = $this->services->get('FormElementManager')->get('CvFormFull');
        $cv = $this->params('id') ? $cvs->find($this->params('id')) : $this->user->getCv();
        
        $this->entity = $cv;
        
        $this->form->bind($this->entity);
        if ($this->request->isPost()) {
            $appRepo = $this->repositories->get('Applications/Application');
            $app = $this->entity->application?  $appRepo->find($this->entity->application->id) : null;             
            
            $this->save($app);
            if ($this->form->isValid()) {
                //$text = /* @translate */ 'Your application has been successfully submitted.';
                //$this->notification()->success($text);
                //return $this->forward()->dispatch('Cv\Controller\Manage', array('action' => 'viewByUser'));
            }  
        }

        $model = $this->getViewModel($this->form, 'edit', array('id'=>$this->entity->id));

        return $model;
    }

    protected function getViewModel($form, $action, array $params = array()) {
        $variables = array(
            'form' => $form,
            'action' => $action,
        );
        $viewVars = array_merge($variables, $params);

        $model = new ViewModel($viewVars);
        $model->setTemplate("cv/manage/form");

        return $model;
    }

    protected function save($application = null) {
        $data = array_merge_recursive(
                $this->request->getPost()->toArray(), 
                $this->request->getFiles()->toArray()
        );

        $this->form->bind($this->request->getPost());
        $this->form->setData($data);
        if ($this->form->isValid()) {
            $jobRepository = $this->repositories->get('Jobs/Job');
            $job = $jobRepository->findJob($data['cv']['positionApplied']);  
            if(! is_null($application)) {
                $this->user = $application->user;
                $this->entity = $application->user->cv;
                $applicationEntity = $application;
            } else {
                $applicationEntity = new \Applications\Entity\Application();
                $applicationEntity->setStatus(new \Applications\Entity\Status());
                $applicationEntity->setJob($job);
            }
            
          
            $this->entity->setPositionApplied((object) $job);

            $applicationEntity->setUser($this->user);

            $permissions = $applicationEntity->getPermissions();
            $permissions->inherit($job->getPermissions());                

            $this->entity->setTitle($this->user->info->getFullName());
            $this->entity->setUser($this->user);
            $this->entity->user->setCv($this->entity);
            $this->entity->user->setInfo($this->entity->getContact());
            
            $this->entity->setApplication($applicationEntity);
            
            $this->repositories->store($applicationEntity);
            
            $text = /* @translate */ 'Form has been saved.';
            $this->notification()->success($text);
            
        } else { // form is invalid
            $text = /* @translate */ 'Saving failed. Please check the marked fields.';
            $this->notification()->error($text);
        }
    }

    public function populateAction() {
        $viewModel = new JsonModel();

        $type = 'Cv\Entity\\' . ucfirst(substr($this->params('type'), 0, -1));
        $viewModel->setVariables(array('results' => $type::getDefaults('skillName')));

        return $viewModel;
    }

}
