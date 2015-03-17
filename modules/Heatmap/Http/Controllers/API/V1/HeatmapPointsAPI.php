<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use \App\Models\Mongo\Domain;
use Modules\Heatmap\Models\Mongo\HeatmapPoint;

class HeatmapPointsAPI extends RestController
{
    public function store()
    {
	$user = \App::make('user');

        HeatmapPoint::create([
	    'heatmap_user_id' => \Request::input('user'),
            'data' => \Request::input('point_data')
	]);

	return response()->json();
    }
}