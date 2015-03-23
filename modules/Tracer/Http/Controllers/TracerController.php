<?php

namespace Modules\Tracer\Http\Controllers;

use \App\Http\Controllers\Controller;

use Modules\Tracer\Models\Mongo\Bug;

class TracerController extends Controller 
{
    public function index()
    {
        return view('tracer::index');
    }
    public function getDashboard()
    {
        $bugs = Bug::with('history')
            ->with('browsers')
            ->get();
        
        return view('tracer::dashboard', ['bugs' => $bugs]);
    }
    
    public function getBug($id)
    {
        $bug = Bug::where('_id', '=', $id)
            ->with('history')
            ->with('browsers')
            ->with('browsers.versions')
            ->first();
                
        return view('tracer::bug', ['bug' => $bug]);
    }
}