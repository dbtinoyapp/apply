<?php
namespace Cv\Repository\Filter;

use Zend\Filter\FilterInterface;
use Core\Repository\Filter\AbstractPaginationQuery;
use Auth\Entity\UserInterface;

class JsonPaginationQuery extends PaginationQuery
{
    public function createQuery($params, $queryBuilder)
    {
        parent::createQuery($params, $queryBuilder);
        $queryBuilder
                     ->hydrate(false);
            
        
        
        return $queryBuilder->getQuery();
    }
    
    public function getPaginatorAdapterClassC()
    {
        
        //return '\Core\Repository\DoctrineMongoODM\PaginatorAdapter\EagerCursor';
    }
}

