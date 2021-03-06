<?php

namespace Applications\Entity;

use Core\Entity\AbstractIdentifiableEntity;
use Core\Entity\EntityInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Personal informations of a subscriber
 * 
 * @ODM\Document (collection="applications.subscribers", repositoryClass="Applications\Repository\Subscriber")
 */
class Subscriber extends AbstractIdentifiableEntity 
{  
    /**
     * name of the instance (other PPT, or jobboard etc.) who has
     * published the job posting. Technicaly it's a name of a referer 
     * of an application
     * 
     * @ODM\String 
     */
    protected $name;
    
    /**
     * Referer of a job posting. This referer must be submitted within the
     * application form
     * 
     * @ODM\String 
     **/
    protected $uri;
   
    /**
     * Name of the repository, which stores uri=>name
     * 
     * @var unknown
     */
    protected $repository;
    
    /**
     * Injects a repository
     * 
     * @param \Applications\Repository\Subscriber $repository
     * @return \Applications\Entity\Subscriber
     */
    public function injectRepository($repository) {
        $this->repository = $repository;
        return $this;
    }
    
    /**
     * Gets the repository for subscribers 
     * 
     * @param \Applications\Repository\Subscriber $repository
     * @return \Applications\Entity\unknown
     */
    protected function getRepository($repository) {
        return $this->repository;
    }
    
    /**
     * Gets the name of the instance, who has published the job ad.
     * 
     * @return String
     */
    public function getName()
    {
        if (empty($this->name)) {
            /* TODO try to fetch name from other PPT */
            $this->name = '';
#            $this->getRepository();            
        }
        return $this->name;
    }
    
    /**
     * Sets a name of the Instance, who has published the job
     * 
     * @param String $name
     * @return \Applications\Entity\Subscriber
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
   
    /**
     * Gets the job publishers URI
     * 
     * @return String
     */
    public function getUri()
    {
        return $this->uri;
    }
    
    /**
     * Sets the job publishers URI
     * 
     * @param String $uri
     * @return \Applications\Entity\Subscriber
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }
}