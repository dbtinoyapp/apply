<?php

namespace Auth\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Auth\Entity\Filter\CredentialFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExternalApplication extends AbstractAdapter implements ServiceLocatorAwareInterface
{
    
    protected $applicationKey;
    protected $repository;
    protected $applicationKeys = array();
    protected $serviceManager;
    
    public function __construct($repository, $identity=null, $credential=null, $applicationKey=null)
    {
        $this->repository = $repository;
        $this->setIdentity($identity);
        $this->setCredential($credential);
        $this->setApplicationKey($applicationKey);
    }
    
    public function setServiceLocator(ServiceLocatorInterface $serviceManager) {
        $this->serviceManager = $serviceManager;
    }       
    
    public function getServiceLocator() {
        return $this->serviceManager;
    }
    
    public function getRepository()
    {
        return $this->repository;
    }
    
    public function setApplicationKey($applicationKey)
    {
        $this->applicationKey = $applicationKey;
        return $this;
    }
    
    public function getApplicationKey()
    {
        return $this->applicationKey;
    } 
    
    public function getApplicationIdentifier()
    {
        $keys = $this->getApplicationKeys();
        $ids  = array_flip($keys);
        $key  = $this->getApplicationKey();
        
        return isset($ids[$key]) ? $ids[$key] : null;
    }
    
    public function setApplicationKeys(array $applicationKeys)
    {
        $this->applicationKeys = $applicationKeys;
        return $this;
    }
    
    public function getApplicationKeys()
    {
        return $this->applicationKeys;
    }
    
    public function authenticate()
    {
        if (!in_array($this->getApplicationKey(), $this->getApplicationKeys())) {
            return new Result(Result::FAILURE, $this->getIdentity(), array('Invalid application key'));
        }
                
        $identity      = $this->getIdentity();
        $applicationId = '@' . $this->getApplicationIdentifier();
        $applicationIdIndex = strrpos($identity,$applicationId);
        //$login         = (0 < $applicationIdIndex &&  strlen($identity) - strlen($applicationId) == $applicationIdIndex)?substr($identity, 0, $applicationIdIndex):$identity;
        $login         = $identity;
        $users         = $this->getRepository();
        $user          = $users->findByLogin($login);
        $filter        = new CredentialFilter();
        $credential    = $this->getCredential();
        
        $loginSuccess = False;
        $loginResult = array();
        
        if (0 < $applicationIdIndex &&  strlen($identity) - strlen($applicationId) == $applicationIdIndex) {
            $this->serviceManager->get('Log/Core/Ats')->debug('User ' . $login . ', login with correct suffix: ');
            // the login ends with the applicationID, therefore use the secret key
            // the external login must be the form 'xxxxx@yyyy' where yyyy is the matching suffix to the external application key
            if (isset ($user)) {
                if ($user->secret == $filter->filter($credential)) {
                    $loginSuccess = True;
                }
                else {
                    $loginSuccess = False;
                    $this->serviceManager->get('Log/Core/Ats')->info('User ' . $login . ', secret: ' . $user->secret . ' != loginPassword: ' . $filter->filter($credential) . ' (' . $credential . ')');
                }
            }
            else {
                $user = $users->create(array(
                    'login' => $login,
                    'password' => $credential,
                    'secret' => $filter->filter($credential),
                ));
                $users->store($user);
                $loginSuccess = True;
                $loginResult = array('firstLogin' => True);
            }
        }   
        elseif (isset($user)) {
            $this->serviceManager->get('Log/Core/Ats')->debug('User ' . $login . ', login with noncorrect suffix: ');
            if ($user->credential == $filter->filter($credential)) {
                $this->serviceManager->get('Log/Core/Ats')->debug('User ' . $login . ', credentials are equal');
                $loginSuccess = True;
            }
            elseif (!empty($applicationId)) {
                $this->serviceManager->get('Log/Core/Ats')->debug('User ' . $login . ', credentials are not equal');
                // TODO: remove this code as soon as the secret key has been fully established
                // basically this does allow an external login with an applicationIndex match against the User-Password
                // the way it had been used in the start
                if ($user->credential == $filter->filter($credential)) {
                    $this->serviceManager->get('Log/Core/Ats')->debug('User ' . $login . ', credentials2 test');
                    $loginSuccess = True;
                }
            }
        }
        
        if (!$loginSuccess) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $identity, array('User not known or invalid credential'));
        }
        return new Result(Result::SUCCESS, $user->id, $loginResult);
    }
}