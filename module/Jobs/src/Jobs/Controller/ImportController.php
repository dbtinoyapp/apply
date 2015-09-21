<?php
namespace Jobs\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Stdlib\Parameters;
use Jobs\Entity\Job;
use Core\Entity\PermissionsInterface;

class ImportController extends AbstractActionController {

    public function saveAction() {
        
        $services = $this->getServiceLocator();
        
        $p = $this->params()->fromPost();
        $services->get('Log/Core/Ats')->info('Jobs/manage/saveJob ' . var_export($p, True));
        $user = $services->get('AuthenticationService')->getUser();
        //if (isset($user)) {
        //    $services->get('Log/Core/Ats')->info('Jobs/manage/saveJob ' . $user->login);
        //}
        $result = array('token' => session_id(), 'isSaved' => False);
        if (isset($user)) {
            $form = $services->get('FormElementManager')->get('Jobs/Import');
            // determine Job from Database 
            $id = $this->params()->fromPost('id');
            if (empty($id)) {
                $applyId = $this->params()->fromPost('applyId');
                if (empty($applyId)) {
                    // new Job (propably this branch is never used since all Jobs should have an apply-Id)
                    $entity = $services->get('repositories')->get('Jobs/Job')->create();
                } else {
                    $entity = $services->get('repositories')->get('Jobs/Job')->findOneBy(array("applyId" => (string) $applyId));
                    if (!isset($entity)) {
                        // new Job (the more likely branch)
                        $entity = $services->get('repositories')->get('Jobs/Job')->create(array("applyId" => (string) $applyId));
                    }
                }
            } else {
                $entity = $services->get('repositories')->get('Jobs/Job')->find($id);
            }
            //$services->get('repositories')->get('Jobs/Job')->store($entity);
            
            $form->bind($entity);
            if ($this->request->isPost()) {
                $params = $this->getRequest()->getPost();
                $params->datePublishStart = \Datetime::createFromFormat("Y-m-d",$params->datePublishStart);
                $form->setData($params);
                $result['post'] = $_POST;
                if ($form->isValid()) {
                    $entity->setUser($user);
//                    $group = $user->getGroup($entity->getCompany());
//                    if ($group) {
//                        $entity->getPermissions()->grant($group, PermissionsInterface::PERMISSION_VIEW);
//                    }
                    $services->get('repositories')->get('Jobs/Job')->store($entity);
                    $result['isSaved'] = true;
                } else {
                    $result['valid Error'] = $form->getMessages();
                }
            }
        } else {
            $result['message'] = 'session_id is lost';
        }
        //$services->get('Log/Core/Ats')->info('Jobs/manage/saveJob result:' . PHP_EOL . var_export($p, True));
        return new JsonModel($result);
    }

}

