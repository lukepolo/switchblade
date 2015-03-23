<?php

namespace Modules\Analytics\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use  \Modules\Analytics\Models\Mongo\PageViews;

class PageViewAPI extends RestController
{
    public function index()
    {
	$user = \App::make('user');
	$domain = \Domains::get($_SERVER['HTTP_REFERER']);
        
        PageViews::create([
	    'domain_id' => $domain->id,
            'url' => $_SERVER['HTTP_REFERER'],
            'user_id' => $user->id,
            'time' => time()
	]);

	\App::abort(204);
    }
}