<?php

namespace Modules\Analytics\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use \App\Models\Mongo\Domain;
use  \Modules\Analytics\Models\Mongo\PageViews;

class PageViewController extends RestController
{
    public function index()
    {
	$user = \App::make('user');
	$domain = \Domains::getDomain($user);

        PageViews::create([
	    'domain_id' => $domain->id,
            'url' => $_SERVER['HTTP_REFERER'],
            'user_id' => $user->id,
            'time' => time()
	]);

	return \response()->json();
    }
}