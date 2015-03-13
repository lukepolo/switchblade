<?php namespace Modules\Analytics\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use \App\Models\Mongo\Domain;
use  \Modules\Analytics\Models\Mongo\PageViews;

class PageViewController extends RestController
{
    public function index()
    {
	$user = \App::make('user');

        $domain = Domain::where('user_id', '=', $user->id)
	    ->where('domain', '=', parse_url($_SERVER['HTTP_REFERER'])['host'])
	    ->first();

        if(empty($domain) === true)
        {
            // We can group the hosts so we are able to reteieve the info alot faster
            $domain = Domain::create([
		'domain' => parse_url($_SERVER['HTTP_REFERER'])['host'],
                'user_id' => $user->id
	    ]);
        }

        PageViews::create([
	    'domain_id' => $domain->id,
            'url' => $_SERVER['HTTP_REFERER'],
            'user_id' => $user->id,
            'time' => time()
	]);
    }
}