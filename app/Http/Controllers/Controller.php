<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;
    public function __construct(Request $request)
    {
	if(\Auth::check())
	{
	    $gamp = \GAMP::setClientId(\Session::getId());
	}
	else
	{
	    $gamp = \GAMP::setClientId(\Auth::user()->id);
	}

	$gamp->setUserAgentOverride($_SERVER['HTTP_USER_AGENT']);
	$gamp->setIpOverride($request->ip());
	$gamp->setDocumentPath('dev_'.\Request::path());
	$gamp->setAsyncRequest(true);
	$gamp->sendPageview();

	// Sets the Title, based on the controller
	$name = preg_replace('/Controller@.*/', '', str_replace(\Route::getCurrentRoute()->getAction()["namespace"].'\\', '', \Route::currentRouteAction()));
	\View::share('title', 'Switch Blade | '.$name);
	\View::share('container_class', 'container');
    }
}
