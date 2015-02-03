<?php
// Bootstrap the framework DO NOT edit this
require COREPATH.'bootstrap.php';


Autoloader::add_classes(array(
    'Controller_Template' => APPPATH.'classes/controller/template.php',
    'Form' => APPPATH.'classes/core/form.php',
    'Controller_Rest' => APPPATH.'classes/core/controller/rest.php',
    'Controller_Hybrid' => APPPATH.'classes/core/controller/hybrid.php',
    'Security' => APPPATH.'classes/core/security.php'
));

// Register the autoloader
Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */
Fuel::$env = (isset($_SERVER['FUEL_ENV']) ? $_SERVER['FUEL_ENV'] : Fuel::DEVELOPMENT);

// Initialize the framework with the config file.
Fuel::init('config.php');
