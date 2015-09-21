<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'odm_default' => array(
                'drivers' => array(
                    'Cv\Entity' => 'annotation',
                ),
            ),
        ),
    ),
    // Translations
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    // Routes
    'router' => array(
        'routes' => array(
            'lang' => array(
                'child_routes' => array(
                    'cvs' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/cvs',
                            'defaults' => array(
                                'controller' => 'Cv\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'detail-full' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:id',
                                    'constraints' => array(
                                        'id' => '[a-z0-9]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Cv\Controller\Manage',
                                        'action' => 'detail-full',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'new' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/new',
                                    'defaults' => array(
                                        'controller' => 'Cv\Controller\Manage',
                                        'action' => 'new',
                                        'id' => false,
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'constraints' => array(
                                        'id' => '[a-z0-9]+',
                                    ),                                    
                                    'defaults' => array(
                                        'controller' => 'Cv\Controller\Manage',
                                        'action' => 'edit',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'viewByUser' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/view-by-user',
                                    'defaults' => array(
                                        'controller' => 'Cv\Controller\Manage',
                                        'action' => 'viewByUser',
                                        'id' => false,
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'populate' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/populate/:type',
                                    'defaults' => array(
                                        'controller' => 'Cv\Controller\Manage',
                                        'action' => 'populate',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'acl' => array(
        'rules' => array(
            'user' => array(
                'allow' => array(
                    'route/lang/cvs',
                    'Cv\Controller\Manage',
                ),
               'deny' => array(
                    'Cv\Controller\Manage' => 'detail',
                ),                
            ),
            'recruiter' => array(
                'allow' => array(
                    'route/lang/cvs',
                    'Cv\Controller\Manage'
                ),                
                'deny' => array(
                    'Cv\Controller\Manage' => 'new',
                ),
            ),
            'admin' => array(
                'allow' => array(
                    'route/lang/cvs',
                    'Cv\Controller\Manage'
                ),                
                'deny' => array(
                    'Cv\Controller\Manage' => 'new',
                ),
            ),
        ),
    ),
    // Configuration of the controller service manager (Which loads controllers)
    'controllers' => array(
        'invokables' => array(
            'Cv\Controller\Index' => 'Cv\Controller\IndexController',
            'Cv\Controller\Manage' => 'Cv\Controller\ManageController',
        ),
    ),
    // Navigation
//    'navigation' => array(
//        'default' => array(
//            'resume' => array(
//                'label' => /* @translate */ 'Resume',
//                'route' => 'lang/cvs',
//                'resource' => 'route/lang/cvs',
//                'order' => 10,
////                'pages' => array(
////                    'list' => array(
////                        'label' => /* @translate */ 'Overview',
////                        'route' => 'lang/cvs',
////                    ),
////                    'new' => array(
////                        'label' => /* @translate */ 'Create Resume',
////                        'route' => 'lang/cvs/new',
////                    ),
////                ),
//            ),
////            'create_resume' => array(
////                'label' =>  /*@translate*/ 'Create Resume',
////                'route' => 'lang/cvs/create',
////                'resource' => 'route/lang/cvs/create',
////                'order' => 10,
////            ),
//        ),
//    ),
    'view_manager' => array(
        // Map template to files. Speeds up the lookup through the template stack.
        'template_map' => array(
            'form/cv/computing-skills' => __DIR__ . '/../view/form/computing-skills.phtml',
        //'form/div-wrapper-fieldset' => __DIR__ . '/../view/form/div-wrapper-fieldset.phtml',
        ),
        // Where to look for view templates not mapped above
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'filters' => array(
        'factories' => array(
            'Cv/PaginationQuery' => 'Cv\Repository\Filter\PaginationQueryFactory',
            'Cv/JsonPaginationQuery' => 'Cv\Repository\Filter\JsonPaginationQueryFactory',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'CvFormFull' => '\Cv\Form\CvFull',
            'CvForm' => '\Cv\Form\Cv',
            'CvFieldset' => '\Cv\Form\CvFieldset',
            'CvFieldsetFull' => '\Cv\Form\CvFieldsetFull',
            'EducationFieldset' => '\Cv\Form\EducationFieldset',
            'TrainingFieldset' => '\Cv\Form\TrainingFieldset',
            'EmploymentFieldset' => '\Cv\Form\EmploymentFieldset',
            'NativeLanguageFieldset' => '\Cv\Form\NativeLanguageFieldset',
            'OtherLanguageFieldset' => '\Cv\Form\OtherLanguageFieldset',
            'ComputingSkillFieldset' => '\Cv\Form\ComputingSkillFieldset',
            'ComplianceSkillFieldset' => '\Cv\Form\ComplianceSkillFieldset',
            'ManagementSkillFieldset' => '\Cv\Form\ManagementSkillFieldset',
        ),
//        'initializers' => array(  
//            function($instance, ServiceLocatorInterface $sl) {  
//                if($instance instanceof DocumentManagerAwareInterface) {  
//                    $instance->setDocumentManager(  
//                        $sl->getServiceLocator()->get('doctrine.documentmanager.odm_default')  
//                    );  
//                }  
//            }  
//        ),  
        'factories' => array(
            'Cv' => '\Cv\Form\CvFactory',
            'EducationCollection' => '\Cv\Form\EducationCollectionFactory',
            'TrainingCollection' => '\Cv\Form\TrainingCollectionFactory',
            'ComputingSkillCollection' => '\Cv\Form\ComputingSkillCollectionFactory',
            'ComplianceSkillCollection' => '\Cv\Form\ComplianceSkillCollectionFactory',
            'ManagementSkillCollection' => '\Cv\Form\ManagementSkillCollectionFactory',
        ),
    ),
);
