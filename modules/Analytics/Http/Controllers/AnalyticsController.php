<?php namespace Modules\Analytics\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class AnalyticsController extends Controller {

	public function index()
	{
		return View::make('analytics::index');
	}
	
}