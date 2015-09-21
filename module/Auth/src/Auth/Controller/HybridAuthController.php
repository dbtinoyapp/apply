<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class HybridAuthController extends AbstractActionController
{
    /**
     * HybridAuth endpoint.
     */
    public function indexAction()
    {
        \Hybrid_Endpoint::process();
    }
}

