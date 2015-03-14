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
	    'user_id' => $user->id,
            'data' => \Request::input('point_data'),
            'user' => \Request::input('user')
	]);

	return response()->json();
    }
}