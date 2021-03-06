<?php
namespace Core\Mail;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Transport\SmtpOptions;

class MailServiceConfig extends Config
{
    public function getTransport()
    {
        return isset($this->config['transport'])
            ? $this->config['transport']
            : new \Zend\Mail\Transport\Smtp();
    }
    
    public function getFrom()
    {
        if (isset($this->config['from'])) {
            if (is_string($this->config['from'])) {
                return $this->config['from'];
            }
            if (!is_array($this->config['from']) || !isset($this->config['from']['email'])) {
                return null;
            }
            if (isset($this->config['from']['name'])) {
                return array($this->config['from']['email'] => $this->config['from']['name']);
            }
            return $this->config['from']['email'];
        }
        return null;
    }

    public function getMailer()
    {
        return isset($this->config['mailer'])
            ? $this->config['mailer']
            : 'Core/Mailer';
    }
    public function getOption()
    {
        return new SmtpOptions($this->config['option']);
    }
    
    public function configureServiceManager(ServiceManager $serviceManager)
    {
        if (!$serviceManager instanceOf MailService) {
            throw new \DomainException('Can only configure instances of \Core\Mail\MailService.');
        }
        
        parent::configureServiceManager($serviceManager);
        
        $serviceManager->setTransport($this->getTransport());
        $serviceManager->setOption($this->getOption());
        $serviceManager->setFrom($this->getFrom());
        $serviceManager->setMailer($this->getMailer());
    }


}

