<?php

namespace Modules\Screenshot\Http\Middleware;

use Closure;

class ScreenshotTokens
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	if(empty(\Session::get('preview_token')) === false && \Session::get('preview_token') == $request->input('preview_token'))
	{
	    if(\Session::get('preview_count') == 2)
	    {
		\Session::forget('preview_token');
	    }
	    else
	    {
		\Session::put('preview_count', \Session::get('preview_count') + 1);
	    }
	    return $next($request);
	}
	else
	{
	    // NOT AUTHORIZED
	    abort(403, 'Not Authorized!');
	}
    }
}
