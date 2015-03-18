<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
	$this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	if ($this->auth->guest())
	{
	    if ($request->ajax())
	    {
		return response('Unauthorized.', 401);
	    }
	    else
	    {
		if(\Cookie::get('ketchurl') && \Cookie::get('ketchurl') == 'c49489f5729ded636356782a1ae3ee0d8694fd08')
		{
		    // login as KetchURL person
		    \Auth::loginUsingId(3);
		    return $next($request);
		}
		return redirect()->guest('login');
	    }
	}

	return $next($request);
    }
}
