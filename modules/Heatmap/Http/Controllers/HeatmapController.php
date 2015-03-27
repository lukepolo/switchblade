<?php

namespace Modules\Heatmap\Http\Controllers;

use \App\Http\Controllers\Controller;

use Modules\Heatmap\Models\Mongo\HeatmapUrl;
use Modules\Heatmap\Models\Mongo\HeatmapUser;
use Modules\Heatmap\Models\Mongo\HeatmapPoint;
use Modules\Heatmap\Models\Mongo\HeatmapClick;
use Modules\Screenshot\Models\Mongo\ScreenshotRevision;

class HeatmapController extends Controller
{
    public function index()
    {
	return view('heatmap::index');
    }

    public function getMap($id)
    {
        $heatmap_url = HeatmapUrl::where('_id', '=', $id)->first();

	// Make sure its their domain!
	$points = HeatmapPoint::where('heatmap_url_id', '=', $id)
            ->get();

	$clicks = HeatmapClick::where('heatmap_url_id', '=', $id)
	    ->get();

	if(empty($heatmap_url) === false)
	{
	    // get the most recent screenshot
	    $screenshot = ScreenshotRevision::where('url', '=', $heatmap_url->url)
		->orderBy('created_at', 'desc')
		->first();

	    if(empty($screenshot) === true)
	    {
		return view('heatmap::heatmap',[
		    'reason' => 'Screenshot Still Rendering'
		]);
	    }
	    else
	    {
		$point_data = array();
		foreach($points as $point)
		{
                    $point_data = array_merge($point_data, $point->data);
		}

		$click_data = array();
		foreach($clicks as $click)
		{
                    $click_data[] = $click->data;
		}

		$user_count = HeatmapUser::where('heatmap_url_id', '=', $heatmap_url->id)
		    ->count();

		return view('heatmap::heatmap', [
		    'screenshot' => $screenshot,
		    'point_data' => json_encode($point_data),
		    'click_data' => json_encode($click_data),
		    'total_points' => count($point_data),
		    'total_users' => $user_count
		]);
	    }
	}
	else
	{
	    return view('heatmap::heatmap',[
		'reason' => 'You dont have any heap map users yet!'
	    ]);
	}
    }

    public function getDashboard()
    {
	$urls = HeatmapUrl::get();

	return view('heatmap::dashboard', ['urls' => $urls]);
    }
}