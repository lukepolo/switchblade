<?php

namespace Modules\Tracer\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;

class JSAPI extends RestController
{
    public function store()
    {
	echo 'test'; die;
    }
    
    public function index()
    {
        echo 'here'; die;
    }
}