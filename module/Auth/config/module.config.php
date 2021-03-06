<?php

return array(
    
    'doctrine' => array(
        'driver' => array(
            'odm_default' => array(
                'drivers' => array(
                    'Auth\Entity' => 'annotation',
                ),
            ),
        ),
    ),

    
    'service_manager' => array(
        'invokables' => array(
            'SessionManager' => '\Zend\Session\SessionManager',
        ),
        'factories' => array(
            'HybridAuth' => '\Auth\Service\HybridAuthFactory',
            'HybridAuthAdapter' => '\Auth\Service\HybridAuthAdapterFactory',
            'ExternalApplicationAdapter' => '\Auth\Service\ExternalApplicationAdapterFactory',
            'Auth/Adapter/UserLogin' => '\Auth\Service\UserAdapterFactory',
            'RegistrationService' => '\Auth\Service\RegistrationServiceFactory',
            'AuthenticationService' => '\Auth\Service\AuthenticationServiceFactory',
            'UnauthorizedAccessListener' => '\Auth\Service\UnauthorizedAccessListenerFactory',
            'Auth/CheckPermissionsListener' => 'Acl\Listener\CheckPermissionsListenerFactory',
            'Acl' => '\Acl\Service\AclFactory',
            'Acl/AssertionManager' => 'Acl\Assertion\AssertionManagerFactory',
        ),
        'aliases' => array(
            'assertions' => 'Acl/AssertionManager',
        )
    ),
    
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Index' => 'Auth\Controller\IndexController',
            'Auth\Controller\Login' => 'Auth\Controller\LoginController',
            'Auth\Controller\Register' => 'Auth\Controller\RegisterController',
            'Auth\Controller\Manage' => 'Auth\Controller\ManageController',
            'Auth/ManageGroups' => 'Auth\Controller\ManageGroupsController',
            'Auth\Controller\Image' => 'Auth\Controller\ImageController',
            'Auth\Controller\HybridAuth' => 'Auth\Controller\HybridAuthController',
            'Auth/SocialProfiles' => 'Auth\Controller\SocialProfilesController',
        ),
    ),
    
    'controller_plugins' => array(
        'invokables' => array(
            'Auth' => '\Auth\Controller\Plugin\Auth',
        ),
        'factories' => array(
            'Auth/SocialProfiles' => 'Auth\Controller\Plugin\Service\SocialProfilesFactory',
            'Acl' => '\Acl\Controller\Plugin\AclFactory',
        )
    ),
    
    'hybridauth' => array(
        "Facebook" => array (
            "enabled" => true,
            "keys"    => array ( "id" => "", "secret" => "" ),
            "scope"	  => 'email, user_about_me, user_birthday, user_hometown, user_website',
        ),
        "LinkedIn" => array (
            "enabled" => true,
            "keys"    => array ( "key" => "", "secret" => "" ),
        ),
//        "XING" => array (
//            "enabled" => true,
//            // This is a hack due to bad design of Hybridauth
//            // There's no simpler way to include "additional-providers"
//            "wrapper" => array ( 
//                'class' => 'Hybrid_Providers_XING',
//                'path' => __FILE__,
//            ),
//            "keys"    => array ( "key" => "", "secret" => "" ),
//        ),
    ),
    
    // Module specific configuration
    
    'Auth' => array(
        // Allowed external Applications
        // applications[USERPOSTFIX] => AppKey
        'external_applications' => array(
            'ams' => 'AmsAppKey',
        ),
    ),
    
    // Routes
    'router' => array(
        'routes' => array(
            'lang' => array(
                'child_routes' => array(
                    'auth' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/login',
                            'defaults' => array(
                                'controller' => 'Auth\Controller\Index',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'intern-login' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/intern-login',
                            'defaults' => array(
                                'controller' => 'Auth\Controller\Login',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'intern-register' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/intern-register',
                            'defaults' => array(
                                'controller' => 'Auth\Controller\Register',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'my' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/my/:action',
                            'defaults' => array(
                                'controller' => 'Auth\Controller\Manage',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'my-groups' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/my/groups[/:action]',
                            'defaults' => array(
                                'controller' => 'Auth/ManageGroups',
                                'action' => 'index'
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'auth-provider' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/login/:provider',
                    'constraints' => array(
                       // 'provider' => '.+',
                    ),
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Login',
                        'action' => 'login'
                     ),
                ),
            ),
            'auth-hauth' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login/hauth',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\HybridAuth',
                        'action' => 'index'
                    ),
                ),
            ),
            // This route must be after auth-provider for the
            // last in first out order of the route stack!
            // @TODO implement auth-provider and auth-extern as child routes
            //       to a new auth-login route.
            'auth-extern' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login/extern',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Login',
                        'action'     => 'login-extern',
                        'forceJson'  => true,
                    ),
                ),
                'may_terminate' => true,
            ),
            'auth-social-profiles' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/auth/social-profiles',
                    'defaults' => array(
                        'controller' => 'Auth/SocialProfiles',
                        'action'     => 'fetch',
                    ),
                ),
            ),
            
            'auth-group' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/auth/groups',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Login',
                        'action'     => 'group',
                        'forceJson'  => true,
                    ),
                ),
                'may_terminate' => true,
            ),
            'auth-logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Login',
                        'action' => 'logout',
                    ),
                ),
            ),
            'user-image' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user/image/:id',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Image',
                        'action' => 'index',
                        'id' => 0,
                    ),
                ),
            ),
        ),
    ),
    
    /*
     * Acl definitions.
     * Format
     * array($ROLE[:$PARENT] => $RESOURCES);
     * 
     * $ROLE: Role name
     * $PARENT: Coma separated list of roles to inherit from.
     * $RESOURCES: array of resources
     *      a resource is 
     *      1. a string: taken as resource name
     *                   (when prefixed with "!", a deny rule is created.)
     *      1.1 the "null" value: allow on all resources.
     *      2. a key => string pair:
     *          key is the resource name (optionally prefixed with "!")
     *          if key is "__ALL__" rule apply to all resources.
     *          string is the privilege name
     *      3. a key => array pair:
     *              key is the resource name (optionally prefixed with "!")
     *              array are the privileges which each of is
     *              1. a string: Taken as privilege name
     *              2. a key => string pair:
     *                  key is the privilege name
     *                  string is the name of the assertion class to instantiate and use with this rule.
     *              3. a key => array pair:
     *                  key is the privilege name
     *                  array is:
     *                      index 0: Name of the assertion class,
     *                      index 1: array of parameters to pass to the constructor of the assertion.
     *                  
     */
    'acl' => array(
        'roles' => array(
            'guest',
            'user' => 'guest',
            'recruiter' => 'user',
            'admin' => 'recruiter'
        ),
        
        'public_roles' => array(
            /*@translate*/ 'user', 
            /*@translate*/ 'recruiter',
        ),
        
        'rules' => array(
            'guest' => array(
                'allow' => array(
                    'route/lang/auth',
                    'route/auth-provider',
                    'route/auth-hauth',
                    'route/auth-extern',
                ),
            ),
            'user' => array(
                'allow' => array(
                    'route/auth-logout',
                    'route/lang/my',
                ),
                'deny' => array( 
                    'route/lang/auth',
                    'route/auth-provider',
                    'route/auth-extern',
                ),
            ),
            'recruiter' => array(
                'allow' => array(
                    'route/lang/my-groups'
                ),
            ),
            'admin' => array(
                'allow' => "__ALL__",
                'deny' => array(
                    'route/lang/auth',
                    'route/auth-provider',
                    'route/auth-hauth',
                    'route/auth-extern',
                ),
            ),
        ),
    ),
    
    // Navigation
//     'navigation' => array(
//         'default' => array( 
//             'login' => array(
//                 'label' => 'Login',
//                 'route' => 'auth',
//                 'pages' => array(
//                     'facebook' => array(
//                         'label' => 'Facebook',
//                         'route' => 'auth/auth-providers',
//                         'params' => array(
//                             'provider' => 'facebook'
//                         ),
//                      ),
//                 ),
//             ),
//         ),
//     ),
    
   
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    
    // Configure the view service manager
    'view_manager' => array(
        'template_map' => array(
            'form/auth/my-profile' => __DIR__ . '/../view/form/my-profile.phtml',
            'form/auth/my-profile-image-readonly' => __DIR__ . '/../view/form/my-profile-image-readonly.phtml',
            'auth/form/userselect' => __DIR__ . '/../view/form/userselect.phtml',
            'auth/form/social-profiles-fieldset' => __DIR__ . '/../view/form/social-profiles-fieldset.phtml',
            'auth/form/social-profiles-button' => __DIR__ . '/../view/form/social-profiles-button.phtml',
            'auth/sidebar/groups-menu' => __DIR__ . '/../view/sidebar/groups-menu.phtml',
//            'layout/layout' => __DIR__ . '/../view/layout/layout-auth.phtml'
        ),
    
        'template_path_stack' => array(
            'Auth' => __DIR__ . '/../view',
        ),
    ),
    
    'filters' => array(
        'invokables' => array(
            'Auth/StripQueryParams' => '\Auth\Filter\StripQueryParams',
            'Auth/Entity/UserToSearchResult' => '\Auth\Entity\Filter\UserToSearchResult',
        ),
    ),
    
    'validators' => array(
        'factories' => array(
            'Auth/Form/UniqueGroupName' => 'Auth\Form\Validator\UniqueGroupNameFactory',
        ),
    ),
    
    'view_helpers' => array(
        'invokables' => array(
            'buildReferer' => '\Auth\View\Helper\BuildReferer',
            'loginInfo' => '\Auth\View\Helper\LoginInfo',
        ),   
        'factories' => array(
            'auth' => '\Auth\Service\AuthViewHelperFactory',
            'acl'  => '\Acl\View\Helper\AclFactory',
         ),
    ),
    
    'form_elements' => array(
        'invokables' => array( 
            'Auth/Login' => 'Auth\Form\Login',
            'Auth/Registration' => 'Auth\Form\Registration',
            'Auth/RegistrationFieldset' => '\Auth\Form\RegistrationFieldset',
            'user-info' => 'Auth\Form\UserInfo',
            'user-profile' => 'Auth\Form\UserProfile',
            
            'user-password' => 'Auth\Form\UserPassword',
            'Auth/UserPasswordFieldset' => 'Auth\Form\UserPasswordFieldset', 
            'Auth/UserBaseFieldset' => 'Auth\Form\UserBaseFieldset', 
            'Auth/Group' => 'Auth\Form\Group',
            'Auth/Group/Data' => 'Auth\Form\GroupFieldset',
            'Auth/Group/Users' => 'Auth\Form\GroupUsersCollection',
            'Auth/Group/User'  => 'Auth\Form\GroupUserElement',
            'Auth/SocialProfilesButton' => 'Auth\Form\Element\SocialProfilesButton',
            
        ),
        'factories' => array(
            'Auth/RoleSelect' => 'Auth\Form\RoleSelectFactory',
            'Auth/UserInfoFieldset' => 'Auth\Form\UserInfoFieldsetFactory',
            'Auth/SocialProfilesFieldset' => 'Auth\Form\SocialProfilesFieldsetFactory',
        )
    ),
);
