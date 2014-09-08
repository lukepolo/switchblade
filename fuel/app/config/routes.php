<?php
return array(
    '_root_'  => 'home',  // The default route
    '_404_'   => 'core/404',    // The main 404 route


    // Modules default path
    'jumpsplit' => 'jumpsplit/home',
    'jumpsplit/get/(:segment)/(:any)' => 'jumpsplit/editor/get/$1/$2'
);