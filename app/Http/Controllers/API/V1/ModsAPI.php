<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\RestController;

class ModsAPI extends RestController
{
    public function index()
    {
        // Based on their activated mods , grab the JS code to execute
        $mods[] = \Modules\Absplit\Http\Controllers\API\V1\AbsplitAPI::getCode();
        $mods[] = \Modules\Heatmap\Http\Controllers\API\V1\HeatmapAPI::getCode();

        return response($mods);
    }
}
