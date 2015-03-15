<?php

namespace Modules\Screenshot\Http\Controllers;

use \App\Http\Controllers\Controller;
Use Modules\Screenshot\Models\Mongo\Screenshot;

class ScreenshotController extends Controller
{
    public function index()
    {
	return view('screenshot::index');
    }

    public function getDashboard()
    {
	$screenshots = Screenshot::get();

	return view('screenshot::dashboard', ['screenshots' => $screenshots]);
    }
}