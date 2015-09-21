<?php

namespace Cv\Repository\Filter;

use Zend\Filter\FilterInterface;
use Core\Repository\Filter\AbstractPaginationQuery;
use Auth\Entity\UserInterface;
use Zend\Stdlib\Parameters;

class PaginationQuery extends AbstractPaginationQuery {

    protected $repositoryName = 'Cv/Cv';
    
    /**
     * Sortable fields
     *
     * @var array
     */
    protected $sortPropertiesMap = array(
        'date' => 'application.dateCreated.date',
    );
    
    protected $user;

    public function __construct(UserInterface $user = null) {
        $this->user = $user;
    }

    public function createQuery($params, $queryBuilder) {
        return $queryBuilder;
    }

}
