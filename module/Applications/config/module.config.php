<?php

return array(

    'doctrine' => array(
       'driver' => array(
            'odm_default' => array(
                'drivers' => array(
                    'Applications\Entity' => 'annotation',
                ),
            ),
        ),
        'eventmanager' => array(
            'odm_default' => array(
                'subscribers' => array(
                    '\Applications\Repository\Event\JobReferencesUpdateListener',
                    '\Applications\Repository\Event\UpdatePermissionsSubscriber',
                    '\Applications\Repository\Event\UpdateFilesPermissionsSubscriber',
                ),
            ),
        ),
    ),
    
    'Applications' => array(
        'dashboard' => array(
            'enabled' => true,
            'widgets' => array(
                'recentApplications' => array(
                    'controller' => 'Applications\Controller\Index',
                ),
            ),
        ),
    
        'allowedMimeTypes' => array('image', 'applications/pdf'),
        'settings' => array(
            'entity' => '\Applications\Entity\Settings',
            'navigation_order' => 1, 
            'navigation_label' => /*@translate*/ "E-Mail Templates",
//            'navigation_class' => 'ppt-icon ppt-icon-envelope'
        ),
    ),
    
    'service_manager' => array(
        'invokables' => array(
        ),
        'factories' => array(
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Applications\Controller\Index' => 'Applications\Controller\IndexController',
            'Applications\Controller\Manage' => 'Applications\Controller\ManageController',
            'Applications/CommentController' => 'Applications\Controller\CommentController',
            'Applications/Console' => 'Applications\Controller\ConsoleController',
            'Applications\Controller\MultiManage' => 'Applications\Controller\MultimanageController'
        ),
    ),
    
    'acl' => array(
        'rules' => array(
//            'guest' => array(
//                'allow' => array(
//                    'Applications\Controller\Manage' => 'detail',
//                ),
//            ),
//            'user' => array(
//                'allow' => array(
//                    'route/lang/applications',
//                    'Applications\Controller\Manage',
//                    'Entity/Application' => array(
//                        '__ALL__' => 'Applications/Access',
//                        
//                    ),
//                ),
//            ),
            'user' => array(
                'allow' => array(
                    'Applications\Controller\Manage' => 'detail',
                ),
            ),
            'recruiter' => array(
                'allow' => array(
                    'route/lang/applications',
                    'Applications\Controller\Manage',
                    'Entity/Application' => array(
                        '__ALL__' => 'Applications/Access',
                        
                    ),
                ),
            ),
            'admin' => array(
                'allow' => array(
                    'route/lang/applications',
                    'Applications\Controller\Manage',
                    'Entity/Application'
                ),
            ),
        ),
        'assertions' => array(
            'invokables' => array(
                'Applications/Access'      => 'Applications\Acl\ApplicationAccessAssertion',
            ),
        ),
    ),
    
    // Navigation
    'navigation' => array(
        'default' => array(
            'apply' => array(
                'label' => 'Applications',
                'route' => 'lang/applications',
                'order' => 20,
                'resource' => 'route/lang/applications',
                'query' => array(
                    'clear' => '1'
                ),
                'pages' => array(
                    'list' => array(
                        'label' => /*@translate*/ 'Overview',
                        'route' => 'lang/applications',
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    // Configure the view service manager
    'view_manager' => array(
        'template_path_stack' => array(
            'Applications' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/apply' => __DIR__ . '/../view/layout/layout.phtml',
            'applications/sidebar/manage' => __DIR__ . '/../view/sidebar/manage.phtml',
            'applications/index/disclaimer' => __DIR__ . '/../view/applications/index/disclaimer.phtml',
   //         'pagination-control' => __DIR__ . '/../../Core/view/partial/pagination-control.phtml',
        )
    ),
    'view_helpers' => array(
        
    ),
    
    
    'view_helper_config' => array(
        'headscript' => array(
            'lang/applications' => array('Core/js/jquery.barrating.min.js'),
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
//             'ApplicationFieldset' => '\Applications\Form\ApplicationFieldset',
//             'EducationFieldset' => '\Applications\Form\EducationFieldset',
//             'EmploymentFieldset' => '\Applications\Form\EmploymentFieldset',
//             'LanguageFieldset' => '\Applications\Form\LanguageFieldset',
             'Application/Create' => '\Applications\Form\CreateApplication',
             'Applications/Mail' => 'Applications\Form\Mail',
             'Applications/BaseFieldset' => 'Applications\Form\BaseFieldset', 
             'Applications/Privacy' => 'Applications\Form\PrivacyFieldset', 
             'Applications/SettingsFieldset' => 'Applications\Form\SettingsFieldset',
             'Applications/CommentForm' => 'Applications\Form\CommentForm',
             'Applications/CommentFieldset' => 'Applications\Form\CommentFieldset',
         ),
        'factories' => array(
            'Applications/ContactFieldset' => 'Applications\Form\ContactFieldsetFactory',
            'Applications/AttachmentsCollection' => '\Applications\Form\AttachmentsCollectionFactory',
            'Applications/AttachmentsFieldset' => '\Applications\Form\AttachmentsFieldsetFactory',
            'Applications/PrivacyPolicy' => '\Applications\Form\Element\PrivacyPolicyFactory',
            //'Applications/CarbonCopy' => 'Applications\Form\CarbonCopyFieldset',  
            'Applications/CarbonCopy' => '\Applications\Form\Element\CarbonCopyFactory',  
        ),
     ),
     
    'filters' => array(
        'invokables' => array(
            'Applications/ActionToStatus' => 'Applications\Filter\ActionToStatus',
        ),
        'factories'=> array(
            'Applications/PaginationQuery' => '\Applications\Repository\Filter\PaginationQueryFactory'
        ),
    ),
     
    'mails' => array(
        'invokables' => array(
            'Applications/NewApplication' => 'Applications\Mail\NewApplication',
            'Applications/Confirmation'   => 'Applications\Mail\Confirmation',
            'Applications/StatusChange'   => 'Applications\Mail\StatusChange',
            'Applications/Forward'        => 'Applications\Mail\Forward',
            'Applications/CarbonCopy'     => 'Applications\Mail\ApplicationCarbonCopy',
        ),
    ),
    /*
     * Settings for the application form.
     */
    'Applications/Settings' => array(
        'Form' => array(
            'showCv' => true, // show educations and work experiences in application form
            'showCarbonCopy' => true, // show 'send me my data in CC' in application form
            'showSocialProfiles' => true, // enables attaching social profiles to an application
            'showAttachments' => true, // enables file uploads for an application
        )
    ),
);
