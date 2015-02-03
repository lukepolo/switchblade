<?php

    try
    {
        $host = explode('.',parse_url(Uri::Base())['host']);
        if(count($host) === 3)
        {
            $host = $host[1];
        }
        else
        {
            $host = $host[0];
        }
    }
    catch(\Exception $e)
    {
        $host = '';
    }
    
    // If the host is not switchblade we need to redirect them root to a diff path
    switch($host)
    {
        case 'ketchscreen':
        case 'ketchurl':
            $overrides =  array(
                '_root_'  => 'screenshot/home',  // The default route
                // Add any special routes here, otherwise we assume the default routes begin with screenshot
                '(:any)' => 'screenshot/$1'
            );
        break;
        default :
            $overrides = array();
        break;
    }
    
    
    // GLOBAL ROUTES
    return array_merge(array(
        '_root_'  => 'home',  // The default route
        '_404_'   => 'core/404',    // The main 404 route
        
        'login' => 'auth',
        'logout' => 'auth/logout',

        'my_profile' => 'profile',
        
        // MODULES 

        // TODO - Move dashboards to their own controllers...no reason to have them in the home
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
        ),
        $overrides
    );