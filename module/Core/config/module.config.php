<?php

$doctrineConfig = include __DIR__ . '/doctrine.config.php';


return array(

    'doctrine' => $doctrineConfig,
    
    'Core' => array(
        'settings' => array(
            'entity' => '\\Core\\Entity\\SettingsContainer',
            'navigation_label' => /* @translate */ 'General Settings',
//            'navigation_class' => 'ppt-icon ppt-icon-settings'
        ),
    ),


    // Logging
    'log' => array(
        'Log/Core/Ats' => array(
            'writers' => array(
                 array(
                     'name' => 'stream',
                    'priority' => 1000,
                    'options' => array(
                         'stream' => __DIR__ .'/../../../log/ats.log',
                    ),
                ),
            ),
        ),
        'Log/Core/Mail' => array(
            'writers' => array(
                 array(
                     'name' => 'stream',
                    'priority' => 1000,
                    'options' => array(
                         'stream' => __DIR__ .'/../../../log/mails.log',
                    ),
                ),
            ),
        ),
        'ErrorLogger' => array(
            'service' => 'Core/ErrorLogger',
            'config'  => array(
                'stream' => __DIR__ . '/../../../log/error.log',
                'log_errors' => true,
                'log_exceptions' => true,
            ),
        ),
//         array(
//             'writers' => array(
//                 array(
//                     'name' => 'stream',
//                     'priority' => 1000,
//                     'options' => array(
//                         'stream' => __DIR__ . '/../../../log/error.log',
//                         'formatter' => 'ErrorHandler',
//                         'filters' => array(
//                             'Core\Log\Filter\PhpError'
//                         ),
//                     ),
//                 ),
//             ),
//             'exceptionhandler' => true,
//             'errorhandler' => true,
//         ),
    ),
           
    // Routes
    'router' => array(
        'routes' => array(
            'lang' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/:lang',
                    'defaults' => array(
                        'controller' => 'Core\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'error' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/error',
                            'defaults' => array(
                                'controller' => 'Core\Controller\Index',
                                'action' => 'error',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'mailtest' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/mail',
                            'defaults' => array(
                                'controller' => 'Core\Controller\Index',
                                'action' => 'mail',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'file' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/file/:filestore/:fileId[/:fileName]',
                    'defaults' => array(
                        'controller' => '\Core\Controller\File',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    
    'acl' => array(
        'rules' => array(
            'user' => array(
                'allow' => array(
                    //'route/file',
                    'Entity/File' => array(
                        '__ALL__' => 'Core/FileAccess'
                    ),
                ),
                'deny' => array(
                    'route/lang/dashboard'
                ),
            ),
            'recruiter' => array(
                'allow' => array(
                    'route/lang/dashboard',
                )
            ),
            'admin' => array(
                'allow' => array(
                    'route/lang/dashboard',
                )
            ),
            
        ),
        'assertions' => array(
            'invokables' => array(
                'Core/FileAccess' => 'Core\Acl\FileAccessAssertion',
            ),
        ),
    ),
    
    // Setup the service manager
    'service_manager' => array(
        'invokables' => array(
            'configaccess' => 'Core\Service\Config',
            'Core/DoctrineMongoODM/RepositoryEvents' => '\Core\Repository\DoctrineMongoODM\Event\RepositoryEventsSubscriber',
        ),
        'factories' => array(
            'Core/DocumentManager' => 'Core\Repository\DoctrineMongoODM\DocumentManagerFactory',
            'Core/RepositoryService' => 'Core\Repository\RepositoryServiceFactory',
            'Core/MailService' => '\Core\Mail\MailServiceFactory',
            'Core/html2pdf' => '\Core\Html2Pdf\PdfServiceFactory',
            'Core/html2doc' => '\Core\Html2Doc\DocServiceFactory',
            'main_navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'Core/ErrorLogger' => 'Core\Log\ErrorLoggerFactory',
        ),
        'abstract_factories' => array(
            'Core\Log\LoggerAbstractFactory',
        ),
        'aliases' => array(
            'forms' => 'FormElementManager',
            'repositories' => 'Core/RepositoryService',
            'translator' => 'mvctranslator',
        ),
    ),
    // Translation settings consumed by the 'translator' factory above.
    'translator' => array(
        'locale' => 'de_DE',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => 'Zend_Validate.%s.php',
            )
        ),
    ),
    'navigation' => array(
        'default' => array(
             'dashboard' => array(
                 'label' => 'Dashboard',
                 'route' => 'lang',
                 'resource' => 'route/lang/dashboard'
             ),
            
        ),
          
    ),
    'login_redirect_route' => function(\Auth\Entity\UserInterface $user) {
        if($user->getRole()=='user') {
            return 'lang/cvs';
        }

    },
    // Configuration of the controller service manager (Which loads controllers)
    'controllers' => array(
        'invokables' => array(
            'Core\Controller\Index' => 'Core\Controller\IndexController',
            'Core\Controller\File'  => 'Core\Controller\FileController',
        ),
    ),
    // Configuration of the controller plugin service manager
    'controller_plugins' => array(
        'factories' => array(
            'mailstackmailer' => 'Core\Controller\Plugin\Mailfactory',
            'config' => 'Core\Controller\Plugin\ConfigFactory',
            'Notification' => '\Core\Controller\Plugin\Service\NotificationFactory',
        ),
        'invokables' => array(
            'listquery' => 'Core\Controller\Plugin\ListQuery',
            'Core/FileSender' => 'Core\Controller\Plugin\FileSender',
            'templatedMail' => 'Core\Controller\Plugin\TemplatedMail',
            'Core/Mailer' => 'Core\Controller\Plugin\Mailer',
            'Core/CreatePaginator' => 'Core\Controller\Plugin\CreatePaginator',
            'Core/ContentCollector' => 'Core\Controller\Plugin\ContentCollector',
            'Core/PaginationParams' => 'Core\Controller\Plugin\PaginationParams',
        ),
        'aliases' => array(
            'filesender' => 'Core/FileSender',
            'mailer'     => 'Core/Mailer',
            'paginator' => 'Core/CreatePaginator',
            'paginationparams' => 'Core/PaginationParams',
        )
    ),
    // Configure the view service manager
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'unauthorized_template' => 'error/403',
        'exception_template' => 'error/index',
        // Map template to files. Speeds up the lookup through the template stack. 
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            //'core/index/index'        => __DIR__ . '/../view/core/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/403' => __DIR__ . '/../view/error/403.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'main-navigation' => __DIR__ . '/../view/partial/main-navigation.phtml',
            'pagination-control' => __DIR__ . '/../view/partial/pagination-control.phtml',
            'core/loading-popup' => __DIR__ . '/../view/partial/loading-popup.phtml',
            'core/notifications' => __DIR__ . '/../view/partial/notifications.phtml',
            'form/core/buttons' => __DIR__ . '/../view/form/buttons.phtml',
            'form/core/privacy' => __DIR__ . '/../view/form/privacy.phtml',
            'core/form/permissions-fieldset' => __DIR__ . '/../view/form/permissions-fieldset.phtml',
            'core/form/permissions-collection' => __DIR__ . '/../view/form/permissions-collection.phtml',
        ),
        // Where to look for view templates not mapped above
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'services' => 'Core\View\Helper\Services',
//            'form' => 'Core\Form\View\Helper\Form',
            'form_element' => 'Core\Form\View\Helper\FormElement',
//            'form_partial' => 'Core\Form\View\Helper\FormPartial',
//            'form_collection' => 'Core\Form\View\Helper\FormCollection',
//            'form_row' => 'Core\Form\View\Helper\FormRow',
//            'form_multi_checkbox' => 'Core\Form\View\Helper\FormMultiCheckbox',
//            'form_radio' => 'Core\Form\View\Helper\FormRadio',
//            'form_daterange' => 'Core\Form\View\Helper\FormDateRange',
//            'build_query' => 'Core\View\Helper\BuildQuery',
            'form' => 'Core\Form\View\Helper\Form',
            'formPartial' => '\Core\Form\View\Helper\FormPartial',
            'formcollection' => 'Core\Form\View\Helper\FormCollection',
            'formrow' => 'Core\Form\View\Helper\FormRow',
            'formrowcombined' => 'Core\Form\View\Helper\FormRowCombined',
            'dateFormat' => 'Core\View\Helper\DateFormat',
            'salutation' => 'Core\View\Helper\Salutation',
            'truncate' => 'Core\View\Helper\Truncate',
            'period' => 'Core\View\Helper\Period',
            'link'   => 'Core\View\Helper\Link',
            'rating' => 'Core\View\Helper\Rating',
            'base64' => 'Core\View\Helper\Base64',
            'insertFile' => 'Core\View\Helper\InsertFile',
            'alert' => 'Core\View\Helper\Alert',
            'spinnerButton' => 'Core\Form\View\Helper\Element\SpinnerButton',
        ),
        'factories' => array(
            'params' => 'Core\View\Helper\Service\ParamsHelperFactory',
            'headscript' => 'Core\View\Helper\Service\HeadScriptFactory',
        ),
        'initializers' => array(
//            '\Core\View\Helper\Service\HeadScriptInitializer',
        ),
    ),
    
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
            'message_separator_string' => '</li><li>',
            'message_close_string'     => '</li></ul></div>',
        ),
        'headscript' => array(
            'Core/js/notification.js',
        ),
    ),
    
    'filters' => array(
        'invokables' => array(
            'Core/Repository/PropertyToKeywords' => 'Core\Repository\Filter\PropertyToKeywords',
        ),
    ),
    
    'form_elements' => array(
        'invokables' => array(
            'DefaultButtonsFieldset' => '\Core\Form\DefaultButtonsFieldset',
            'DefaultSaveButtonsFieldset' => '\Core\Form\DefaultSaveButtonsFieldset',
            'DefaultUnloadButtonsFieldset' => '\Core\Form\DefaultUnloadButtonsFieldset',
            'Core/ListFilterButtons' => '\Core\Form\ListFilterButtonsFieldset',
            'Core\FileCollection' => 'Core\Form\FileCollection',
            'Core/LocalizationSettingsFieldset' => 'Core\Form\LocalizationSettingsFieldset',
            'Core/RatingFieldset' => 'Core\Form\RatingFieldset',
            'Core/Rating' => 'Core\Form\Element\Rating',
            'Core/PermissionsFieldset' => 'Core\Form\PermissionsFieldset',
            'Core/PermissionsCollection' => 'Core\Form\PermissionsCollection',
            'Location' => 'Zend\Form\Element\Text',
            'Core/Spinner-Submit' => 'Core\Form\Element\SpinnerSubmit',
        ),
        'factories' => array(
            'Core/PolicyCheck' => 'Core\Form\Element\PolicyCheckFactory',
        ),
    ),
    'mails' => array(
        'from' => array(
            'email' => 'peopleplustech.com',
            'name' => 'PPT Recruitment'
        ),
//        'option' => array(
//            'name' => 'recruit.peopleplustech.com',
//            'host' => '127.0.0.1',
//            'connection_class' => 'plain',
//            'connection_config' => array(
//                'username' => 'ppt',
//                'password' => 'pptats1231',
//            ),
//        ),
        'option' => array(
            'host' => 'smtp.gmail.com',
            'connection_class' => 'login',
            'connection_config' => array(
                'ssl' => 'tls',
                'username' => 'dbtinoy.app@gmail.com',
                'password' => 'p4$$w0rd'
            ),
            'port' => 587,
        )
    ),
);
