<?php
    // a MySQL driver configuration
    'development' => array(
        'type'           => 'mysqli',
        'connection'     => array(
            'hostname'       => 'localhost',
            'database'       => 'jumpcord_dev',
            'username'       => 'jumpcord',
            'password'       => '!MolyPOX!',
            'persistent'     => true,
            'compress'       => true,
        ),
        'identifier'     => '`',
        'table_prefix'   => '',
        'charset'        => 'utf8',
        'enable_cache'   => false,
        'profiling'      => true,
        'readonly'       => false,
    ),
    
    // a MySQL driver configuration
    'production' => array(
        'type'           => 'mysqli',
        'connection'     => array(
            'hostname'       => 'localhost',
            'database'       => 'jumpcord',
            'username'       => 'jumpcord',
            'password'       => '!MolyPOX!',
            'persistent'     => true,
            'compress'       => true,
        ),
        'identifier'     => '`',
        'table_prefix'   => '',
        'charset'        => 'utf8',
        'enable_cache'   => true,
        'profiling'      => false,
        'readonly'       => false,
    ),