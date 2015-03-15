<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;
    public function __construct()
    {
	if(\Auth::check())
	{
	    $gamp = \GAMP::setClientId(\Auth::user()->id);
	}
	else
	{
	    $gamp = \GAMP::setClientId(\Session::getId());
	}

	$gamp->setDocumentPath(\Request::path());
	$gamp->sendPageview();

	// Sets the Title, based on the controller
	$name = preg_replace('/Controller@.*/', '', str_replace(\Route::getCurrentRoute()->getAction()["namespace"].'\\', '', \Route::currentRouteAction()));
	\View::share('title', 'Switch Blade | '.$name);
	\View::share('container_class', 'container');
    }
}
