<?php

namespace Modules\Heatmap\Http\Controllers;

use \App\Http\Controllers\Controller;

use Modules\Heatmap\Models\Mongo\HeatmapUrl;
use Modules\Heatmap\Models\Mongo\HeatmapPoint;
use Modules\Screenshot\Models\Mongo\ScreenshotRevision;

class HeatmapController extends Controller
{
    public function index()
    {
	return view('heatmap::index');
    }

    public function getMap($id)
    {
        $url = HeatmapUrl::where('_id', '=', $id)->first();
        
	// Make sure its their domain!
	$heatmaps = HeatmapPoint::where('heatmap_url_id', '=', $id)
            ->get();

	if(empty($url) === false)
	{
	    // get the most recent screenshot
	    $screenshot = ScreenshotRevision::where('url', '=', $url->url)
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
		$data = array();
		foreach($heatmaps as $heatmap)
		{
                    $data = array_merge($data, $heatmap->data);
		}

		// we are in test mode, just do this for now
		return view('heatmap::heatmap', [
		    'screenshot' => $screenshot,
		    'data' => json_encode($data),
		    'total_points' => count($data),
		    'total_users' => $url->user_count
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