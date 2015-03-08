<?php

return [

    'stubs' => [
        'enabled'   => false,
        'path'      => base_path().'/vendor/pingpong/modules/src/Pingpong/Modules/Commands/stubs',
        'files'     => [
            'start'         => 'start.php',
            'routes'        => 'Http/routes.php',
            'json'          => 'module.json',
            'views/index'   => 'Resources/views/index.blade.php',
            'views/master'  => 'Resources/views/layouts/master.blade.php',
            'scaffold/config' => 'Config/config.php',
        ],
        'replacements' => [
            'start'         => ['LOWER_NAME'],
            'routes'        => ['LOWER_NAME', 'STUDLY_NAME'],
            'json'          => ['LOWER_NAME', 'STUDLY_NAME'],
            'views/index'   => ['LOWER_NAME'],
            'views/master'  => ['STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
        ],
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('modules'),
      
        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */

        'migration' => app_path('database/migrations'),
        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules generator path.
        |
        */

        'generator' => [
            'config' => 'Config',
            'command' => 'Console',
            'migration' => 'Database/Migrations',
            'model' => 'Entities',
            'repository' => 'Repositories',
            'seeder' => 'Database/Seeders',
            'controller' => 'Http/Controllers',
            'filter' => 'Http/Middleware',
            'request' => 'Http/Requests',
            'provider' => 'Providers',
            'lang' => 'Resources/lang',
            'views' => 'Resources/views',
            'test' => 'Tests',
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*')
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'key' => 'pingpong-modules',
        'lifetime' => 60
    ]

];
