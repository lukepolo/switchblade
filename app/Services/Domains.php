<?php

namespace App\Services;

use App\Models\Mongo\Domain;

class Domains
{
    public function get($domain_url)
    {
        if(\Auth::check())
        {
            $user = \Auth::user();
        }
        else
        {
            $user = \App::make('user');
        }
        
	$domain = Domain::where('user_id', '=', $user->id)
	    ->where('domain', '=', parse_url($domain_url)['host'])
	    ->first();

        if(empty($domain) === true)
        {
            // We can group the hosts so we are able to reteieve the info alot faster
            $domain = Domain::create([
		'domain' => parse_url($domain_url)['host'],
                'user_id' => $user->id
	    ]);
        }

	return $domain;
    }
}
