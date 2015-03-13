<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\RestController;

class ModsController extends RestController
{
    public function index()
    {
        // Based on their activated mods , grab the JS code to execute
        $mods[] = \Modules\Absplit\Http\Controllers\API\V1\CodeController::getMods();
//        $mods[] = \HeatMap\Controller_Api::get_code();

        return response($mods);
    }
}