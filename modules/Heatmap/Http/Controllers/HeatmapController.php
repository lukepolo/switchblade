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

    public function getDashboard()
    {
	$user = HeatmapUser::has('HeatmapPoints')
	    ->with('HeatmapPoints')
	    ->first();

	// since these were created in node we cannot use thenormal date time stuff
	$screenshot = ScreenshotRevision::where('url', '=', $user->url)
	    ->where('created_at', '>=', strtotime($user->created_at))
	    ->first();

	if(empty($screenshot) === true)
	{
	    // get the most recent screenshot
	    $screenshot = ScreenshotRevision::where('url', '=', $user->url)
		->orderBy('created_at', 'desc')
		->first();
	}

	if(empty($screenshot) === true)
	{
	    echo 'Still Rendering!';
	}
	else
	{
	    $data = array();
	    foreach($user->HeatmapPoints as $HeatmapPoints)
	    {
		$data = array_merge($data, $HeatmapPoints->data);
	    }
	}

	// we are in test mode, just do this for now
	return view('heatmap::dashboard', [
	    'screenshot' => $screenshot,
	    'data' => json_encode($data)
	]);
    }
}