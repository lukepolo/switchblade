<?php

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
	    'client_id' => '528960278580-0ceodaa98qa4kqbrnfc7rd2k1460knv8.apps.googleusercontent.com',
	    'client_secret' => 'Ddf_LOsXtW7uB8U0JyozfZmP',
	    'redirect' => 'https://oauth.switchblade.io/auth/callback/google'
	]
];
