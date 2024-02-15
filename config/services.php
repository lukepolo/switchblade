<?php

if(isset($_SERVER['HTTP_HOST']) === true)
{
    $host = $_SERVER['HTTP_HOST'];
}
else
{
    $host = '';
}

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
	'domain' => '',
	'secret' => '',
    ],

    'mandrill' => [
	'secret' => '',
    ],

    'ses' => [
	'key' => '',
	'secret' => '',
	'region' => 'us-east-1',
    ],

    'stripe' => [
	'model'  => 'User',
	'secret' => env('STRIPE_SECRET'),
    ],
    'google' => [
	'client_id' => '',
	'client_secret' => '',
	'redirect' => 'https://login.switchblade.lukepolo.com/auth/callback/google'
    ],
    'twitter' => [
	'client_id' => 'DxbUf9Dn6KhUuPDqLvvfdMpD4',
	'client_secret' => '',
	'redirect' => 'https://login.switchblade.lukepolo.com/auth/callback/twitter'
    ]
];
