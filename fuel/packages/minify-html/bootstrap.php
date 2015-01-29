<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Luke Policinski
 * @license    MIT License
 * @copyright  2015
 * @link       http://lukepolo.com
 */

Autoloader::add_core_namespace('MinifyHTML');

Autoloader::add_classes(array(
    /**
     * MinifyHTML classes.
     */
    'MinifyHTML\\MinifyHTML'    => __DIR__.'/classes/minifyhtml.php',
    'View'    => __DIR__.'/classes/core/classes/view.php',
));
