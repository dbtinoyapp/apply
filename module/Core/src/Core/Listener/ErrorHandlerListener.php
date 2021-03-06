<?php
namespace Core\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Log\LoggerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Application;
use Zend\Log\Logger;

class ErrorHandlerListener implements ListenerAggregateInterface
{
    
    
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();
    
    protected $log;
    
    protected $redirect;
    
    public function __construct(LoggerInterface $log, $redirect = null)
    {
        $this->log = $log;
        if ($redirect) {
            $this->redirect = $redirect;
            register_shutdown_function(array($this, 'handleFatalError'));
        }
    }
    
    public function getLog()
    {
        return $this->log;
    }
    
    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @param  integer $priority
    */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'), $priority);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'handleError'), $priority);
    }
    
    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function handleError(MvcEvent $event)
    {
        // Do nothing if no error in the event
        $error = $event->getError();
        if (empty($error)) {
            return;
        }
        
        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_FOUND:
            case Application::ERROR_CONTROLLER_INVALID:
            case Application::ERROR_ROUTER_NO_MATCH:
                // Specifically not handling these
                return;
        
            case Application::ERROR_EXCEPTION:
            default:
                $exception = $event->getParam('exception');
                $logMessages = array();
            
                do {
                    $priority = Logger::ERR;
                    
                    $extra = array(
                        'file'  => $exception->getFile(),
                        'line'  => $exception->getLine(),
                        'trace' => $exception->getTrace(),
                    );
                    if (isset($exception->xdebug_message)) {
                        $extra['xdebug'] = $exception->xdebug_message;
                    }
            
                    $logMessages[] = array(
                        'priority' => $priority,
                        'message'  => $exception->getMessage(),
                        'extra'    => $extra,
                    );
                    $exception = $exception->getPrevious();
                } while ($exception);
            
                foreach (array_reverse($logMessages) as $logMessage) {
                    $this->log->log($logMessage['priority'], $logMessage['message'], $logMessage['extra']);
                }

                break;
        }
    }
    
    
    public function handleFatalError()
    {
        $error = error_get_last();
        
        if($error) {
             $this->log->err($error['message'], array(
                 'errno' => $error['type'],
                 'file' => $error['file'],
                 'line' => $error['line']
             ));
             if ($this->redirect && 
                 ($error['type'] & (E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR))
             ) {
                 if (is_callable($this->redirect)) {
                     call_user_func($this->redirect);
                 }
            }
        }
    }
    

}

