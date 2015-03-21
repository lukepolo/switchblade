<?php

namespace Modules\Tracer\Http\Controllers;

use \App\Http\Controllers\Controller;

class TracerController extends Controller 
{
    public function index()
    {
            return view('tracer::index');
    }
    public function getDashboard()
    {
            return view('tracer::index');
    }
}