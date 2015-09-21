<?php
namespace Auth;

use Auth\AuthenticationService;
use Zend\Authentication\Adapter\AdapterInterface;
use Core\Repository\RepositoryInterface;
use Auth\Entity\Filter\CredentialFilter;

class RegistrationService {

    protected $user;
    protected $repository;

    public function __construct(RepositoryInterface $repository) {
        $this->setRepository($repository);
    }

    /**
     * @return the $repository
     */
    public function getRepository() {
        return $this->repository;
    }

    /**
     * @param field_type $repository
     */
    public function setRepository($repository) {
        $this->repository = $repository;
        return $this;
    }

    public function getUser() {
        
        return $this->user;
    }
    public function createUser($data) {
        $filter        = new CredentialFilter();
        $this->user = $this->getRepository()->create(array(
           'login' => $data['login'],
           'password' => $data['credential'],
           'email' => $data['email'],
           'secret' => $filter->filter($data['credential']),
       ));
        $this->user->info->firstName = $data['firstName'];
        $this->user->info->lastName = $data['lastName'];
        $this->getRepository()->store($this->user);
        return $this;
    }
    public function authenticate(AdapterInterface $adapter = null) {
        $auth = new AuthenticationService($this->repository);
        return $auth->authenticate($adapter);
    }

}