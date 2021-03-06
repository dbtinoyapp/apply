<?php


namespace Auth\Listener;

use Zend\Mvc\View\Http\ExceptionStrategy;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Auth\Exception\UnauthorizedAccessException;
use Zend\Http\PhpEnvironment\Response;
use Auth\Exception\UnauthorizedImageAccessException;


class UnauthorizedAccessListener extends ExceptionStrategy
{

    public function prepareExceptionViewModel(MvcEvent $e)
    {
        // Do nothing if no error in the event
        $error = $e->getError();
        if (empty($error)) {
            return;
        }
    
        // Do nothing if the result is a response object
        $result = $e->getResult();
        if ($result instanceof Response) {
            return;
        }
        
        // Do nothing if there is no exception or the exception is not
        // an UnauthorizedAccessException
        $exception = $e->getParam('exception');
        if (!$exception instanceOf UnauthorizedAccessException) {
            return;
        }
      
        $response = $e->getResponse();
        if (!$response) {
            $response = new Response();
            $e->setResponse($response);
        }
        
        /*
         * Return an image, if a image was requested.
         */
        if ($exception instanceOf UnauthorizedImageAccessException) {
            
            $image = __DIR__ . '/../../../../../public/images/unauthorized-access.png';
            $response->setStatusCode(403)
                     ->setContent(file_get_contents($image))
                     ->getHeaders()
                     ->addHeaderLine('Content-Type', 'image/png');
            $e->stopPropagation();
            $response->sendHeaders();
            //echo file_get_contents($image);
      
            
            //$response->stopped = true;
            return $response;
        }
        
        $auth = $e->getApplication()->getServiceManager()->get('AuthenticationService');
        
        if (!$auth->hasIdentity()) {
            $response->setStatusCode(Response::STATUS_CODE_403);
            $routeMatch = $e->getRouteMatch();
            $routeMatch->setParam('controller', 'Auth\Controller\Index');
            $routeMatch->setParam('action', 'index');
            $query = $e->getRequest()->getQuery();
            $ref = $e->getRequest()->getRequestUri();
            $ref = preg_replace('~^' . preg_quote($e->getRouter()->getBaseUrl()) . '~' , '', $ref);
            $query->set('ref', urlencode($ref));
            $query->set('req', 1);
            $result = $e->getApplication()->getEventManager()->trigger('dispatch', $e);
            $e->stopPropagation();
            return $result;
            $lang = $e->getRouteMatch()->getParam('lang', 'de');
            $ref = urlencode($e->getRequest()->getRequestUri());
            $url = $e->getRouter()->assemble(array('lang' => $lang), array(
                'name' => 'lang/auth',
                'query' => array(
                    'ref' => $ref,
                    'req' => 1
                )
            ));
            $response->getHeaders()->addHeaderLine('Location', $url);
            return $response;
        }
        $message = $exception->getMessage();
        $model = new ViewModel(array(
            'message'            => empty($message)
                                    ? /*translate*/ 'You are not permitted to access this resource.'
                                    : $message,
            'exception'          => $e->getParam('exception'),
            'display_exceptions' => $this->displayExceptions(),
        ));

        $model->setTemplate($this->getExceptionTemplate());
        $e->setResult($model);

       // $statusCode = $response->getStatusCode();
       // if ($statusCode === 200) {
            $response->setStatusCode(403);
       // }
    

    }
    
}