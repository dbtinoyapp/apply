<?php

use Zend\Stdlib\ArrayUtils;

$env = getenv('APPLICATION_ENV') ?: 'development';

$modules = array(
         'DoctrineModule', 'DoctrineMongoODMModule', 'Core', /*'TwbBundle', */ 'Auth', 'Cv', 'Applications', 'Jobs', 'Settings', 'Pdf', 'Doc', 'FormValidation',
    );

if (!isset($allModules)) {
    $allModules = False;
}
foreach (glob(__dir__ . '/autoload/*.module.php') as $moduleFile) {
    $addModules = require $moduleFile;
    foreach ($addModules as $addModule) {
        if (strpos($addModule, '-') === 0) {
            $remove = substr($addModule,1);
            $modules = array_filter($modules, function ($elem) use ($remove) { return strcasecmp($elem,$remove); });
        }
        else {
            if (!in_array($addModule, $modules)) {
                $modules[] = $addModule;
            }
        }
    }
}

$config = array(

    // Activated modules. (Use folder name)
    'modules' => $modules,
    
    
    // Where to search modules
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
            'Ats\*' => './vendor/extern'
        ),
    
    
        // What configuration files should be autoloaded 
        'config_glob_paths' => array(
            sprintf('config/autoload/{,*.}{global,%s,local}.php', $env)
        ),
        // Use the $env value to determine the state of the flag
        'config_cache_enabled' => ($env == 'production'),
        
        'config_cache_key' => $env,
        
        // Use the $env value to determine the state of the flag
        'module_map_cache_enabled' => ($env == 'production'),
        
        'module_map_cache_key' => 'module_map',
        
        'cache_dir' => 'cache/',
        
        // Use the $env value to determine the state of the flag
        'check_dependencies' => ($env != 'production'),
    ),
    
    'service_listener_options' => array(
        array(
            'service_manager' => 'SettingsManager',
            'config_key'      => 'settings',
            'interface'       => '\Core\ModuleManager\Feature\SettingsProviderInterface',
            'method'          => 'getUserSettings',
        ),
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'SettingsManager' => 'Core\ModuleManager\SettingsManager',
            //'ServiceListenerInterface' => 'Core\mvc\Service\ServiceListener',
        ),
        'factories' => array(
            //'Log' => 'Core\src\Core\Service\Log'
        ),
        
    ),
);

$envConfigFile = __DIR__ . '/config.' . $env . '.php';
if (file_exists($envConfigFile)) {
    if (is_readable($envConfigFile)) {
        $envConfig = include $envConfigFile;
        $config = ArrayUtils::merge($config, $envConfig);
    } else {
        trigger_notice(
            sprintf('Environment config file "%s" is not readable.', $envConfigFile),
            E_USER_NOTICE
        );
    }
}

return $config;