<?php
namespace Applications\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class StatusChanger extends AbstractPlugin
{
    
    public function __invoke()
    {
        return $this;    
    }
    
    
    
    public function mustSendMail()
    {
        $controller = $this->getController();
        $action     = $controller->params('do', 'hire');
        $isPost     = $controller->getRequest()->isPost();
        
        return in_array($action, array('process', 'deny')) && !$isPost;
    }
}

