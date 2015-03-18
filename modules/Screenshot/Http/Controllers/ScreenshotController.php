<?php

namespace Modules\Screenshot\Http\Controllers;

use \App\Http\Controllers\Controller;
Use Modules\Screenshot\Models\Mongo\Screenshot;
Use Modules\Screenshot\Models\Mongo\ScreenshotRevision;

class ScreenshotController extends Controller
{
    public function index()
    {
	return view('screenshot::index');
    }

    public function getDashboard()
    {
	// Unquie Screenshots
	$screenshots = ScreenshotRevision::with('screenshots')->get();
	
	return view('screenshot::dashboard', ['screenshots' => $screenshots]);
    }
}