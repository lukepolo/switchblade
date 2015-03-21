<?php

namespace Modules\Heatmap\Http\Controllers;

use \App\Http\Controllers\Controller;

use Modules\Heatmap\Models\Mongo\HeatmapUser;
use Modules\Screenshot\Models\Mongo\ScreenshotRevision;

class HeatmapController extends Controller
{
    public function index()
    {
	return view('heatmap::index');
    }

    public function getHeatmap()
    {
	// Make sure its their domain!
	$users = HeatmapUser::has('HeatmapPoints')
	    ->with('HeatmapPoints')
	    ->where('url', '=', \Request::get('url'))
	    ->get();

	if(empty($users) === false)
	{
	    // get the most recent screenshot
	    $screenshot = ScreenshotRevision::where('url', '=', \Request::get('url'))
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
		foreach($users as $user)
		{
		    foreach($user->HeatmapPoints as $points)
		    {
			$data[] = ['reset' => true];
			$data = array_merge($data, $points->data);
		    }
		}

		// we are in test mode, just do this for now
		return view('heatmap::heatmap', [
		    'screenshot' => $screenshot,
		    'data' => json_encode($data),
		    'total_points' => count($data),
		    'total_users' => count($users)
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
	$urls = HeatmapUser::Distinct('url')->get();

	return view('heatmap::dashboard', ['urls' => $urls]);
    }
}