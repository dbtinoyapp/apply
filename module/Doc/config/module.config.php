<?php

return array(
    'service_manager' => array(
        'invokables' => array(
            'Html2DocConverter' => 'Doc\Module',
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'doc/application/details/button' => __DIR__ . '/../view/applicationDetailsButton.phtml',
        )
    ),
);