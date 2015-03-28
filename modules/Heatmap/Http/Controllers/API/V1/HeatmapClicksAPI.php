<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use Modules\Heatmap\Models\Mongo\HeatmapClick;

class HeatmapClicksAPI extends RestController
{
    public function index()
    {
	$user = \App::make('user');

        HeatmapClick::create([
	    'heatmap_url_id' => \Request::input('heatmap_url_id'),
	    'user_id' => \Request::input('user_id'),
            'data' => \Request::input('click_data'),
	    'width' => \Request::input('width')
	]);

        \Emitter::apply_broadcast(
            'add_realtime_clicks',
            action('\Modules\Heatmap\Http\Controllers\HeatmapController@getMap', ['id' => \Request::input('heatmap_url_id')]),
            \Request::input('click_data')
        );

	\App::abort(204);
    }
}