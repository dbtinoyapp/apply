<?php

namespace Cv\Repository;

use Zend\ServiceManager\ServiceLocatorInterface;
use Core\Repository\AbstractRepository;
use Core\Entity\EntityInterface;
use Zend\Stdlib\ArrayUtils;
use Core\Repository\PaginationList;
/**
 * class for accessing CVs
 */
class Cv extends AbstractRepository
{
    /**
     * Gets a pointer to access a CV
     * 
     * @param array $params
     */
    public function getPaginatorCursor($params)
    {
        return $this->getPaginationQueryBuilder($params)
                    ->getQuery()
                    ->execute();
    }
    /**
     * Gets a query builder to search for CVs
     *
     * @param array $params
     * @return unknown
     */
    protected function getPaginationQueryBuilder($params)
    {
        $filter = $this->getService('filterManager')->get('Cv/PaginationQuery');
        
        $qb = $filter->filter($params, $this->createQueryBuilder());
    
        return $qb;
    }
    
    /**
     * Gets a result list of applications
     * 
     * @param array $params
     * @return \Cv\Repository\PaginationList
     */
    public function getPaginationList($params)
    {
        $qb = $this->getPaginationQueryBuilder($params);
        $cursor = $qb->hydrate(false)
                     ->select('_id')
                     ->getQuery()
                     ->execute();
        
        $list = new PaginationList(array_keys(ArrayUtils::iteratorToArray($cursor)));
        return $list;
    }
    
    
}