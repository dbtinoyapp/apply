<?php
namespace Auth\Controller\Plugin\SocialProfiles;

class Facebook extends AbstractAdapter
{
    
    
    protected function queryApi($api)
    {
        return $api->api('/me');
    }
}

