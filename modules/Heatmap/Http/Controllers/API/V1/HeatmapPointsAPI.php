<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use \App\Models\Mongo\Domain;
use Modules\Heatmap\Models\Mongo\HeatmapUsers;
use Modules\Heatmap\Models\Mongo\HeatmapPoints;

class HeatmapPointsAPI extends RestController
{
    public function store()
    {
	$user = \App::make('user');

        HeatmapPoints::create([
	    'heatmap_user' => \Request::input('user'),
            'data' => \Request::input('point_data')
	]);

	return response()->json();
    }
}