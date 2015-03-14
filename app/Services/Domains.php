<?php
namespace App\Services;

use App\Models\Mongo\Domain;

class Domains
{
    public function getDomain(\App\Models\User $user)
    {
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

	return $domain;
    }
}
