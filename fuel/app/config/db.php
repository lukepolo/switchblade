<?php
return array(
    'default' => array(
        'type'           => 'mysqli',
        'connection'     => array(
            'hostname'       => 'localhost',
            'database'       => 'switchblade_dev',
            'username'       => 'switchblade',
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
    'production' => array(
        'type'           => 'mysqli',
        'connection'     => array(
            'hostname'       => 'localhost',
            'database'       => 'switchblade',
            'username'       => 'switchblade',
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
    'mongo' => array(
        'default' => array(
            'hostname'   => 'localhost',
            'database'   => 'switchblade_dev',
        ),
    )
);