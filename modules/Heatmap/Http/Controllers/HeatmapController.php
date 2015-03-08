<?php namespace Modules\Heatmap\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class HeatmapController extends Controller {

	public function index()
	{
		return View::make('heatmap::index');
	}
	
}