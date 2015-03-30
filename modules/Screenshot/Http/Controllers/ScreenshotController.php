<?php

namespace Modules\Screenshot\Http\Controllers;

use App\Http\Controllers\Controller;
Use Modules\Screenshot\Models\Mongo\ScreenshotRevision;
use App\Models\Mongo\Domain;
use App\Models\User;

class ScreenshotController extends Controller
{
    public function getIndex()
    {
	$screenshot = ScreenshotRevision::where('url', '=', 'bing.com/')
	    ->orderBy('created_at', 'desc ')
	    ->first();

	return view('screenshot::index', ['screenshot' => $screenshot]);
    }

    public function postPreview()
    {
	$preview_token = str_random(40);
	\Session::put('preview_token', $preview_token);
	\Session::put('preview_count', 0);

	return view('screenshot::preview', [
	    'url' => \Request::get('url'),
	    'preview_token' => $preview_token
	]);
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

    public function getShortShot()
    {
	// use a standard user aka KETCH
	$user = User::where('id', '=', '1')
	    ->first();

	return \Screenshots::get(\Request::get('url'), $user, [
	    'width' => 600,
	    'height' => 667,
	    'delay' => 700
	]);
    }

    public function getLongShot()
    {
	// use a standard user aka KETCH
	$user = User::where('id', '=', '1')
	    ->first();

	return \Screenshots::get(\Request::get('url'), $user, [
	    'delay' => 700
	]);
    }

    public function getMobileShot()
    {
	// use a standard user aka KETCH
	$user = User::where('id', '=', '1')
	    ->first();

	return \Screenshots::get(\Request::get('url'), $user, [
	    'width' => 375,
	    'height' => 667,
	    'delay' => 700,
	    'mobile' => true
	]);
    }
}