<?php

namespace Modules\Tracer\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;

class TracerAPI extends RestController
{
    public static function getCode()
    {
	$user = \App::make('user');
        
        // CUSTOM JS back to the user
        return array(
            'function' => 'insert_script',
            'data' => array(
                'url' => asset('assets/js/tracer.js')
            )
        );
    }
    
    public function index()
    {
        \Tracer::store(\Request::input());
        \App::abort(204);
    }
    
    public function store()
    {
        \Tracer::store(\Request::input());
        return response()->status(200);
    }
}