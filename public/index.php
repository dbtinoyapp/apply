<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL | E_STRICT);

error_reporting(E_ALL ^ E_DEPRECATED);

date_default_timezone_set('Asia/Manila');

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
    $loader->set(null, array('module'));
} else {
    throw new \RuntimeException('Could not initialize autoloading.');
}
    
// Run the application!
Zend\Mvc\Application::init(require 'config/config.php')->run();

