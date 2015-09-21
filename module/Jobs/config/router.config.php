<?php

// Routes
return array('router' => array('routes' => array('lang' => array('child_routes' => array(
    'jobs' => array(
        'type' => 'Literal',
        'options' => array(
            'route'    => '/jobs',
            'defaults' => array(
                'controller' => 'Jobs/Index',
                'action'     => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'manage' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/:action',
                    'defaults' => array(
                        'controller' => 'Jobs/Manage',
                    ),
                ),
                'may_terminate' => true,
            ),
            'check_apply_id' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/check-apply-id',
                    'defaults' => array(
                        'controller' => 'Jobs/Manage',
                        'action'     => 'check-apply-id',
                        'forceJson' => true, 
                    ),
                    
                ),
                'may_terminate' => true,
            ),
            'view'   => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/view',
                    'defaults' => array(
                        'controller' => 'Jobs/Index',
                        'action' => 'view'
                    ),
                ),
                'may_terminate' => true,
            ),
            'typeahead' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/typeahead',
                    'defaults' => array(
                        'controller' => 'Jobs/Index',
                        'action' => 'typeahead',
                        'forceJson' => true,
                    ),
                ),
                'may_terminate' => true,
            ),
            'populate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/populate/:type',
                    'defaults' => array(
                        'controller' => 'Jobs/Manage',
                        'action' => 'populate',
                        'id' => false,
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'save' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/saveJob',
            'defaults' => array(
                'controller' => 'Jobs/Import',
                'action' => 'save',
            ),
        ),
        'may_terminate' => true,
    ),
)))));
