<?php
return array(
    '_root_'  => 'home',  // The default route
    '_404_'   => 'core/404',    // The main 404 route

    'blade.js' => 'assets/js/blade.js', 
   
    'login' => 'auth',
    'logout' => 'auth/logout',
    
    'my_profile' => 'profile',
    
    // MODULES 
    
    // ABSPLIT
    'absplit' => 'absplit/home',
    'absplit/dashboard' => 'absplit/home/dashboard',
    'absplit/get/(:any)' => 'absplit/editor/get/$1',
    'absplit/editor/(:num)' => 'absplit/editor/experiment/$1',
    
    // ANALYTICS
    'analytics' => 'analytics/home',
    'analytics/dashboard' => 'analytics/home/dashboard',
    
    // HeatMap
    'heatmap' => 'heatmap/home',
    'heatmap/dashboard' => 'heatmap/home/dashboard',
    
    // Screenshot / Ketch Screen
    'screenshot' => 'screenshot/home',
    'screenshot/dashboard' => 'screenshot/home/dashboard',
    
);