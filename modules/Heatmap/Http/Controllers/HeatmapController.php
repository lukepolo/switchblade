<?php

namespace Modules\Heatmap\Http\Controllers;

use \App\Http\Controllers\Controller;

class HeatmapController extends Controller
{
    public function index()
    {
	return view('heatmap::index');
    }
}