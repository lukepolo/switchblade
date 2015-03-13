<?php namespace Modules\Screenshot\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class ScreenshotController extends Controller {

    public function index()
    {
	return View::make('screenshot::index');
    }

    public function getDashboard()
    {
	
    }
}