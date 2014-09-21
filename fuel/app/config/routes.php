<?php
return array(
    '_root_'  => 'home',  // The default route
    '_404_'   => 'core/404',    // The main 404 route

    'login' => 'auth',
    'logout' => 'auth/logout',
    
    'my_profile' => 'profile',
    
    // Modules default path
    'jumpsplit' => 'jumpsplit/home',
    'jumpsplit/get/(:any)' => 'jumpsplit/editor/get/$1',
    
);