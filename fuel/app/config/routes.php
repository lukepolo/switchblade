<?php
return array(
    '_root_'  => 'home',  // The default route
    '_404_'   => 'core/404',    // The main 404 route

    'blade.js' => 'assets/js/blade.js', 
   
    'login' => 'auth',
    'logout' => 'auth/logout',
    
    'my_profile' => 'profile',
    
    // Modules default path
    'absplit' => 'absplit/home',
    'absplit/get/(:any)' => 'absplit/editor/get/$1',
    'absplit/editor/(:num)' => 'absplit/editor/experiment/$1' 
);