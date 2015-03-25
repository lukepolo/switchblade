<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use \App\Models\Mongo\Domain;
use Modules\Heatmap\Models\Mongo\HeatmapPoint;

class HeatmapPointsAPI extends RestController
{
    public function index()
    {
	$user = \App::make('user');

        HeatmapPoint::create([
	    'heatmap_url_id' => \Request::input('heatmap_url_id'),
            'data' => \Request::input('point_data')
	]);
        
        \Emitter::apply_broadcast(
            'add_realtime_data',
            action('\Modules\Heatmap\Http\Controllers\HeatmapController@getMap', ['id' => \Request::input('heatmap_url_id')]),
            \Request::input('point_data')
        );
        
	\App::abort(204);
    }
}