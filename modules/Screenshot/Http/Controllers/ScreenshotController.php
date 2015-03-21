<?php

namespace Modules\Screenshot\Http\Controllers;

use \App\Http\Controllers\Controller;
Use Modules\Screenshot\Models\Mongo\Screenshot;
Use Modules\Screenshot\Models\Mongo\ScreenshotRevision;
use App\Models\Mongo\Domain;

class ScreenshotController extends Controller
{
    public function index()
    {
	return view('screenshot::index');
    }

    public function getDashboard()
    {
	// Unquie Screenshots
	$screenshots = ScreenshotRevision::has('screenshots')->get();

	$domain = Domain::first();
	if(empty($domain) === false)
	{
	    $domain = $domain->domain;
	}
	else
	{
	    $domain = 'http://google.com';
	}

	if($screenshots->count() == 0)
	{
	    return view('screenshot::dashboard', ['domain' => $domain]);
	}
	else
	{
	    return view('screenshot::dashboard', ['screenshots' => $screenshots, 'domain' => $domain]);
	}
    }
}