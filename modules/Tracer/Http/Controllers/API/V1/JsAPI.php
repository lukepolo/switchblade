<?php

namespace Modules\Tracer\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;

class JsAPI extends RestController
{
    public function index()
    {
	Dump(\Request::input());
        \App::abort(204);
    }
}